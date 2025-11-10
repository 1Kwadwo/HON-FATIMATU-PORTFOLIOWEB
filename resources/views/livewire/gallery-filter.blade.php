<?php

use App\Models\GalleryItem;
use Livewire\Volt\Component;

new class extends Component {
    public $category = 'all';
    public $items = [];

    public function mount($category = 'all')
    {
        $this->category = $category;
        $this->loadItems();
    }

    public function filterByCategory($category)
    {
        $this->category = $category;
        $this->loadItems();
    }

    public function loadItems()
    {
        $query = GalleryItem::published()->ordered();

        if ($this->category !== 'all') {
            $query->byCategory($this->category);
        }

        $this->items = $query->get();
    }
}; ?>

<div>
    <!-- Category Filter Buttons -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-wrap gap-3">
                <button 
                    wire:click="filterByCategory('all')"
                    class="px-6 py-2 rounded-lg transition {{ $category === 'all' ? 'bg-[#D4A017] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </button>
                <button 
                    wire:click="filterByCategory('events')"
                    class="px-6 py-2 rounded-lg transition {{ $category === 'events' ? 'bg-[#D4A017] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Events
                </button>
                <button 
                    wire:click="filterByCategory('community')"
                    class="px-6 py-2 rounded-lg transition {{ $category === 'community' ? 'bg-[#D4A017] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Community
                </button>
                <button 
                    wire:click="filterByCategory('official_duties')"
                    class="px-6 py-2 rounded-lg transition {{ $category === 'official_duties' ? 'bg-[#D4A017] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Official Duties
                </button>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="container mx-auto px-4 py-12">
        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($items as $item)
                    <div class="gallery-item group cursor-pointer" data-index="{{ $loop->index }}">
                        <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                            <img 
                                src="{{ $item->image_url }}" 
                                alt="{{ $item->title }}"
                                class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            >
                            @if($item->caption)
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                    <p class="text-white text-sm">{{ $item->caption }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-gray-500 text-lg">No gallery items found in this category.</p>
            </div>
        @endif
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center">
        <button id="lightbox-close" class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <button id="lightbox-prev" class="absolute left-4 text-white hover:text-gray-300 transition z-10">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button id="lightbox-next" class="absolute right-4 text-white hover:text-gray-300 transition z-10">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <div class="max-w-6xl max-h-[90vh] mx-4">
            <img id="lightbox-image" src="" alt="" class="max-w-full max-h-[80vh] object-contain">
            <div id="lightbox-caption" class="text-white text-center mt-4 text-lg"></div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', initGallery);
        document.addEventListener('DOMContentLoaded', initGallery);

        function initGallery() {
            // Gallery data
            const galleryItems = @json($items->map(function($item) {
                return [
                    'url' => $item->image_url,
                    'title' => $item->title,
                    'caption' => $item->caption
                ];
            }));

            let currentIndex = 0;

            // Lightbox elements
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxCaption = document.getElementById('lightbox-caption');
            const lightboxClose = document.getElementById('lightbox-close');
            const lightboxPrev = document.getElementById('lightbox-prev');
            const lightboxNext = document.getElementById('lightbox-next');

            // Open lightbox
            document.querySelectorAll('.gallery-item').forEach(item => {
                item.addEventListener('click', function() {
                    currentIndex = parseInt(this.dataset.index);
                    showLightbox();
                });
            });

            // Close lightbox
            if (lightboxClose) {
                lightboxClose.addEventListener('click', closeLightbox);
            }
            
            if (lightbox) {
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        closeLightbox();
                    }
                });
            }

            // Navigation
            if (lightboxPrev) {
                lightboxPrev.addEventListener('click', function(e) {
                    e.stopPropagation();
                    currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
                    updateLightbox();
                });
            }

            if (lightboxNext) {
                lightboxNext.addEventListener('click', function(e) {
                    e.stopPropagation();
                    currentIndex = (currentIndex + 1) % galleryItems.length;
                    updateLightbox();
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (lightbox && !lightbox.classList.contains('hidden')) {
                    if (e.key === 'Escape') {
                        closeLightbox();
                    } else if (e.key === 'ArrowLeft') {
                        currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
                        updateLightbox();
                    } else if (e.key === 'ArrowRight') {
                        currentIndex = (currentIndex + 1) % galleryItems.length;
                        updateLightbox();
                    }
                }
            });

            function showLightbox() {
                if (lightbox) {
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                    updateLightbox();
                }
            }

            function closeLightbox() {
                if (lightbox) {
                    lightbox.classList.add('hidden');
                    lightbox.classList.remove('flex');
                    document.body.style.overflow = '';
                }
            }

            function updateLightbox() {
                const item = galleryItems[currentIndex];
                if (lightboxImage && item) {
                    lightboxImage.src = item.url;
                    lightboxImage.alt = item.title;
                }
                if (lightboxCaption && item) {
                    lightboxCaption.textContent = item.caption || item.title;
                }
            }
        }
    </script>
</div>
