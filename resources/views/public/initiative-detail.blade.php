<x-layouts.public>
    <x-slot name="title">{{ $initiative->title }} | Hon. Fatimatu Abubakar</x-slot>
    
    @push('meta')
        <meta property="og:title" content="{{ $initiative->title }}" />
        <meta property="og:description" content="{{ $initiative->short_description }}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{ route('initiatives.show', $initiative->slug) }}" />
        @if($initiative->featured_image_url)
        <meta property="og:image" content="{{ $initiative->featured_image_url }}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        @endif
        
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="{{ $initiative->title }}" />
        <meta name="twitter:description" content="{{ $initiative->short_description }}" />
        @if($initiative->featured_image_url)
        <meta name="twitter:image" content="{{ $initiative->featured_image_url }}" />
        @endif
    @endpush

    <!-- Breadcrumb Navigation -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-[#003366] transition">Home</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('initiatives') }}" class="hover:text-[#003366] transition">Initiatives</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">
                    {{ $initiative->title }}
                </li>
            </ol>
        </div>
    </nav>

    <!-- Initiative Header -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-[#003366]" style="font-family: 'Playfair Display', serif;">
                    {{ $initiative->title }}
                </h1>

                <!-- Social Sharing Buttons -->
                <x-social-share 
                    :url="route('initiatives.show', $initiative->slug)"
                    :title="$initiative->title"
                    :description="$initiative->short_description"
                    hashtags="Community,Initiative"
                    class="mb-8"
                />
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    @if($initiative->featured_image_url)
        <section class="py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <img 
                        src="{{ $initiative->featured_image_url }}" 
                        alt="{{ $initiative->title }}"
                        class="w-full h-auto rounded-lg shadow-lg"
                        loading="eager"
                    >
                </div>
            </div>
        </section>
    @endif

    <!-- Initiative Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Short Description -->
                <div class="mb-8">
                    <p class="text-xl text-gray-700 leading-relaxed">
                        {{ $initiative->short_description }}
                    </p>
                </div>

                <!-- Full Description -->
                <div class="prose prose-lg max-w-none mb-12">
                    {!! nl2br(e($initiative->full_description)) !!}
                </div>

                <!-- Impact Statistics -->
                @if($initiative->impact_stats && count($initiative->impact_stats) > 0)
                    <div class="bg-gradient-to-br from-[#003366] to-[#007AB8] rounded-lg p-8 text-white">
                        <h2 class="text-3xl font-bold mb-8 text-center" style="font-family: 'Playfair Display', serif;">
                            Impact & Achievements
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($initiative->impact_stats as $stat)
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-[#D4A017] mb-2">
                                        {{ $stat['value'] ?? 'N/A' }}
                                    </div>
                                    <div class="text-lg font-medium">
                                        {{ $stat['label'] ?? '' }}
                                    </div>
                                    @if(isset($stat['description']))
                                        <div class="text-sm text-gray-200 mt-2">
                                            {{ $stat['description'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Back to Initiatives -->
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <a 
                    href="{{ route('initiatives') }}" 
                    class="inline-flex items-center text-[#003366] font-semibold hover:text-[#D4A017] transition"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to All Initiatives
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
