<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">Edit Page: {{ $page->title }}</h1>
                            <p class="mt-1 text-gray-600">Update page content and metadata</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.pages.revisions', $page) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                View History
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Info Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-blue-800">
                                <strong>Last updated:</strong> {{ $page->updated_at->format('M d, Y g:i A') }}
                                @if($page->updatedBy)
                                    by <strong>{{ $page->updatedBy->name }}</strong>
                                @endif
                            </p>
                            <p class="text-sm text-blue-700 mt-1">
                                Changes are automatically saved as revisions. You can restore previous versions from the history.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Page Form -->
                <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Main Content Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg"
                                placeholder="Enter page title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content with Trix Editor -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Content <span class="text-red-500">*</span>
                            </label>
                            <input id="content" type="hidden" name="content" value="{{ old('content', $page->content) }}">
                            <trix-editor input="content" class="trix-content border border-gray-300 rounded-md"></trix-editor>
                            <p class="mt-2 text-sm text-gray-500">
                                Use the toolbar to format text, add headings, lists, and links. Use <strong>Heading 1</strong> for large text.
                            </p>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hero Image Upload (for Home and About pages) -->
                        @if(in_array($page->slug, ['home', 'about']))
                        <div>
                            <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Hero Background Image
                            </label>
                            
                            @if($page->hero_image)
                                <div class="mb-4">
                                    <div class="relative inline-block">
                                        <img src="{{ $page->hero_image }}" alt="Current hero image" class="w-full max-w-md h-48 object-cover rounded-lg shadow-sm">
                                        <button type="button" onclick="confirmDeleteHeroImage()" class="absolute top-2 right-2 p-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">Current hero image</p>
                                </div>
                            @endif
                            
                            <input type="file" name="hero_image" id="hero_image" accept="image/jpeg,image/jpg,image/png,image/webp"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-2 text-sm text-gray-500">
                                Upload a hero background image for the {{ $page->slug === 'home' ? 'homepage' : 'about page' }} banner. Recommended size: 1920x1080px. Max file size: 5MB. Formats: JPEG, PNG, WebP.
                            </p>
                            @error('hero_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-4 hidden">
                                <img id="preview-img" src="" alt="Preview" class="w-full max-w-md h-48 object-cover rounded-lg shadow-sm">
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- SEO Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900">SEO Settings</h3>

                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="SEO title (defaults to page title)">
                            <p class="mt-1 text-sm text-gray-500">Recommended length: 50-60 characters</p>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Brief description for search engines">{{ old('meta_description', $page->meta_description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Recommended length: 150-160 characters</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.pages.index') }}" class="px-6 py-3 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Trix Editor Styles and Scripts -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <style>
        .trix-content {
            min-height: 500px;
        }
        
        /* Trix editor styling */
        trix-editor {
            background-color: white;
            color: #1f2937;
        }
        
        trix-editor:empty:not(:focus)::before {
            color: #9ca3af;
        }
        
        /* Ensure text is visible in Trix editor */
        trix-editor *,
        trix-editor div,
        trix-editor p,
        trix-editor h1,
        trix-editor h2,
        trix-editor h3,
        trix-editor ul,
        trix-editor ol,
        trix-editor li {
            color: #1f2937 !important;
        }
        
        /* Style links in editor */
        trix-editor a {
            color: #2563eb !important;
            text-decoration: underline;
        }
    </style>

    <!-- Delete Hero Image Confirmation Modal -->
    @if(in_array($page->slug, ['home', 'about']) && $page->hero_image)
    <div id="deleteHeroImageModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Hero Image</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this hero image? This action cannot be undone.
                    </p>
                </div>
                <div class="flex gap-4 px-4 py-3">
                    <button onclick="closeDeleteHeroImageModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button onclick="submitDeleteHeroImage()" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Hero Image Form -->
    <form id="delete-hero-image-form" action="{{ route('admin.pages.remove-hero-image', $page) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif

    <script>
        // Character counter for meta fields
        function updateCharCount(inputId, countId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(countId);
            
            if (input && counter) {
                const length = input.value.length;
                counter.textContent = `${length}/${maxLength} characters`;
                
                if (length > maxLength) {
                    counter.classList.add('text-red-600');
                    counter.classList.remove('text-gray-500');
                } else {
                    counter.classList.add('text-gray-500');
                    counter.classList.remove('text-red-600');
                }
            }
        }

        // Add character counters if needed
        document.addEventListener('DOMContentLoaded', function() {
            const metaTitle = document.getElementById('meta_title');
            const metaDescription = document.getElementById('meta_description');
            
            if (metaTitle) {
                metaTitle.addEventListener('input', function() {
                    const length = this.value.length;
                    const helpText = this.nextElementSibling;
                    if (helpText && helpText.classList.contains('text-gray-500')) {
                        helpText.textContent = `${length} characters (recommended: 50-60)`;
                    }
                });
            }
            
            if (metaDescription) {
                metaDescription.addEventListener('input', function() {
                    const length = this.value.length;
                    const helpText = this.nextElementSibling;
                    if (helpText && helpText.classList.contains('text-gray-500')) {
                        helpText.textContent = `${length} characters (recommended: 150-160)`;
                    }
                });
            }
            
            // Image preview for hero image
            const heroImageInput = document.getElementById('hero_image');
            if (heroImageInput) {
                heroImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('preview-img').src = e.target.result;
                            document.getElementById('image-preview').classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Trix editor image upload handler
            document.addEventListener('trix-attachment-add', function(event) {
                if (event.attachment.file) {
                    uploadFileAttachment(event.attachment);
                }
            });
            
            function uploadFileAttachment(attachment) {
                const file = attachment.file;
                const formData = new FormData();
                formData.append('file', file);
                
                fetch('{{ route('admin.trix.upload') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.url) {
                        attachment.setAttributes({
                            url: data.url,
                            href: data.url
                        });
                    } else {
                        alert('Failed to upload image');
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Failed to upload image');
                });
            }
        });

        // Delete hero image modal functions
        function confirmDeleteHeroImage() {
            document.getElementById('deleteHeroImageModal').classList.remove('hidden');
        }

        function closeDeleteHeroImageModal() {
            document.getElementById('deleteHeroImageModal').classList.add('hidden');
        }

        function submitDeleteHeroImage() {
            document.getElementById('delete-hero-image-form').submit();
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const heroModal = document.getElementById('deleteHeroImageModal');
                if (heroModal && !heroModal.classList.contains('hidden')) {
                    closeDeleteHeroImageModal();
                }
            }
        });
    </script>
</x-layouts.app>
