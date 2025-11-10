<?php

namespace App\Livewire;

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscription as NewsletterSubscriptionModel;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class NewsletterSubscription extends Component
{
    public $email = '';
    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'email' => 'required|email|max:255',
    ];

    protected $messages = [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'Email address is too long.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function subscribe()
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        // Rate limiting: 3 attempts per minute per IP
        $key = 'newsletter-subscribe:' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Too many subscription attempts. Please try again in {$seconds} seconds.";
            return;
        }

        $this->validate();

        RateLimiter::hit($key, 60);

        // Check if email already exists
        $existing = NewsletterSubscriptionModel::where('email', $this->email)->first();

        if ($existing) {
            if ($existing->is_active) {
                $this->errorMessage = 'This email is already subscribed to our newsletter.';
                return;
            } else {
                // Reactivate subscription
                $existing->is_active = true;
                $existing->subscribed_at = now();
                $existing->ip_address = request()->ip();
                $existing->save();
                
                $this->successMessage = 'Welcome back! Your subscription has been reactivated.';
                $this->reset('email');
                return;
            }
        }

        // Create new subscription
        $subscription = NewsletterSubscriptionModel::create([
            'email' => $this->email,
            'ip_address' => request()->ip(),
            'subscribed_at' => now(),
        ]);

        // Send welcome email
        try {
            Mail::to($subscription->email)->send(new NewsletterWelcome($subscription));
        } catch (\Exception $e) {
            // Log the error but don't fail the subscription
            \Log::error('Failed to send newsletter welcome email: ' . $e->getMessage());
        }

        $this->successMessage = 'Thank you for subscribing! Check your email for a welcome message.';
        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.newsletter-subscription');
    }
}
