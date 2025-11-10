<x-layouts.public>
    <div class="min-h-[60vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-2xl w-full text-center">
            <!-- Error Code -->
            <h1 class="text-8xl sm:text-9xl font-bold text-[#003366] mb-4" style="font-family: 'Playfair Display', serif;">500</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4">Server Error</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto">
                We're sorry, but something went wrong on our end. Our team has been notified and is working to fix the issue.
            </p>

            <!-- Error Icon -->
            <div class="mb-8">
                <svg class="w-24 h-24 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-[#D4A017] text-white font-semibold rounded-lg hover:bg-[#B8860B] transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Go to Homepage
                </a>
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center justify-center px-6 py-3 border-2 border-[#003366] text-[#003366] font-semibold rounded-lg hover:bg-[#003366] hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Try Again
                </button>
            </div>

            <!-- Support Information -->
            <div class="border-t border-gray-200 pt-8">
                <p class="text-gray-600 mb-4">If the problem persists, please contact us:</p>
                <a href="{{ route('contact') }}" 
                   class="text-[#003366] hover:text-[#007AB8] font-medium transition-colors duration-200">
                    Contact Support →
                </a>
            </div>
        </div>
    </div>
</x-layouts.public>
