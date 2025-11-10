<?php

use App\Models\Initiative;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create();
});

test('guests cannot access initiatives management', function () {
    $response = $this->get(route('admin.initiatives.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can view initiatives index', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('admin.initiatives.index'));
    $response->assertStatus(200);
});

test('authenticated users can view create initiative form', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('admin.initiatives.create'));
    $response->assertStatus(200);
});

test('authenticated users can create an initiative', function () {
    $this->actingAs($this->user);

    $data = [
        'title' => 'Test Initiative',
        'short_description' => 'This is a test initiative',
        'full_description' => 'This is the full description of the test initiative',
        'sort_order' => 0,
        'is_published' => true,
    ];

    $response = $this->post(route('admin.initiatives.store'), $data);
    
    $response->assertRedirect(route('admin.initiatives.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('initiatives', [
        'title' => 'Test Initiative',
        'slug' => 'test-initiative',
    ]);
});

test('authenticated users can create an initiative with featured image', function () {
    $this->actingAs($this->user);

    $image = UploadedFile::fake()->image('initiative.jpg', 1200, 800);

    $data = [
        'title' => 'Test Initiative with Image',
        'short_description' => 'This is a test initiative',
        'full_description' => 'This is the full description',
        'featured_image' => $image,
        'sort_order' => 0,
        'is_published' => true,
    ];

    $response = $this->post(route('admin.initiatives.store'), $data);
    
    $response->assertRedirect(route('admin.initiatives.index'));
    
    $initiative = Initiative::where('title', 'Test Initiative with Image')->first();
    expect($initiative)->not->toBeNull();
    expect($initiative->featured_image_path)->not->toBeNull();
});

test('authenticated users can update an initiative', function () {
    $this->actingAs($this->user);

    $initiative = Initiative::create([
        'title' => 'Original Title',
        'slug' => 'original-title',
        'short_description' => 'Original description',
        'full_description' => 'Original full description',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    $data = [
        'title' => 'Updated Title',
        'short_description' => 'Updated description',
        'full_description' => 'Updated full description',
        'sort_order' => 1,
        // Not including is_published means it will be false (checkbox not checked)
    ];

    $response = $this->put(route('admin.initiatives.update', $initiative), $data);
    
    $response->assertRedirect(route('admin.initiatives.index'));
    
    $initiative->refresh();
    expect($initiative->title)->toBe('Updated Title');
    expect($initiative->slug)->toBe('updated-title');
    expect($initiative->is_published)->toBe(false);
});

test('authenticated users can delete an initiative', function () {
    $this->actingAs($this->user);

    $initiative = Initiative::create([
        'title' => 'To Delete',
        'slug' => 'to-delete',
        'short_description' => 'Description',
        'full_description' => 'Full description',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    $response = $this->delete(route('admin.initiatives.destroy', $initiative));
    
    $response->assertRedirect(route('admin.initiatives.index'));
    
    $this->assertDatabaseMissing('initiatives', [
        'id' => $initiative->id,
    ]);
});

test('initiative creation requires title and descriptions', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('admin.initiatives.store'), []);
    
    $response->assertSessionHasErrors(['title', 'short_description', 'full_description']);
});

test('initiative slug is auto-generated from title', function () {
    $this->actingAs($this->user);

    $data = [
        'title' => 'My Amazing Initiative',
        'short_description' => 'Description',
        'full_description' => 'Full description',
        'sort_order' => 0,
        'is_published' => true,
    ];

    $this->post(route('admin.initiatives.store'), $data);
    
    $this->assertDatabaseHas('initiatives', [
        'title' => 'My Amazing Initiative',
        'slug' => 'my-amazing-initiative',
    ]);
});
