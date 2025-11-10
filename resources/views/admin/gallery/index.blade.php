<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Gallery Management</h1>
                        <p class="mt-2 text-gray-600">Manage gallery images and categories</p>
                    </div>
                    <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Upload New Image
                    </a>
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

                <!-- Filter Controls -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <form method="GET" action="{{ route('admin.gallery.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="category" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="all" {{ request('category') === 'all' ? 'selected' : '' }}>All Categories</option>
                                <option value="events" {{ request('category') === 'events' ? 'selected' : '' }}>Events</option>
                                <option value="community" {{ request('category') === 'community' ? 'selected' : '' }}>Community</option>
                                <option value="official_duties" {{ request('category') === 'official_duties' ? 'selected' : '' }}>Official Duties</option>
                            </select>
                        </div>

                        <div class="flex-1 min-w-[200px]">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="unpublished" {{ request('status') === 'unpublished' ? 'selected' : '' }}>Unpublished</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Gallery Items Grid -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    @if($items->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($items as $item)
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    <!-- Thumbnail -->
                                    <div class="aspect-square bg-gray-100 relative">
                                        @php
                                            $thumbnailPath = str_replace('/original/', '/thumbnails/', $item->image_path);
                                            $thumbnailUrl = Storage::disk('public')->exists($thumbnailPath) 
                                                ? Storage::url($thumbnailPath) 
                                                : $item->image_url;
                                        @endphp
                                        <img src="{{ $thumbnailUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                        
                                        <!-- Status Badge -->
                                        <div class="absolute top-2 right-2">
                                            @if($item->is_published)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unpublished</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Item Details -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 mb-1 truncate">{{ $item->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                                            </span>
                                        </p>
                                        @if($item->caption)
                                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $item->caption }}</p>
                                        @endif

                                        <!-- Actions -->
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.gallery.edit', $item) }}" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                                Edit
                                            </a>
                                            <button onclick="confirmDelete({{ $item->id }})" class="flex-1 text-center px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                                                Delete
                                            </button>
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $items->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No gallery items</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by uploading a new image.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Upload New Image
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Gallery Item</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this gallery item? This action cannot be undone and will permanently remove the image from storage.
                    </p>
                </div>
                <div class="flex gap-4 px-4 py-3">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button onclick="submitDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteFormId = null;

        function confirmDelete(itemId) {
            deleteFormId = itemId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteFormId = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function submitDelete() {
            if (deleteFormId) {
                document.getElementById('delete-form-' + deleteFormId).submit();
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-layouts.app>
