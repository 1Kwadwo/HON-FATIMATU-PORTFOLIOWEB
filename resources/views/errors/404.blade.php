<x-layouts.public>
    <div class="min-h-[60vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl w-full text-center">
            <!-- Error Code -->
            <h1 class="text-8xl sm:text-9xl font-bold text-[#003366] mb-4" style="font-family: 'Playfair Display', serif;">404</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Sorry, we couldn't find the page you're looking for. The page may have been moved or deleted.
            </p>

            <!-- Navigation Links -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-[#D4A017] text-white font-semibold rounded-lg hover:bg-[#B8860B] transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Go to Homepage
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-[#003366] text-[#003366] font-semibold rounded-lg hover:bg-[#003366] hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Us
                </a>
            </div>

            <!-- Helpful Links -->
            <div class="mb-12">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">You might be interested in:</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('about') }}" class="text-[#003366] hover:text-[#007AB8] font-medium transition-colors duration-200">About</a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('initiatives') }}" class="text-[#003366] hover:text-[#007AB8] font-medium transition-colors duration-200">Initiatives</a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('gallery') }}" class="text-[#003366] hover:text-[#007AB8] font-medium transition-colors duration-200">Gallery</a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('news') }}" class="text-[#003366] hover:text-[#007AB8] font-medium transition-colors duration-200">News</a>
                </div>
            </div>

            <!-- Recent Articles -->
            @php
                $recentArticles = \App\Models\NewsArticle::published()->recent()->take(3)->get();
            @endphp

            @if($recentArticles->count() > 0)
                <div class="border-t border-gray-200 pt-12">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-6" style="font-family: 'Playfair Display', serif;">Recent News</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($recentArticles as $article)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                @if($article->featured_image_url)
                                    <img src="{{ $article->featured_image_url }}" 
                                         alt="{{ $article->title }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $article->title }}</h4>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $article->excerpt }}</p>
                                    <a href="{{ route('news.show', $article->slug) }}" 
                                       class="text-[#003366] hover:text-[#007AB8] font-medium text-sm transition-colors duration-200">
                                        Read More →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.public>
