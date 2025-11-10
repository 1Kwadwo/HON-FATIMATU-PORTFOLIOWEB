<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <a href="{{ route('admin.timeline.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Edit Timeline Item</h1>
                            <p class="mt-2 text-gray-600">Update career timeline entry</p>
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

                <!-- Form -->
                <form action="{{ route('admin.timeline.update', $item) }}" method="POST" class="bg-white rounded-lg shadow-sm p-6">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="e.g., Early Career, Ministerial Appointment">
                    </div>

                    <!-- Period -->
                    <div class="mb-6">
                        <label for="period" class="block text-sm font-medium text-gray-700 mb-2">
                            Period <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="period" id="period" value="{{ old('period', $item->period) }}" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="e.g., 2000-2005, Present">
                        <p class="mt-1 text-sm text-gray-500">The time period for this career milestone</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Describe this career milestone...">{{ old('description', $item->description) }}</textarea>
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-6">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first. You can also drag to reorder on the main page.</p>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $item->is_published) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Publish this timeline item</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Unpublished items won't be visible on the About page</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Timeline Item
                        </button>
                        <a href="{{ route('admin.timeline.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
