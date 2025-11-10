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
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Create New Article</h1>
                            <p class="mt-1 text-gray-600">Write and publish a new news article</p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
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
                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Main Content Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg"
                                placeholder="Enter article title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug (Optional) -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug <span class="text-gray-500 text-xs">(Leave empty to auto-generate from title)</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
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
                                placeholder="Brief summary of the article (max 500 characters)">{{ old('excerpt') }}</textarea>
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
                            <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                            <trix-editor input="content" class="trix-content border border-gray-300 rounded-md"></trix-editor>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Featured Image
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
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
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
                                placeholder="SEO description (defaults to excerpt)">{{ old('meta_description') }}</textarea>
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
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Published At -->
                        <div id="publishedAtField" class="hidden">
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Publish Date
                            </label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
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
                            Create Article
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

        // Trigger on page load if status is already published
        if (document.getElementById('status').value === 'published') {
            document.getElementById('publishedAtField').classList.remove('hidden');
        }
    </script>
</x-layouts.app>
