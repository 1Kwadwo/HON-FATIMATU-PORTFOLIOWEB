<?php

use App\Models\ContactSubmission;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Mail;

new class extends Component {
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $successMessage = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $validated = $this->validate();

        // Store submission in database with IP address and user agent
        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send email notification to admin
        try {
            $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'admin@example.com'));
            Mail::to($adminEmail)->send(new \App\Mail\ContactSubmissionReceived($submission));
        } catch (\Exception $e) {
            // Log the error but don't fail the submission
            \Log::error('Failed to send contact notification email: ' . $e->getMessage());
        }

        // Set success message
        $this->successMessage = 'Thank you for your message! We will get back to you soon.';

        // Reset form
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->subject = '';
        $this->message = '';
        $this->resetValidation();
    }
}; ?>

<div>
    @if($successMessage)
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p>{{ $successMessage }}</p>
            </div>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Name <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                wire:model.blur="name"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#003366] focus:border-transparent @error('name') border-red-500 @enderror"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input 
                type="email" 
                id="email" 
                wire:model.blur="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#003366] focus:border-transparent @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Subject Field -->
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                Subject <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="subject" 
                wire:model.blur="subject"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#003366] focus:border-transparent @error('subject') border-red-500 @enderror"
            >
            @error('subject')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message Field -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                Message <span class="text-red-500">*</span>
            </label>
            <textarea 
                id="message" 
                wire:model.blur="message"
                rows="6"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#003366] focus:border-transparent @error('message') border-red-500 @enderror"
            ></textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button 
                type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-[#D4A017] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#B8860B] transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove>Send Message</span>
                <span wire:loading>Sending...</span>
            </button>
        </div>
    </form>
</div>
