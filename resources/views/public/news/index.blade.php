<x-layouts.public>
    <x-slot name="title">News & Updates - Hon. Fatimatu Abubakar</x-slot>

    <!-- Page Header -->
    <div class="bg-[#003366] text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" style="font-family: 'Playfair Display', serif;">News & Updates</h1>
            <p class="text-xl text-gray-200">Stay informed about the latest activities and announcements</p>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="container mx-auto px-4 py-12 fade-in">
        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($articles as $article)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 fade-in">
                        @if($article->featured_image_url)
                            <a href="{{ route('news.show', $article->slug) }}">
                                <img src="{{ $article->featured_image_url }}" 
                                     alt="{{ $article->title }}" 
                                     class="w-full h-48 object-cover"
                                     loading="lazy">
                            </a>
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <time datetime="{{ $article->published_at->format('Y-m-d') }}">
                                    {{ $article->published_at->format('F j, Y') }}
                                </time>
                                <span class="mx-2">•</span>
                                <span>{{ $article->reading_time }} min read</span>
                            </div>
                            
                            <h2 class="text-xl font-bold mb-3 text-[#003366]" style="font-family: 'Playfair Display', serif;">
                                <a href="{{ route('news.show', $article->slug) }}" class="hover:text-[#D4A017] transition">
                                    {{ $article->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $article->excerpt }}
                            </p>
                            
                            <a href="{{ route('news.show', $article->slug) }}" 
                               class="inline-flex items-center text-[#D4A017] font-semibold hover:text-[#003366] transition">
                                Read More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">No Articles Yet</h3>
                <p class="text-gray-500">Check back soon for the latest news and updates.</p>
            </div>
        @endif
    </div>

    <!-- Newsletter Subscription Section -->
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4 text-[#003366]" style="font-family: 'Playfair Display', serif;">
                    Stay Updated
                </h2>
                <p class="text-gray-600 mb-6">
                    Subscribe to our newsletter to receive the latest news and updates directly in your inbox.
                </p>
                
                <div class="max-w-md mx-auto">
                    @livewire('newsletter-subscription')
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
