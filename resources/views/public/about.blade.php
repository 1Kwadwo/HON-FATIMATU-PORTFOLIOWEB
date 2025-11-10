<x-layouts.public>
    <x-slot name="title">{{ $page->meta_title ?? $page->title ?? 'About' }} | Hon. Fatimatu Abubakar</x-slot>

    @if($page->meta_description)
    <meta name="description" content="{{ $page->meta_description }}">
    @endif

    <!-- Hero Section -->
    <section class="relative text-white py-20 md:py-32 bg-[#003366]" style="min-height: 400px; overflow: hidden;">
        @if($page->hero_image)
            <!-- Hero Background Image -->
            <div class="absolute inset-0" style="z-index: 0;">
                <img src="{{ $page->hero_image }}" alt="{{ $page->title }}" class="w-full h-full object-cover" style="object-position: center 25%;">
            </div>
            <!-- Overlay for text readability -->
            <div class="absolute inset-0 bg-[#003366]" style="z-index: 1; opacity: 0.7;"></div>
        @endif
        
        <div class="container mx-auto px-4 relative" style="z-index: 10;">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6" style="font-family: 'Playfair Display', serif;">
                    {{ $page->title }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200" style="font-family: 'Poppins', sans-serif;">
                    Leadership, Legacy, and Service
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="prose prose-lg page-content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Style headings in the page content for emphasis */
        .page-content h1 {
            font-size: 2.5rem !important;
            font-weight: 800 !important;
            color: #003366 !important;
            margin-top: 2rem !important;
            margin-bottom: 1.5rem !important;
            line-height: 1.2 !important;
            font-family: 'Playfair Display', serif !important;
        }

        .page-content h2 {
            font-size: 2rem !important;
            font-weight: 700 !important;
            color: #003366 !important;
            margin-top: 1.5rem !important;
            margin-bottom: 1rem !important;
            font-family: 'Playfair Display', serif !important;
        }

        .page-content h3 {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            color: #D4A017 !important;
            margin-top: 1.25rem !important;
            margin-bottom: 0.75rem !important;
            font-family: 'Poppins', sans-serif !important;
        }

        /* Style images in the page content */
        .page-content img {
            max-width: 400px !important;
            height: auto !important;
            display: block;
            margin: 1.5rem auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            pointer-events: none; /* Prevent clicking */
            user-select: none; /* Prevent selection */
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        /* Prevent right-click context menu on images */
        .page-content img {
            -webkit-touch-callout: none;
        }

        /* Hide image captions/filenames */
        .page-content figcaption {
            display: none !important;
        }

        /* Style figure containers */
        .page-content figure {
            position: relative;
            display: block;
            margin: 1.5rem auto;
            text-align: center;
        }

        /* Responsive sizing for mobile */
        @media (max-width: 640px) {
            .page-content img {
                max-width: 100% !important;
            }
        }

        /* Golden separator lines between paragraphs */
        .prose.page-content p {
            position: relative;
            margin-bottom: 2.5rem !important;
            padding-bottom: 2rem !important;
        }

        .prose.page-content p::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, transparent, #D4A017 20%, #D4A017 80%, transparent);
        }

        /* Remove separator from last paragraph */
        .prose.page-content p:last-of-type::after,
        .prose.page-content p:last-child::after {
            display: none;
        }

        /* Remove separator from paragraphs right before headings */
        .prose.page-content p:has(+ h1)::after,
        .prose.page-content p:has(+ h2)::after,
        .prose.page-content p:has(+ h3)::after {
            display: none;
        }

        /* Add spacing after headings */
        .prose.page-content h1 + p,
        .prose.page-content h2 + p,
        .prose.page-content h3 + p {
            margin-top: 1rem !important;
        }
    </style>

    <script>
        // Prevent right-click on images in page content
        document.addEventListener('DOMContentLoaded', function() {
            const contentImages = document.querySelectorAll('.page-content img');
            contentImages.forEach(img => {
                img.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                });
                
                // Prevent dragging
                img.addEventListener('dragstart', function(e) {
                    e.preventDefault();
                    return false;
                });
            });
        });
    </script>


    <!-- Career Timeline Section -->
    <!-- DEBUG: Timeline items count = {{ $timelineItems ? $timelineItems->count() : 'NULL' }} -->
    @if($timelineItems && $timelineItems->count() > 0)
    <section class="py-16 md:py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-[#003366] mb-12" style="font-family: 'Playfair Display', serif;">
                    Career Timeline
                </h2>

                <div class="relative">
                    <!-- Timeline Line (hidden on mobile) -->
                    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-[#D4A017] to-[#B8860B]"></div>

                    <!-- Timeline Items -->
                    <div class="space-y-16">
                        @foreach($timelineItems as $index => $item)
                        <!-- Timeline Item {{ $index + 1 }} - {{ $index % 2 === 0 ? 'LEFT' : 'RIGHT' }} -->
                        <div class="relative flex flex-col md:flex-row items-stretch timeline-item opacity-0" style="animation: fadeInUp 0.6s ease-out forwards; animation-delay: {{ $index * 0.2 }}s;">
                            @if($index % 2 === 0)
                                <!-- LEFT SIDE: Content -->
                                <div class="md:w-1/2 md:pr-8 flex justify-end">
                                    <div class="w-full bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 border-r-4 border-[#D4A017]">
                                        <h3 class="text-xl font-bold text-[#003366] mb-2 text-right" style="font-family: 'Playfair Display', serif;">
                                            {{ $item->title }}
                                        </h3>
                                        <p class="text-[#D4A017] font-semibold mb-3 flex items-center justify-end" style="font-family: 'Poppins', sans-serif;">
                                            {{ $item->period }}
                                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                        </p>
                                        <p class="text-gray-700 leading-relaxed text-right" style="font-family: 'Poppins', sans-serif;">
                                            {{ $item->description }}
                                        </p>
                                    </div>
                                </div>
                                <!-- CENTER: Dot -->
                                <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-6 h-6 bg-[#D4A017] rounded-full border-4 border-white shadow-lg items-center justify-center z-10 hover:scale-125 transition-transform duration-300">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <!-- RIGHT SIDE: Empty -->
                                <div class="md:w-1/2"></div>
                            @else
                                <!-- LEFT SIDE: Empty -->
                                <div class="md:w-1/2"></div>
                                <!-- CENTER: Dot -->
                                <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-6 h-6 bg-[#D4A017] rounded-full border-4 border-white shadow-lg items-center justify-center z-10 hover:scale-125 transition-transform duration-300">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <!-- RIGHT SIDE: Content -->
                                <div class="md:w-1/2 md:pl-8 flex justify-start">
                                    <div class="w-full bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 border-l-4 border-[#D4A017]">
                                        <h3 class="text-xl font-bold text-[#003366] mb-2 text-left" style="font-family: 'Playfair Display', serif;">
                                            {{ $item->title }}
                                        </h3>
                                        <p class="text-[#D4A017] font-semibold mb-3 flex items-center justify-start" style="font-family: 'Poppins', sans-serif;">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $item->period }}
                                        </p>
                                        <p class="text-gray-700 leading-relaxed text-left" style="font-family: 'Poppins', sans-serif;">
                                            {{ $item->description }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Leadership Philosop
    <section class="py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="max->
                <h2 class="text-3xl md:text-4xl font-bold t">
                    Leadership Philosophy
                </h2>
-8">
                    <div class="text-center fade>
                        <div class="w-16 h-16 bg-[#D4A017] rounded-full flex items-center justify-4">
                            <svg class="w-8 h-8 text-white24">
                                <path stroke-linecap="ro>
                            g>
                        </div>
                        <h3 class="text-xl font-bold text-[#003366] mb-3" style="font-family: 'Playfair Display', serif;">
                            Community First
                        </h3>
                        <p c
                            Prioritizing the needs and voice
                        </p>
                    </div>

                    <div class="text-center fade-in">
                        <div class="w-16 h-">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="roun"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#003366] mb-3" style="font-family: 'Playfair Display', serif;">
                            Integrity
                        </h3>
                        <p class=";">
                            Up
                        </>
                    </div>

              ade-in">
              

                                <path stro>
                            </svg>
                        </div>
                        <h3 class="text-xl 
                            Impact
                        </h3>
                        <p class="text-gray-700" style="font-family: 'Poppins', sans-serif;">
                            Creating measurabs.
                        <
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.public>
