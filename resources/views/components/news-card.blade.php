@props([
    'article',
    'featured' => false
])

<article class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg {{ $featured ? 'md:col-span-2 md:row-span-2' : '' }}">
    {{-- Featured Image --}}
    @if($article->featured_image_url)
        <div class="{{ $featured ? 'aspect-[21/9]' : 'aspect-[16/9]' }} overflow-hidden">
            <img 
                src="{{ $article->featured_image_url }}" 
                alt="{{ $article->title }}"
                class="w-full h-full object-cover"
            >
        </div>
    @endif
    
    {{-- Content --}}
    <div class="{{ $featured ? 'p-8' : 'p-6' }}">
        {{-- Publication Date --}}
        <time class="text-sm text-gray-500 mb-2 block">
            {{ $article->published_at->format('F j, Y') }}
        </time>
        
        {{-- Title --}}
        <h3 class="font-['Playfair_Display'] {{ $featured ? 'text-3xl' : 'text-xl' }} font-bold text-[#003366] mb-3">
            {{ $article->title }}
        </h3>
        
        {{-- Excerpt --}}
        <p class="text-gray-700 mb-4 {{ $featured ? 'text-lg line-clamp-4' : 'line-clamp-3' }}">
            {{ $article->excerpt }}
        </p>
        
        {{-- Read More Link --}}
        <a 
            href="{{ route('news.show', $article->slug) }}" 
            class="inline-flex items-center text-[#007AB8] hover:text-[#003366] font-semibold transition-colors"
        >
            Read More 
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</article>
