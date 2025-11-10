<?php

use App\Mail\ContactSubmissionReceived;
use App\Mail\NewsletterWelcome;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Mail;

test('contact submission sends email to admin', function () {
    Mail::fake();

    // Submit contact form
    $response = $this->post(route('contact.submit'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message.',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('success');

    // Assert email was sent
    Mail::assertSent(ContactSubmissionReceived::class, function ($mail) {
        return $mail->hasTo(config('mail.admin_email'));
    });
});

test('newsletter subscription queues welcome email', function () {
    Mail::fake();

    // Create a newsletter subscription
    $subscription = NewsletterSubscription::create([
        'email' => 'subscriber@example.com',
        'ip_address' => '127.0.0.1',
        'subscribed_at' => now(),
    ]);

    // Manually send the welcome email (simulating what the Livewire component does)
    Mail::to($subscription->email)->send(new NewsletterWelcome($subscription));

    // Assert email was queued (since it implements ShouldQueue)
    Mail::assertQueued(NewsletterWelcome::class, function ($mail) use ($subscription) {
        return $mail->hasTo($subscription->email);
    });
});

test('contact submission email has correct content', function () {
    $submission = ContactSubmission::create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'subject' => 'Partnership Inquiry',
        'message' => 'I would like to discuss a partnership opportunity.',
        'ip_address' => '192.168.1.1',
        'user_agent' => 'Mozilla/5.0',
    ]);

    $mailable = new ContactSubmissionReceived($submission);

    $mailable->assertSeeInHtml('Jane Smith');
    $mailable->assertSeeInHtml('jane@example.com');
    $mailable->assertSeeInHtml('Partnership Inquiry');
    $mailable->assertSeeInHtml('I would like to discuss a partnership opportunity.');
});

test('newsletter welcome email has correct content', function () {
    $subscription = NewsletterSubscription::create([
        'email' => 'welcome@example.com',
        'ip_address' => '127.0.0.1',
        'subscribed_at' => now(),
    ]);

    $mailable = new NewsletterWelcome($subscription);

    $mailable->assertSeeInHtml('Welcome!');
    $mailable->assertSeeInHtml('Thank you for subscribing');
    $mailable->assertSeeInHtml('What to Expect');
});

test('contact submission email has reply-to address', function () {
    $submission = ContactSubmission::create([
        'name' => 'Test User',
        'email' => 'replyto@example.com',
        'subject' => 'Test',
        'message' => 'Test message',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test',
    ]);

    $mailable = new ContactSubmissionReceived($submission);
    
    expect($mailable->envelope()->replyTo)->toHaveCount(1);
    expect($mailable->envelope()->replyTo[0]->address)->toBe('replyto@example.com');
});

test('newsletter welcome email implements should queue', function () {
    $subscription = NewsletterSubscription::create([
        'email' => 'queue@example.com',
        'ip_address' => '127.0.0.1',
        'subscribed_at' => now(),
    ]);

    $mailable = new NewsletterWelcome($subscription);
    
    expect($mailable)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
