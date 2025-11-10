<x-layouts.public>
    <x-slot name="title">Initiatives | Hon. Fatimatu Abubakar</x-slot>

    <!-- Hero Section -->
    <section class="bg-[#003366] text-white py-20">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" style="font-family: 'Playfair Display', serif;">
                Our Initiatives
            </h1>
            <p class="text-xl text-gray-200 max-w-3xl">
                Discover the programs and projects driving positive change in our communities
            </p>
        </div>
    </section>

    <!-- Initiatives Grid -->
    <section class="py-16 fade-in">
        <div class="container mx-auto px-4">
            @if($initiatives->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">No initiatives available at this time.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($initiatives as $initiative)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 fade-in">
                            <!-- Featured Image -->
                            @if($initiative->featured_image_url)
                                <a href="{{ route('initiatives.show', $initiative->slug) }}">
                                    <img 
                                        src="{{ $initiative->featured_image_url }}" 
                                        alt="{{ $initiative->title }}"
                                        class="w-full h-64 object-cover"
                                        loading="lazy"
                                    >
                                </a>
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Card Content -->
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-3 text-[#003366]" style="font-family: 'Playfair Display', serif;">
                                    <a href="{{ route('initiatives.show', $initiative->slug) }}" class="hover:text-[#D4A017] transition">
                                        {{ $initiative->title }}
                                    </a>
                                </h2>

                                <p class="text-gray-700 mb-4 line-clamp-3">
                                    {{ $initiative->short_description }}
                                </p>

                                <!-- Impact Statistics -->
                                @if($initiative->impact_stats && count($initiative->impact_stats) > 0)
                                    <div class="border-t border-gray-200 pt-4 mb-4">
                                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Key Impact</h3>
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach(array_slice($initiative->impact_stats, 0, 2) as $stat)
                                                <div class="text-center">
                                                    <div class="text-2xl font-bold text-[#D4A017]">
                                                        {{ $stat['value'] ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-xs text-gray-600">
                                                        {{ $stat['label'] ?? '' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Read More Link -->
                                <a 
                                    href="{{ route('initiatives.show', $initiative->slug) }}" 
                                    class="inline-flex items-center text-[#003366] font-semibold hover:text-[#D4A017] transition"
                                >
                                    Learn More
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
