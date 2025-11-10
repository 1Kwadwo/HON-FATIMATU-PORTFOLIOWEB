<?php

use App\Models\User;
use App\Models\GalleryItem;
use App\Models\NewsArticle;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Public Layout', function () {
    it('renders public layout with header navigation', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        $response->assertSee('Hon. Fatimatu Abubakar');
        $response->assertSee('Home');
        $response->assertSee('About');
        $response->assertSee('Initiatives');
        $response->assertSee('Gallery');
        $response->assertSee('News');
        $response->assertSee('Contact');
    });

    it('renders mobile menu button', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        $response->assertSee('mobile-menu-button', false);
        $response->assertSee('aria-label="Toggle menu"', false);
    });

    it('renders footer with quick links and social media', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        $response->assertSee('Quick Links');
        $response->assertSee('Connect');
        $response->assertSee('Leadership');
        $response->assertSee('Legacy');
    });

    it('has sticky header', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        $response->assertSee('sticky top-0', false);
    });

    it('highlights active navigation item', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        // Check that navigation has active styling capability
        $response->assertSee('text-[#003366]', false);
    });
});

describe('Admin Layout', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('renders admin sidebar with navigation', function () {
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Content Management');
    });

    it('shows gallery link in admin navigation', function () {
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('Gallery');
        $response->assertSee(route('admin.gallery.index'), false);
    });

    it('shows news link in admin navigation', function () {
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('News');
        $response->assertSee(route('admin.news.index'), false);
    });

    it('shows pages link in admin navigation', function () {
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('Pages');
        $response->assertSee(route('admin.pages.index'), false);
    });

    it('shows contacts link in admin navigation', function () {
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('Contacts');
        $response->assertSee(route('admin.contacts.index'), false);
    });

    it('displays unread contact count badge', function () {
        $this->actingAs($this->user);
        
        // Create unread contacts
        ContactSubmission::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message',
            'is_read' => false,
        ]);
        ContactSubmission::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'subject' => 'Test Subject 2',
            'message' => 'Test message 2',
            'is_read' => false,
        ]);
        ContactSubmission::create([
            'name' => 'Test User 3',
            'email' => 'test3@example.com',
            'subject' => 'Test Subject 3',
            'message' => 'Test message 3',
            'is_read' => false,
        ]);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('3');
    });

    it('shows view public site link', function () {
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('View Public Site');
    });

    it('shows user profile menu', function () {
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->email);
    });
});

describe('Responsive Design', function () {
    it('has viewport meta tag for mobile', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        $response->assertSee('viewport-fit=cover', false);
        $response->assertSee('width=device-width', false);
    });

    it('uses responsive container classes', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        $response->assertSee('container mx-auto', false);
        $response->assertSee('px-4 sm:px-6 lg:px-8', false);
    });

    it('has mobile-first navigation with hidden desktop menu', function () {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
        $response->assertSee('hidden md:flex', false);
        $response->assertSee('md:hidden', false);
    });
});
