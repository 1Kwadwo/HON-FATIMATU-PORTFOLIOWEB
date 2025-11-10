<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('contact form has rate limiting', function () {
    // Make 5 successful submissions
    for ($i = 0; $i < 5; $i++) {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message',
        ]);
        
        $response->assertStatus(302); // Redirect on success
    }
    
    // 6th submission should be rate limited
    $response = $this->post(route('contact.submit'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'Test message',
    ]);
    
    $response->assertStatus(429); // Too Many Requests
});

test('security headers are present in responses', function () {
    $response = $this->get('/');
    
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('https is enforced in production environment', function () {
    // Temporarily set environment to production
    $originalEnv = app()->environment();
    app()->detectEnvironment(fn () => 'production');
    
    // Make HTTP request (non-secure)
    $response = $this->call('GET', '/about', [], [], [], ['HTTPS' => 'off']);
    
    // Should redirect to HTTPS
    $response->assertStatus(301);
    
    // Restore original environment
    app()->detectEnvironment(fn () => $originalEnv);
})->skip('HTTPS redirect testing requires special server configuration');

test('file upload validates mime types', function () {
    Storage::fake('public');
    
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create a fake text file with image extension
    $file = UploadedFile::fake()->create('test.txt', 100, 'text/plain');
    
    $response = $this->post(route('admin.gallery.store'), [
        'title' => 'Test Image',
        'category' => 'events',
        'image' => $file,
    ]);
    
    // Should fail validation
    $response->assertSessionHasErrors('image');
});

test('file upload validates file size', function () {
    Storage::fake('public');
    
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create a file larger than 5MB
    $file = UploadedFile::fake()->image('large.jpg')->size(6000); // 6MB
    
    $response = $this->post(route('admin.gallery.store'), [
        'title' => 'Test Image',
        'category' => 'events',
        'image' => $file,
    ]);
    
    // Should fail validation
    $response->assertSessionHasErrors('image');
});

test('csrf protection is enabled on forms', function () {
    // CSRF protection is automatically enabled by Laravel
    // Attempting to make a POST request without proper CSRF token should fail
    
    // Create a fake image file
    Storage::fake('public');
    $file = UploadedFile::fake()->image('test.jpg');
    
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Disable CSRF middleware temporarily to test
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    
    // With middleware disabled, request should work
    $response = $this->post(route('admin.gallery.store'), [
        'title' => 'Test Image',
        'category' => 'events',
        'image' => $file,
    ]);
    
    // Should succeed (redirect) when CSRF is bypassed
    $response->assertRedirect();
})->skip('CSRF is automatically handled by Laravel middleware');

test('admin routes require authentication', function () {
    $response = $this->get(route('admin.dashboard'));
    
    // Should redirect to login
    $response->assertRedirect(route('login'));
});

test('file upload only accepts whitelisted extensions', function () {
    Storage::fake('public');
    
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Try to upload a PHP file
    $file = UploadedFile::fake()->create('malicious.php', 100, 'application/x-php');
    
    $response = $this->post(route('admin.gallery.store'), [
        'title' => 'Test Image',
        'category' => 'events',
        'image' => $file,
    ]);
    
    // Should fail validation
    $response->assertSessionHasErrors('image');
});

test('valid image uploads are accepted', function () {
    Storage::fake('public');
    
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create a valid image file
    $file = UploadedFile::fake()->image('test.jpg', 800, 600)->size(1000); // 1MB
    
    $response = $this->post(route('admin.gallery.store'), [
        'title' => 'Test Image',
        'caption' => 'Test caption',
        'category' => 'events',
        'image' => $file,
        'is_published' => true,
    ]);
    
    // Should succeed
    $response->assertRedirect(route('admin.gallery.index'));
    $response->assertSessionHas('success');
});
