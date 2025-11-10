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
                            <h1 class="text-3xl font-bold text-gray-900">Create New Initiative</h1>
                            <p class="mt-2 text-gray-600">Add a new initiative or program</p>
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
                <form action="{{ route('admin.initiatives.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
                    @csrf

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Enter initiative title">
                    </div>

                    <!-- Slug -->
                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug <span class="text-gray-500 text-xs">(Leave empty to auto-generate from title)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
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
                            placeholder="Brief description for card display (max 500 characters)">{{ old('short_description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">This will be displayed on initiative cards</p>
                    </div>

                    <!-- Full Description -->
                    <div class="mb-6">
                        <label for="full_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="full_description" id="full_description" rows="10" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Complete description of the initiative">{{ old('full_description') }}</textarea>
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-6">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Featured Image
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
                            @if(old('impact_stats'))
                                @foreach(old('impact_stats') as $index => $stat)
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
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first. You can also drag to reorder on the main page.</p>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Publish this initiative</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Unpublished initiatives won't be visible on the public site</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Create Initiative
                        </button>
                        <a href="{{ route('admin.initiatives.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let statIndex = {{ old('impact_stats') ? count(old('impact_stats')) : 0 }};

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

        // Auto-generate slug from title
        document.getElementById('title').addEventListener('input', function(e) {
            const slugInput = document.getElementById('slug');
            if (!slugInput.value || slugInput.dataset.autoGenerated) {
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
    </script>
</x-layouts.app>
