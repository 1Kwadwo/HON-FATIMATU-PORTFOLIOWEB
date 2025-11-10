@props(['videos'])

@if($videos->isNotEmpty())
    <section class="py-20 bg-gradient-to-br from-[#003366] to-[#007AB8] fade-in">
        <div class="container mx-auto px-4">
            @foreach($videos as $video)
                <div class="max-w-6xl mx-auto {{ !$loop->last ? 'mb-16' : '' }}">
                    <!-- Video Title -->
                    <div class="text-center mb-8">
                        <h2 class="text-4xl md:text-5xl font-bold text-white mb-4" style="font-family: 'Playfair Display', serif;">
                            {{ $video->title }}
                        </h2>
                        @if($video->description)
                            <p class="text-xl text-white/90 max-w-3xl mx-auto" style="font-family: 'Poppins', sans-serif;">
                                {{ $video->description }}
                            </p>
                        @endif
                    </div>

                    <!-- Video Player Container with Gold Border -->
                    <div class="relative p-2 bg-gradient-to-br from-[#D4A017] to-[#B8860B] rounded-2xl shadow-2xl">
                        <div class="relative rounded-xl overflow-hidden" style="min-height: 400px;">
                            <!-- Blue Loading Fallback -->
                            <div class="absolute inset-0 bg-gradient-to-br from-[#003366] to-[#007AB8] flex items-center justify-center z-0">
                                <div class="text-center">
                                    <!-- Play Icon -->
                                    <div class="mb-4">
                                        <svg class="w-24 h-24 mx-auto text-white/80 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                    <!-- Loading Text -->
                                    <p class="text-white text-lg font-semibold" style="font-family: 'Poppins', sans-serif;">Loading Video...</p>
                                </div>
                            </div>
                            
                            <!-- 16:9 Aspect Ratio Container -->
                            <div class="relative w-full z-10" style="padding-bottom: 56.25%; height: 0;">
                                <iframe 
                                    src="{{ $video->embed_url }}" 
                                    title="{{ $video->title }}"
                                    class="absolute top-0 left-0 w-full h-full border-0 bg-black"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    style="border: none;"
                                    onload="this.style.opacity='1'"
                                    style="opacity: 0; transition: opacity 0.3s ease-in-out;"
                                ></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Watch on YouTube Link -->
                    <div class="text-center mt-6">
                        <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-white hover:text-[#D4A017] transition-colors duration-200">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            <span class="font-semibold" style="font-family: 'Poppins', sans-serif;">Watch on YouTube</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
