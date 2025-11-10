<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <a href="{{ route('admin.initiatives.index') }}" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Edit Initiative</h1>
                            <p class="mt-2 text-gray-600">Update initiative details</p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Whoops!</strong>
                        <span class="block sm:inline">There were some problems with your input.</span>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('admin.initiatives.update', $initiative) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $initiative->title) }}" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Enter initiative title">
                    </div>

                    <!-- Slug -->
                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug <span class="text-gray-500 text-xs">(Leave empty to auto-generate from title)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $initiative->slug) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="initiative-slug">
                        <p class="mt-1 text-sm text-gray-500">URL-friendly version of the title</p>
                    </div>

                    <!-- Short Description -->
                    <div class="mb-6">
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Short Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="short_description" id="short_description" rows="3" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Brief description for card display (max 500 characters)">{{ old('short_description', $initiative->short_description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">This will be displayed on initiative cards</p>
                    </div>

                    <!-- Full Description -->
                    <div class="mb-6">
                        <label for="full_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="full_description" id="full_description" rows="10" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Complete description of the initiative">{{ old('full_description', $initiative->full_description) }}</textarea>
                    </div>

                    <!-- Current Featured Image -->
                    @if($initiative->featured_image_path)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Current Featured Image
                            </label>
                            <div class="relative inline-block">
                                <img src="{{ $initiative->featured_image_url }}" alt="{{ $initiative->title }}" class="max-w-md rounded-lg shadow-sm">
                                <button type="button" onclick="confirmDeleteImage()" class="absolute top-2 right-2 p-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Featured Image -->
                    <div class="mb-6">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $initiative->featured_image_path ? 'Replace Featured Image' : 'Featured Image' }}
                        </label>
                        <input type="file" name="featured_image" id="featured_image" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">Recommended size: 1200x800px. Max 5MB. Formats: JPEG, PNG, WebP</p>
                        
                        <!-- Image Preview -->
                        <div id="image-preview" class="mt-4 hidden">
                            <img id="preview-img" src="" alt="Preview" class="max-w-md rounded-lg shadow-sm">
                        </div>
                    </div>

                    <!-- Impact Statistics -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Impact Statistics
                        </label>
                        <p class="text-sm text-gray-500 mb-4">Add key metrics and achievements for this initiative</p>
                        
                        <div id="impact-stats-container" class="space-y-3">
                            @php
                                $stats = old('impact_stats', $initiative->impact_stats ?? []);
                            @endphp
                            @if($stats && count($stats) > 0)
                                @foreach($stats as $index => $stat)
                                    <div class="impact-stat-row flex gap-3">
                                        <input type="text" name="impact_stats[{{ $index }}][label]" value="{{ $stat['label'] ?? '' }}"
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Label (e.g., People Reached)">
                                        <input type="text" name="impact_stats[{{ $index }}][value]" value="{{ $stat['value'] ?? '' }}"
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Value (e.g., 10,000+)">
                                        <button type="button" onclick="removeStatRow(this)" class="px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <button type="button" onclick="addStatRow()" class="mt-3 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Impact Statistic
                        </button>
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-6">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $initiative->sort_order) }}" min="0"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first. You can also drag to reorder on the main page.</p>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $initiative->is_published) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Publish this initiative</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Unpublished initiatives won't be visible on the public site</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Initiative
                        </button>
                        <a href="{{ route('admin.initiatives.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
    <form id="delete-image-form" action="{{ route('admin.initiatives.remove-image', $initiative) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let statIndex = {{ $stats && count($stats) > 0 ? count($stats) : 0 }};

        // Image preview
        document.getElementById('featured_image').addEventListener('change', function(e) {
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

        // Add impact stat row
        function addStatRow() {
            const container = document.getElementById('impact-stats-container');
            const row = document.createElement('div');
            row.className = 'impact-stat-row flex gap-3';
            row.innerHTML = `
                <input type="text" name="impact_stats[${statIndex}][label]"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Label (e.g., People Reached)">
                <input type="text" name="impact_stats[${statIndex}][value]"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Value (e.g., 10,000+)">
                <button type="button" onclick="removeStatRow(this)" class="px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            container.appendChild(row);
            statIndex++;
        }

        // Remove impact stat row
        function removeStatRow(button) {
            button.closest('.impact-stat-row').remove();
        }

        // Auto-generate slug from title (only if slug hasn't been manually edited)
        const originalSlug = '{{ $initiative->slug }}';
        document.getElementById('title').addEventListener('input', function(e) {
            const slugInput = document.getElementById('slug');
            if (slugInput.value === originalSlug || slugInput.dataset.autoGenerated) {
                const slug = e.target.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        document.getElementById('slug').addEventListener('input', function() {
            delete this.dataset.autoGenerated;
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
