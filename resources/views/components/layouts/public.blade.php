<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? $title ?? 'Hon. Fatimatu Abubakar' }}</title>

    @include('partials.seo-meta')
    
    @stack('meta')

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|poppins:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-bold text-[#003366] flex-shrink-0" style="font-family: 'Playfair Display', serif;">
                    Hon. Fatimatu Abubakar
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-6 lg:gap-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-[#003366] transition-colors duration-200 font-medium {{ request()->routeIs('home') ? 'text-[#003366]' : '' }}">Home</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-[#003366] transition-colors duration-200 font-medium {{ request()->routeIs('about') ? 'text-[#003366]' : '' }}">About</a>
                    <a href="{{ route('initiatives') }}" class="text-gray-700 hover:text-[#003366] transition-colors duration-200 font-medium {{ request()->routeIs('initiatives*') ? 'text-[#003366]' : '' }}">Initiatives</a>
                    <a href="{{ route('gallery') }}" class="text-gray-700 hover:text-[#003366] transition-colors duration-200 font-medium {{ request()->routeIs('gallery') ? 'text-[#003366]' : '' }}">Gallery</a>
                    <a href="{{ route('news') }}" class="text-gray-700 hover:text-[#003366] transition-colors duration-200 font-medium {{ request()->routeIs('news*') ? 'text-[#003366]' : '' }}">News</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-[#003366] transition-colors duration-200 font-medium {{ request()->routeIs('contact') ? 'text-[#003366]' : '' }}">Contact</a>
                </div>

                <!-- Mobile Menu Button - Minimum 44x44px touch target -->
                <button 
                    id="mobile-menu-button" 
                    class="md:hidden text-gray-700 p-2 -mr-2 hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#003366] focus:ring-offset-2"
                    aria-label="Toggle menu"
                    aria-expanded="false"
                >
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation - Minimum 44x44px touch targets -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-2 border-t border-gray-200 pt-4">
                <div class="flex flex-col space-y-1">
                    <a href="{{ route('home') }}" class="block py-3 px-4 text-base text-gray-700 hover:text-[#003366] hover:bg-gray-50 rounded-lg transition-colors duration-200 {{ request()->routeIs('home') ? 'text-[#003366] bg-gray-50' : '' }}">Home</a>
                    <a href="{{ route('about') }}" class="block py-3 px-4 text-base text-gray-700 hover:text-[#003366] hover:bg-gray-50 rounded-lg transition-colors duration-200 {{ request()->routeIs('about') ? 'text-[#003366] bg-gray-50' : '' }}">About</a>
                    <a href="{{ route('initiatives') }}" class="block py-3 px-4 text-base text-gray-700 hover:text-[#003366] hover:bg-gray-50 rounded-lg transition-colors duration-200 {{ request()->routeIs('initiatives*') ? 'text-[#003366] bg-gray-50' : '' }}">Initiatives</a>
                    <a href="{{ route('gallery') }}" class="block py-3 px-4 text-base text-gray-700 hover:text-[#003366] hover:bg-gray-50 rounded-lg transition-colors duration-200 {{ request()->routeIs('gallery') ? 'text-[#003366] bg-gray-50' : '' }}">Gallery</a>
                    <a href="{{ route('news') }}" class="block py-3 px-4 text-base text-gray-700 hover:text-[#003366] hover:bg-gray-50 rounded-lg transition-colors duration-200 {{ request()->routeIs('news*') ? 'text-[#003366] bg-gray-50' : '' }}">News</a>
                    <a href="{{ route('contact') }}" class="block py-3 px-4 text-base text-gray-700 hover:text-[#003366] hover:bg-gray-50 rounded-lg transition-colors duration-200 {{ request()->routeIs('contact') ? 'text-[#003366] bg-gray-50' : '' }}">Contact</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-[#003366] text-white py-12 mt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <!-- Brand -->
                <div>
                    @php
                        $footerImage = App\Models\SiteSetting::get('footer_image', '');
                    @endphp
                    @if($footerImage)
                        <div class="mb-4">
                            <img src="{{ Storage::url($footerImage) }}" alt="Hon. Fatimatu Abubakar" class="h-16 sm:h-20 w-auto object-contain">
                        </div>
                    @else
                        <h3 class="text-xl sm:text-2xl font-bold mb-4" style="font-family: 'Playfair Display', serif;">Hon. Fatimatu Abubakar</h3>
                    @endif
                    <p class="text-gray-300 text-sm sm:text-base">Leadership & Legacy</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-4 text-base sm:text-lg">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm sm:text-base inline-block py-1">About</a></li>
                        <li><a href="{{ route('initiatives') }}" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm sm:text-base inline-block py-1">Initiatives</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm sm:text-base inline-block py-1">Gallery</a></li>
                        <li><a href="{{ route('news') }}" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm sm:text-base inline-block py-1">News</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm sm:text-base inline-block py-1">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Social Media - Minimum 44x44px touch targets -->
                <div>
                    <h4 class="font-semibold mb-4 text-base sm:text-lg">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="{{ $socialMedia['facebook'] ?: '#' }}" @if($socialMedia['facebook']) target="_blank" rel="noopener noreferrer" @endif class="text-gray-300 hover:text-white transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg" aria-label="Facebook">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="{{ $socialMedia['twitter'] ?: '#' }}" @if($socialMedia['twitter']) target="_blank" rel="noopener noreferrer" @endif class="text-gray-300 hover:text-white transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg" aria-label="Twitter">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="{{ $socialMedia['instagram'] ?: '#' }}" @if($socialMedia['instagram']) target="_blank" rel="noopener noreferrer" @endif class="text-gray-300 hover:text-white transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg" aria-label="Instagram">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                        </a>
                        <a href="{{ $socialMedia['linkedin'] ?: '#' }}" @if($socialMedia['linkedin']) target="_blank" rel="noopener noreferrer" @endif class="text-gray-300 hover:text-white transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg" aria-label="LinkedIn">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="font-semibold mb-4 text-base sm:text-lg">Newsletter</h4>
                    <p class="text-gray-300 text-sm mb-4">Get the latest updates delivered to your inbox.</p>
                    @livewire('newsletter-subscription')
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Hon. Fatimatu Abubakar. All rights reserved.</p>
            </div>
        </div>
        
        @include('partials.schema-organization')
    </footer>

    <script>
        // Mobile menu toggle with improved accessibility
        (function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            if (menuButton && mobileMenu) {
                menuButton.addEventListener('click', function() {
                    const isExpanded = mobileMenu.classList.contains('hidden');
                    
                    // Toggle menu visibility
                    mobileMenu.classList.toggle('hidden');
                    
                    // Toggle icons
                    menuIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                    
                    // Update aria-expanded
                    menuButton.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!menuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        if (!mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                            menuIcon.classList.remove('hidden');
                            closeIcon.classList.add('hidden');
                            menuButton.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                
                // Close menu on escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                        menuButton.setAttribute('aria-expanded', 'false');
                        menuButton.focus();
                    }
                });
            }
        })();
    </script>
</body>
</html>
