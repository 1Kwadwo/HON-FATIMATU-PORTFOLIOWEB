@props([
    'items',
    'columns' => 3
])

@php
    $gridClasses = match($columns) {
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'
    };
@endphp

<div class="grid {{ $gridClasses }} gap-6">
    @foreach($items as $item)
        <div class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
            {{-- Image --}}
            <div class="aspect-square overflow-hidden">
                <img 
                    src="{{ $item->image_url ?? $item->featured_image_url }}" 
                    alt="{{ $item->title ?? $item->caption ?? '' }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                >
            </div>
            
            {{-- Overlay with Title/Caption --}}
            @if(isset($item->title) || isset($item->caption))
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                        <h3 class="font-semibold text-lg">
                            {{ $item->title ?? $item->caption }}
                        </h3>
                        @if(isset($item->caption) && isset($item->title))
                            <p class="text-sm mt-1 opacity-90">
                                {{ $item->caption }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
