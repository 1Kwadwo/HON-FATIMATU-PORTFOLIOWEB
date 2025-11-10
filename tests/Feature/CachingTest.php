<?php

use App\Models\ContactSubmission;
use App\Models\GalleryItem;
use App\Models\Initiative;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

test('about page is cached with 1 hour TTL', function () {
    $page = Page::factory()->create(['slug' => 'about']);
    
    // First request should cache the page
    $this->get(route('about'));
    
    expect(Cache::has('pages:about'))->toBeTrue();
    
    // Verify cached data matches
    $cachedPage = Cache::get('pages:about');
    expect($cachedPage->id)->toBe($page->id);
});

test('published news articles are cached with 15 minutes TTL', function () {
    NewsArticle::factory()->count(3)->create(['status' => 'published']);
    
    // First request should cache the articles
    $this->get(route('news'));
    
    expect(Cache::has('news:published:page:1'))->toBeTrue();
});

test('gallery items are cached by category with 30 minutes TTL', function () {
    GalleryItem::factory()->count(3)->create([
        'category' => 'events',
        'is_published' => true,
    ]);
    
    // Request gallery with category filter
    $this->get(route('gallery', ['category' => 'events']));
    
    expect(Cache::has('gallery:category:events'))->toBeTrue();
});

test('gallery all items are cached', function () {
    GalleryItem::factory()->count(3)->create(['is_published' => true]);
    
    // Request gallery without filter
    $this->get(route('gallery'));
    
    expect(Cache::has('gallery:all'))->toBeTrue();
});

test('published initiatives are cached with 1 hour TTL', function () {
    Initiative::factory()->count(3)->create(['is_published' => true]);
    
    // First request should cache the initiatives
    $this->get(route('initiatives'));
    
    expect(Cache::has('initiatives:published'))->toBeTrue();
});

test('unread contact count is cached with 5 minutes TTL', function () {
    $user = User::factory()->create();
    ContactSubmission::factory()->count(5)->create(['is_read' => false]);
    
    // Request dashboard
    $this->actingAs($user)->get(route('admin.dashboard'));
    
    expect(Cache::has('contact:unread_count'))->toBeTrue();
    expect(Cache::get('contact:unread_count'))->toBe(5);
});

test('gallery cache is invalidated when gallery item is created', function () {
    $user = User::factory()->create();
    
    // Pre-populate cache
    Cache::put('gallery:all', collect([]), 1800);
    Cache::put('gallery:category:events', collect([]), 1800);
    
    // Create a new gallery item
    $this->actingAs($user)->post(route('admin.gallery.store'), [
        'title' => 'Test Gallery Item',
        'category' => 'events',
        'image' => \Illuminate\Http\UploadedFile::fake()->image('test.jpg'),
        'is_published' => true,
    ]);
    
    // Cache should be cleared
    expect(Cache::has('gallery:all'))->toBeFalse();
    expect(Cache::has('gallery:category:events'))->toBeFalse();
});

test('gallery cache is invalidated when gallery item is updated', function () {
    $user = User::factory()->create();
    $item = GalleryItem::factory()->create();
    
    // Pre-populate cache
    Cache::put('gallery:all', collect([]), 1800);
    
    // Update the gallery item
    $this->actingAs($user)->put(route('admin.gallery.update', $item), [
        'title' => 'Updated Title',
        'category' => 'events',
        'is_published' => true,
    ]);
    
    // Cache should be cleared
    expect(Cache::has('gallery:all'))->toBeFalse();
});

test('gallery cache is invalidated when gallery item is deleted', function () {
    $user = User::factory()->create();
    $item = GalleryItem::factory()->create();
    
    // Pre-populate cache
    Cache::put('gallery:all', collect([]), 1800);
    
    // Delete the gallery item
    $this->actingAs($user)->delete(route('admin.gallery.destroy', $item));
    
    // Cache should be cleared
    expect(Cache::has('gallery:all'))->toBeFalse();
});

test('news cache is invalidated when article is created', function () {
    $user = User::factory()->create();
    
    // Pre-populate cache
    Cache::put('news:publis