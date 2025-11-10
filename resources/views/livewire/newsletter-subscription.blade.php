<div class="newsletter-subscription">
    <form wire:submit.prevent="subscribe" class="space-y-3">
        <div class="flex flex-col sm:flex-row gap-2">
            <div class="flex-1">
                <label for="newsletter-email" class="sr-only">Email address</label>
                <input 
                    type="email" 
                    id="newsletter-email"
                    wire:model.live="email"
                    placeholder="Your email address"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4A017] focus:border-transparent transition-colors text-gray-900"
                    :class="{ 'border-red-500': @error('email') true @enderror }"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <button 
                type="submit"
                class="px-6 py-3 bg-[#D4A017] text-white font-semibold rounded-lg hover:bg-[#D4A017]/90 transition-colors whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Subscribe</span>
                <span wire:loading>Subscribing...</span>
            </button>
        </div>

        @if($successMessage)
            <div class="p-3 bg-green-900/30 border border-green-500/50 rounded-lg">
                <p class="text-sm text-green-200">{{ $successMessage }}</p>
            </div>
        @endif

        @if($errorMessage)
            <div class="p-3 bg-red-900/30 border border-red-500/50 rounded-lg">
                <p class="text-sm text-red-200">{{ $errorMessage }}</p>
            </div>
        @endif
    </form>
</div>
