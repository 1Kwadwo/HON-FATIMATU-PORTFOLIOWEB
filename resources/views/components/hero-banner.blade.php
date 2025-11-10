@props([
    'image',
    'title',
    'subtitle' => null,
    'height' => 'h-screen'
])

<div class="relative {{ $height }} md:h-screen overflow-hidden">
    {{-- Background Image --}}
    <img 
        src="{{ $image }}" 
        alt="{{ $title }}"
        class="absolute inset-0 w-full h-full object-cover"
    >
    
    {{-- Overlay Gradient for Text Readability --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
    
    {{-- Content --}}
    <div class="relative h-full flex items-center justify-center px-4">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="font-['Playfair_Display'] text-4xl md:text-6xl lg:text-7xl font-bold mb-4 leading-tight">
                {{ $title }}
            </h1>
            
            @if($subtitle)
                <p class="text-lg md:text-xl lg:text-2xl font-light max-w-2xl mx-auto">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    </div>
</div>
