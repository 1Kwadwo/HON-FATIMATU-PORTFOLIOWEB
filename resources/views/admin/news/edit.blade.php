<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <a href="{{ route('admin.news.index') }}" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">Edit Article</h1>
                            <p class="mt-1 text-gray-600">Update article content and settings</p>
                        </div>
                        <div>
                            @if($article->status === 'draft')
                                <form action="{{ route('admin.news.publish', $article) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Publish Now
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-md font-semibold text-xs uppercase">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Published
                                </span>
                            @endif
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

                <!-- Article Form -->
                <form action="{{ route('admin.news.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Main Content Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg"
                                placeholder="Enter article title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug <span class="text-gray-500 text-xs">(Leave empty to auto-generate from title)</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="article-url-slug">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                                Excerpt <span class="text-red-500">*</span>
                            </label>
                            <textarea name="excerpt" id="excerpt" rows="3" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Brief summary of the article (max 500 characters)">{{ old('excerpt', $article->excerpt) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 500 characters</p>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content with Trix Editor -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <input id="content" type="hidden" name="content" value="{{ old('content', $article->content) }}">
                            <trix-editor input="content" class="trix-content border border-gray-300 rounded-md"></trix-editor>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Featured Image -->
                        @if($article->featured_image_path)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Featured Image
                                </label>
                                <div class="relative inline-block">
                                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="max-w-md rounded-lg shadow-sm">
                                    <button type="button" onclick="confirmDeleteImage()" class="absolute top-2 right-2 p-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Featured Image Upload -->
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $article->featured_image_path ? 'Replace Featured Image' : 'Featured Image' }}
                            </label>
                            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                onchange="previewImage(event)">
                            <p class="mt-1 text-sm text-gray-500">JPEG, PNG, WebP (max 5MB)</p>
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                                <img id="preview" src="" alt="Preview" class="max-w-md rounded-lg shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- SEO & Publishing Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900">SEO & Publishing</h3>

                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $article->meta_title) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="SEO title (defaults to article title)">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="SEO description (defaults to excerpt)">{{ old('meta_description', $article->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Published At -->
                        <div id="publishedAtField" class="{{ old('status', $article->status) === 'published' ? '' : 'hidden' }}">
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Publish Date
                            </label>
                            <input type="datetime-local" name="published_at" id="published_at" 
                                value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to publish immediately</p>
                            @error('published_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.news.index') }}" class="px-6 py-3 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Article
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
        trix-toolbar .trix-button-group--file-tools {
            display: none;
        }
        .trix-content {
            min-height: 400px;
            background-color: white !important;
        }
        
        /* Fix text visibility in Trix editor - force dark text on white background */
        trix-editor {
            background-color: white !important;
            color: #111827 !important;
        }
        
        trix-editor *,
        trix-editor div,
        trix-editor p,
        trix-editor span,
        trix-editor h1,
        trix-editor h2,
        trix-editor h3,
        trix-editor ul,
        trix-editor ol,
        trix-editor li,
        trix-editor strong,
        trix-editor em,
        trix-editor blockquote {
            color: #111827 !important;
            background-color: transparent !important;
        }
        
        trix-editor a {
            color: #2563eb !important;
            text-decoration: underline !important;
        }
        
        /* Override dark mode styles */
        .dark trix-editor,
        .dark trix-editor * {
            color: #111827 !important;
            background-color: white !important;
        }
        
        .dark trix-editor {
            background-color: white !important;
        }
    </style>

    <!-- Delete Image Confirmation Modal -->
    <div id="deleteImageModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Featured Image</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this featured image? This action cannot be undone.
                    </p>
                </div>
                <div class="flex gap-4 px-4 py-3">
                    <button onclick="closeDeleteImageModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button onclick="submitDeleteImage()" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Image Form -->
    <form id="delete-image-form" action="{{ route('admin.news.remove-image', $article) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Image preview
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Show/hide published_at field based on status
        document.getElementById('status').addEventListener('change', function() {
            const publishedAtField = document.getElementById('publishedAtField');
            if (this.value === 'published') {
                publishedAtField.classList.remove('hidden');
            } else {
                publishedAtField.classList.add('hidden');
            }
        });

        // Delete image modal functions
        function confirmDeleteImage() {
            document.getElementById('deleteImageModal').classList.remove('hidden');
        }

        function closeDeleteImageModal() {
            document.getElementById('deleteImageModal').classList.add('hidden');
        }

        function submitDeleteImage() {
            document.getElementById('delete-image-form').submit();
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteImageModal();
            }
        });
    </script>
</x-layouts.app>
