<x-layouts.public>
    <div class="min-h-[60vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-2xl w-full text-center">
            <!-- Error Code -->
            <h1 class="text-8xl sm:text-9xl font-bold text-[#003366] mb-4" style="font-family: 'Playfair Display', serif;">403</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4">Access Forbidden</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto">
                You don't have permission to access this resource. This area is restricted to authorized administrators only.
            </p>

            <!-- Lock Icon -->
            <div class="mb-8">
                <svg class="w-24 h-24 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                @auth
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-[#D4A017] text-white font-semibold rounded-lg hover:bg-[#B8860B] transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Go to Homepage
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-[#D4A017] text-white font-semibold rounded-lg hover:bg-[#B8860B] transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login to Admin
                    </a>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border-2 border-[#003366] text-[#003366] font-semibold rounded-lg hover:bg-[#003366] hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Go to Homepage
                    </a>
                @endauth
            </div>

            <!-- Additional Information -->
            <div class="border-t border-gray-200 pt-8">
                <p class="text-gray-600 mb-4">If you believe you should have access to this area:</p>
                <a href="{{ route('contact') }}" 
                   class="text-[#003366] hover:text-[#007AB8] font-medium transition-colors duration-200">
                    Contact Administrator →
                </a>
            </div>
        </div>
    </div>
</x-layouts.public>
