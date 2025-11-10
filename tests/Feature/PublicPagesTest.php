<?php

use App\Models\Page;

test('about page displays when page exists', function () {
    // Create an about page
    $page = Page::create([
        'slug' => 'about',
        'title' => 'About Hon. Fatimatu Abubakar',
        'content' => '<p>This is the about page content.</p>',
        'meta_title' => 'About',
        'meta_description' => 'Learn about Hon. Fatimatu Abubakar',
    ]);

    $response = $this->get(route('about'));

    $response->assertStatus(200);
    $response->assertSee('About Hon. Fatimatu Abubakar');
    $response->assertSee('Career Timeline');
});

test('about page returns 404 when page does not exist', function () {
    $response = $this->get(route('about'));

    $response->assertStatus(404);
});

test('news index page displays published articles', function () {
    $user = \App\Models\User::factory()->create();
    
    // Create published articles
    $publishedArticle = \App\Models\NewsArticle::create([
        'title' => 'Published Article',
        'slug' => 'published-article',
        'excerpt' => 'This is a published article excerpt',
        'content' => 'This is the full content of the published article.',
        'author_id' => $user->id,
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    // Create draft article (should not be displayed)
    \App\Models\NewsArticle::create([
        'title' => 'Draft Article',
        'slug' => 'draft-article',
        'excerpt' => 'This is a draft article excerpt',
        'content' => 'This is the full content of the draft article.',
        'author_id' => $user->id,
        'status' => 'draft',
        'published_at' => null,
    ]);

    $response = $this->get(route('news'));

    $response->assertStatus(200);
    $response->assertSee('Published Article');
    $response->assertDontSee('Draft Article');
});

test('news detail page displays article and increments view count', function () {
    $user = \App\Models\User::factory()->create();
    
    $article = \App\Models\NewsArticle::create([
        'title' => 'Test Article',
        'slug' => 'test-article',
        'excerpt' => 'This is a test article excerpt',
        'content' => 'This is the full content of the test article.',
        'author_id' => $user->id,
        'status' => 'published',
        'published_at' => now()->subDay(),
        'view_count' => 0,
    ]);

    $response = $this->get(route('news.show', $article->slug));

    $response->assertStatus(200);
    $response->assertSee('Test Article');
    $response->assertSee('This is the full content of the test article.');
    
    // Check that view count was incremented
    $article->refresh();
    expect($article->view_count)->toBe(1);
});

test('news detail page returns 404 for draft articles', function () {
    $user = \App\Models\User::factory()->create();
    
    $article = \App\Models\NewsArticle::create([
        'title' => 'Draft Article',
        'slug' => 'draft-article',
        'excerpt' => 'This is a draft article excerpt',
        'content' => 'This is the full content of the draft article.',
        'author_id' => $user->id,
        'status' => 'draft',
        'published_at' => null,
    ]);

    $response = $this->get(route('news.show', $article->slug));

    $response->assertStatus(404);
});

test('news detail page displays related articles', function () {
    $user = \App\Models\User::factory()->create();
    
    // Create main article
    $mainArticle = \App\Models\NewsArticle::create([
        'title' => 'Main Article',
        'slug' => 'main-article',
        'excerpt' => 'Main article excerpt',
        'content' => 'Main article content.',
        'author_id' => $user->id,
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    // Create related articles
    $relatedArticle = \App\Models\NewsArticle::create([
        'title' => 'Related Article',
        'slug' => 'related-article',
        'excerpt' => 'Related article excerpt',
        'content' => 'Related article content.',
        'author_id' => $user->id,
        'status' => 'published',
        'published_at' => now()->subDays(2),
    ]);

    $response = $this->get(route('news.show', $mainArticle->slug));

    $response->assertStatus(200);
    $response->assertSee('Related Articles');
    $response->assertSee('Related Article');
});

test('news article page includes social sharing buttons', function () {
    $user = \App\Models\User::factory()->create();
    
    $article = \App\Models\NewsArticle::create([
        'title' => 'Test Article for Sharing',
        'slug' => 'test-article-sharing',
        'excerpt' => 'Test article excerpt',
        'content' => 'Test article content.',
        'author_id' => $user->id,
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->get(route('news.show', $article->slug));

    $response->assertStatus(200);
    // Check for Facebook share button
    $response->assertSee('facebook.com/sharer/sharer.php', false);
    // Check for Twitter share button
    $response->assertSee('twitter.com/intent/tweet', false);
    // Check for LinkedIn share button
    $response->assertSee('linkedin.com/shareArticle', false);
});

test('news article page includes Open Graph meta tags', function () {
    $user = \App\Models\User::factory()->create();
    
    $article = \App\Models\NewsArticle::create([
        'title' => 'Test Article with Meta Tags',
        'slug' => 'test-article-meta',
        'excerpt' => 'Test article excerpt for meta tags',
        'content' => 'Test article content.',
        'author_id' => $user->id,
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->get(route('news.show', $article->slug));

    $response->assertStatus(200);
    // Check for Open Graph tags
    $response->assertSee('og:title', false);
    $response->assertSee('og:description', false);
    $response->assertSee('og:type', false);
    $response->assertSee('article', false);
    // Check for Twitter Card tags
    $response->assertSee('twitter:card', false);
    $response->assertSee('twitter:title', false);
});

test('initiative detail page includes social sharing buttons', function () {
    $initiative = \App\Models\Initiative::create([
        'title' => 'Test Initiative for Sharing',
        'slug' => 'test-initiative-sharing',
        'short_description' => 'Test initiative short description',
        'full_description' => 'Test initiative full description.',
        'status' => 'active',
        'start_date' => now()->subMonth(),
    ]);

    $response = $this->get(route('initiatives.show', $initiative->slug));

    $response->assertStatus(200);
    // Check for Facebook share button
    $response->assertSee('facebook.com/sharer/sharer.php', false);
    // Check for Twitter share button
    $response->assertSee('twitter.com/intent/tweet', false);
    // Check for LinkedIn share button
    $response->assertSee('linkedin.com/shareArticle', false);
});

test('initiative detail page includes Open Graph meta tags', function () {
    $initiative = \App\Models\Initiative::create([
        'title' => 'Test Initiative with Meta Tags',
        'slug' => 'test-initiative-meta',
        'short_description' => 'Test initiative description for meta tags',
        'full_description' => 'Test initiative full description.',
        'status' => 'active',
        'start_date' => now()->subMonth(),
    ]);

    $response = $this->get(route('initiatives.show', $initiative->slug));

    $response->assertStatus(200);
    // Check for Open Graph tags
    $response->assertSee('og:title', false);
    $response->assertSee('og:description', false);
    $response->assertSee('og:type', false);
    // Check for Twitter Card tags
    $response->assertSee('twitter:card', false);
});
