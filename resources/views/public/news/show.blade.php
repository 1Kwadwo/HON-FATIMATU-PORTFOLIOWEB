<x-layouts.public>
    <x-slot name="title">{{ $article->meta_title ?? $article->title }} - Hon. Fatimatu Abubakar</x-slot>
    
    @push('meta')
        <meta property="og:title" content="{{ $article->meta_title ?? $article->title }}" />
        <meta property="og:description" content="{{ $article->meta_description ?? $article->excerpt }}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{ route('news.show', $article->slug) }}" />
        @if($article->featured_image_url)
        <meta property="og:image" content="{{ $article->featured_image_url }}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        @endif
        <meta property="article:published_time" content="{{ $article->published_at->toIso8601String() }}" />
        @if($article->updated_at != $article->created_at)
        <meta property="article:modified_time" content="{{ $article->updated_at->toIso8601String() }}" />
        @endif
        @if($article->author)
        <meta property="article:author" content="{{ $article->author->name }}" />
        @endif
        
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="{{ $article->meta_title ?? $article->title }}" />
        <meta name="twitter:description" content="{{ $article->meta_description ?? $article->excerpt }}" />
        @if($article->featured_image_url)
        <meta name="twitter:image" content="{{ $article->featured_image_url }}" />
        @endif
    @endpush

    <!-- Breadcrumb Navigation -->
    <div class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex items-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-[#003366] transition">Home</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('news') }}" class="hover:text-[#003366] transition">News</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900">{{ Str::limit($article->title, 50) }}</span>
            </nav>
        </div>
    </div>

    <!-- Article Content -->
    <article class="container mx-auto px-4 py-12 fade-in">
        <div class="max-w-4xl mx-auto">
            <!-- Article Header -->
            <header class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-[#003366]" style="font-family: 'Playfair Display', serif;">
                    {{ $article->title }}
                </h1>
                
                <div class="flex flex-wrap items-center text-gray-600 mb-6">
                    <div class="flex items-center mr-6 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <time datetime="{{ $article->published_at->format('Y-m-d') }}">
                            {{ $article->published_at->format('F j, Y') }}
                        </time>
                    </div>
                    
                    <div class="flex items-center mr-6 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $article->reading_time }} min read</span>
                    </div>
                    
                    @if($article->author)
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>By {{ $article->author->name }}</span>
                        </div>
                    @endif
                </div>

                <!-- Social Sharing Buttons -->
                <x-social-share 
                    :url="route('news.show', $article->slug)"
                    :title="$article->title"
                    :description="$article->excerpt"
                    hashtags="Leadership,Community"
                    class="py-4 border-y border-gray-200"
                />
            </header>

            <!-- Featured Image -->
            @if($article->featured_image_url)
                <div class="mb-8">
                    <img src="{{ $article->featured_image_url }}" 
                         alt="{{ $article->title }}" 
                         class="w-full rounded-lg shadow-lg"
                         loading="eager">
                </div>
            @endif

            <!-- Article Body -->
            <div class="prose prose-lg max-w-none mb-12 article-content">
                <div class="text-gray-700 leading-relaxed">
                    {!! $article->content !!}
                </div>
            </div>

            <style>
                /* Style links in article content */
                .article-content a {
                    color: #2563eb;
                    text-decoration: underline;
                    font-weight: 500;
                    transition: color 0.2s ease;
                }
                
                .article-content a:hover {
                    color: #1d4ed8;
                    text-decoration: underline;
                }
                
                .article-content a:visited {
                    color: #7c3aed;
                }
            </style>

            <!-- Author Information -->
            @if($article->author)
                <div class="bg-gray-50 rounded-lg p-6 mb-12">
                    <h3 class="text-xl font-bold mb-2 text-[#003366]" style="font-family: 'Playfair Display', serif;">
                        About the Author
                    </h3>
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full bg-[#003366] text-white flex items-center justify-center text-2xl font-bold mr-4">
                            {{ strtoupper(substr($article->author->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-lg">{{ $article->author->name }}</p>
                            <p class="text-gray-600">{{ $article->author->email }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </article>

    <!-- Related Articles Section -->
    @if($relatedArticles->count() > 0)
        <div class="bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8 text-[#003366] text-center" style="font-family: 'Playfair Display', serif;">
                    Related Articles
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach($relatedArticles as $related)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            @if($related->featured_image_url)
                                <a href="{{ route('news.show', $related->slug) }}">
                                    <img src="{{ $related->featured_image_url }}" 
                                         alt="{{ $related->title }}" 
                                         class="w-full h-40 object-cover"
                                         loading="lazy">
                                </a>
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <time class="text-sm text-gray-500" datetime="{{ $related->published_at->format('Y-m-d') }}">
                                    {{ $related->published_at->format('F j, Y') }}
                                </time>
                                
                                <h3 class="text-lg font-bold mt-2 mb-2 text-[#003366]" style="font-family: 'Playfair Display', serif;">
                                    <a href="{{ route('news.show', $related->slug) }}" class="hover:text-[#D4A017] transition">
                                        {{ $related->title }}
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 text-sm line-clamp-2">
                                    {{ $related->excerpt }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @include('partials.schema-article')
</x-layouts.public>
