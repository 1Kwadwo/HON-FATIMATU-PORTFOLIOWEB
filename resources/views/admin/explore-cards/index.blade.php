<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Explore Section Cards</h1>
                    <p class="mt-2 text-gray-600">Manage the images for the Explore section cards on the homepage</p>
                </div>

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

                <!-- Form -->
                <form action="{{ route('admin.explore-cards.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- About Card Image -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">About Card</h3>
                            
                            @if($exploreCards['about_image'])
                                <div class="mb-4">
                                    <img src="{{ Storage::url($exploreCards['about_image']) }}" alt="About Card" class="w-full h-48 object-cover rounded-lg">
                                    <form action="{{ route('admin.explore-cards.remove-image') }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to remove this image?');">
                                        @csrf
                                        <input type="hidden" name="card" value="about">
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="event.stopPropagation();">Remove Image</button>
                                    </form>
                                </div>
                            @else
                                <div class="mb-4 h-48 bg-gradient-to-br from-blue-900 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">No custom image. Default icon will be shown.</p>
                            @endif

                            <label for="about_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload New Image
                            </label>
                            <input type="file" name="about_image" id="about_image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 800x600px, Max 5MB</p>
                        </div>

                        <!-- Initiatives Card Image -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Initiatives Card</h3>
                            
                            @if($exploreCards['initiatives_image'])
                                <div class="mb-4">
                                    <img src="{{ Storage::url($exploreCards['initiatives_image']) }}" alt="Initiatives Card" class="w-full h-48 object-cover rounded-lg">
                                    <form action="{{ route('admin.explore-cards.remove-image') }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to remove this image?');">
                                        @csrf
                                        <input type="hidden" name="card" value="initiatives">
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="event.stopPropagation();">Remove Image</button>
                                    </form>
                                </div>
                            @else
                                <div class="mb-4 h-48 bg-gradient-to-br from-blue-500 to-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">No custom image. Default icon will be shown.</p>
                            @endif

                            <label for="initiatives_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload New Image
                            </label>
                            <input type="file" name="initiatives_image" id="initiatives_image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 800x600px, Max 5MB</p>
                        </div>

                        <!-- News Card Image -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">News Card</h3>
                            
                            @if($exploreCards['news_image'])
                                <div class="mb-4">
                                    <img src="{{ Storage::url($exploreCards['news_image']) }}" alt="News Card" class="w-full h-48 object-cover rounded-lg">
                                    <form action="{{ route('admin.explore-cards.remove-image') }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to remove this image?');">
                                        @csrf
                                        <input type="hidden" name="card" value="news">
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="event.stopPropagation();">Remove Image</button>
                                    </form>
                                </div>
                            @else
                                <div class="mb-4 h-48 bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">No custom image. Default icon will be shown.</p>
                            @endif

                            <label for="news_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload New Image
                            </label>
                            <input type="file" name="news_image" id="news_image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 800x600px, Max 5MB</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Update Explore Cards
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
