@props([
    'initiative',
    'showStats' => true
])

<div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
    {{-- Featured Image --}}
    @if($initiative->featured_image_url)
        <div class="aspect-[16/9] overflow-hidden">
            <img 
                src="{{ $initiative->featured_image_url }}" 
                alt="{{ $initiative->title }}"
                class="w-full h-full object-cover"
            >
        </div>
    @endif
    
    {{-- Content --}}
    <div class="p-6">
        <h3 class="font-['Playfair_Display'] text-2xl font-bold text-[#003366] mb-3">
            {{ $initiative->title }}
        </h3>
        
        <p class="text-gray-700 mb-4 line-clamp-3">
            {{ $initiative->short_description }}
        </p>
        
        {{-- Impact Statistics --}}
        @if($showStats && $initiative->impact_stats && count($initiative->impact_stats) > 0)
            <div class="border-t border-gray-200 pt-4 mt-4">
                <div class="grid grid-cols-2 gap-4">
                    @foreach($initiative->impact_stats as $stat)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#D4A017]">
                                {{ $stat['value'] ?? '' }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $stat['label'] ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        {{-- Link to Detail Page --}}
        <a 
            href="{{ route('initiatives.show', $initiative->slug) }}" 
            class="inline-block mt-4 text-[#007AB8] hover:text-[#003366] font-semibold transition-colors"
        >
            Learn More →
        </a>
    </div>
</div>
