<x-layouts.public>
    <x-slot name="title">Home | Hon. Fatimatu Abubakar</x-slot>

    <!-- Hero Banner -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden fade-in">
        @if($homePage && $homePage->hero_image)
            <div class="absolute inset-0 z-0">
                <img 
                    src="{{ $homePage->hero_image }}" 
                    alt="Hero Banner" 
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/30"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-[#003366] to-[#007AB8]"></div>
        @endif
        
        <div class="relative z-10 container mx-auto px-4 text-center text-white">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6" style="font-family: 'Playfair Display', serif;">
                {{ $homePage->title ?? 'Hon. Fatimatu Abubakar' }}
            </h1>
            @if($homePage && $homePage->content)
                <p class="text-xl md:text-2xl max-w-3xl mx-auto" style="font-family: 'Poppins', sans-serif;">
                    {!! Str::limit(strip_tags($homePage->content), 150) !!}
                </p>
            @else
                <p class="text-xl md:text-2xl max-w-3xl mx-auto" style="font-family: 'Poppins', sans-serif;">
                    Leadership & Legacy
                </p>
            @endif
        </div>
    </section>

    <!-- Mission Statement -->
    <section class="py-20 bg-white fade-in">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                @if($missionPage)
                    <h2 class="text-4xl md:text-5xl font-bold text-[#003366] mb-8" style="font-family: 'Playfair Display', serif;">
                        {{ $missionPage->title }}
                    </h2>
                    <div class="text-lg md:text-xl text-gray-700 leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                        {!! $missionPage->content !!}
                    </div>
                @else
                    <h2 class="text-4xl md:text-5xl font-bold text-[#003366] mb-8" style="font-family: 'Playfair Display', serif;">
                        Our Mission
                    </h2>
                    <p class="text-lg md:text-xl text-gray-700 leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                        Dedicated to serving the community with integrity, compassion, and excellence. 
                        Working tirelessly to create positive change and empower those in need.
                    </p>
                @endif
                <div class="mt-8 inline-block px-8 py-2 border-2 border-[#D4A017] text-[#D4A017] font-semibold">
                    COMMITMENT TO EXCELLENCE
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <x-video-section :videos="$homeVideos" />

    <!-- Foundation Section -->
    @php
        $foundationEnabled = App\Models\SiteSetting::get('foundation_enabled', '0') === '1';
        $foundationName = App\Models\SiteSetting::get('foundation_name', 'Foundation');
        $foundationUrl = App\Models\SiteSetting::get('foundation_url', '#');
        $foundationDescription = App\Models\SiteSetting::get('foundation_description', '');
        $foundationImage = App\Models\SiteSetting::get('foundation_image', '');
    @endphp

    @if($foundationEnabled)
    <section class="py-20 bg-gray-50 fade-in">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <!-- Section Title -->
                <h2 class="text-4xl md:text-5xl font-bold text-center text-[#003366] mb-12" style="font-family: 'Playfair Display', serif;">
                    Foundation
                </h2>

                <!-- Foundation Card -->
                <div class="text-center">
                    <!-- Logo/Image Section -->
                    @if($foundationImage)
                        <div class="w-full max-w-2xl mx-auto mb-8">
                            <img src="{{ Storage::url($foundationImage) }}" alt="{{ $foundationName }}" class="w-full h-auto object-contain">
                        </div>
                    @endif

                    <!-- Content Section -->
                    <div class="max-w-3xl mx-auto">
                        <h3 class="text-3xl md:text-4xl font-bold text-[#003366] mb-4" style="font-family: 'Playfair Display', serif;">
                            {{ $foundationName }}
                        </h3>
                        @if($foundationDescription)
                            <p class="text-lg md:text-xl text-gray-700 mb-8" style="font-family: 'Poppins', sans-serif;">
                                {{ $foundationDescription }}
                            </p>
                        @endif
                        <a href="{{ $foundationUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-8 py-4 bg-[#D4A017] text-white rounded-lg font-bold text-lg hover:bg-[#003366] transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <span style="font-family: 'Poppins', sans-serif;">Visit Foundation Website</span>
                            <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Quick Navigation Cards -->
    <section class="py-20 bg-gray-50 fade-in">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-[#003366] mb-12" style="font-family: 'Playfair Display', serif;">
                Explore
            </h2>
            @php
                $aboutImage = App\Models\SiteSetting::get('explore_about_image', '');
                $initiativesImage = App\Models\SiteSetting::get('explore_initiatives_image', '');
                $newsImage = App\Models\SiteSetting::get('explore_news_image', '');
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- About Card -->
                <a href="{{ route('about') }}" class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    @if($aboutImage)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ Storage::url($aboutImage) }}" alt="About" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-[#003366] to-[#007AB8] flex items-center justify-center">
                            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-[#003366] mb-3" style="font-family: 'Playfair Display', serif;">About</h3>
                        <p class="text-gray-600" style="font-family: 'Poppins', sans-serif;">
                            Learn about Hon. Fatimatu's background, achievements, and leadership philosophy.
                        </p>
                        <div class="mt-4 text-[#D4A017] font-semibold group-hover:translate-x-2 transition-transform inline-flex items-center">
                            Learn More
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Initiatives Card -->
                <a href="{{ route('initiatives') }}" class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    @if($initiativesImage)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ Storage::url($initiativesImage) }}" alt="Initiatives" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-[#007AB8] to-[#003366] flex items-center justify-center">
                            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-[#003366] mb-3" style="font-family: 'Playfair Display', serif;">Initiatives</h3>
                        <p class="text-gray-600" style="font-family: 'Poppins', sans-serif;">
                            Discover the programs and projects making a difference in our community.
                        </p>
                        <div class="mt-4 text-[#D4A017] font-semibold group-hover:translate-x-2 transition-transform inline-flex items-center">
                            View Projects
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- News Card -->
                <a href="{{ route('news') }}" class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    @if($newsImage)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ Storage::url($newsImage) }}" alt="News" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-[#D4A017] to-[#B8860B] flex items-center justify-center">
                            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-[#003366] mb-3" style="font-family: 'Playfair Display', serif;">News</h3>
                        <p class="text-gray-600" style="font-family: 'Poppins', sans-serif;">
                            Stay updated with the latest news, announcements, and stories.
                        </p>
                        <div class="mt-4 text-[#D4A017] font-semibold group-hover:translate-x-2 transition-transform inline-flex items-center">
                            Read More
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Recent News -->
    @if($recentNews->isNotEmpty())
    <section class="py-20 bg-white fade-in">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-[#003366] mb-12" style="font-family: 'Playfair Display', serif;">
                Recent News
            </h2>
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($recentNews as $article)
                        <x-news-card :article="$article" />
                    @endforeach
                </div>

                @if($hasMoreNews)
                    <div class="text-center mt-12">
                        <a href="{{ route('news') }}" class="inline-flex items-center px-8 py-4 bg-[#003366] text-white rounded-lg font-bold text-lg hover:bg-[#007AB8] transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <span style="font-family: 'Poppins', sans-serif;">See More News</span>
                            <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @include('partials.schema-person')
</x-layouts.public>
