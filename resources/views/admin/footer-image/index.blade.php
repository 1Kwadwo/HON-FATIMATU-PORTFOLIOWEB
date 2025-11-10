<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Footer Image</h1>
                    <p class="mt-2 text-gray-600">Manage the image displayed in the footer section</p>
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

                <!-- Current Image -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Current Footer Image</h3>
                    
                    @if($footerImage)
                        <div class="mb-4">
                            <div class="bg-[#003366] p-8 rounded-lg">
                                <img src="{{ Storage::url($footerImage) }}" alt="Footer Image" class="w-full max-w-md mx-auto h-auto object-contain">
                            </div>
                            <form action="{{ route('admin.footer-image.remove') }}" method="POST" class="mt-4" onsubmit="return confirm('Are you sure you want to remove the footer image?');">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    Remove Image
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-4 bg-[#003366] p-12 rounded-lg flex items-center justify-center">
                            <div class="text-center text-white">
                                <svg class="w-24 h-24 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg">No footer image uploaded</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Upload Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        {{ $footerImage ? 'Replace Footer Image' : 'Upload Footer Image' }}
                    </h3>
                    
                    <form action="{{ route('admin.footer-image.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="footer_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Footer Image
                            </label>
                            <input type="file" name="footer_image" id="footer_image" accept="image/*" required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-2 text-sm text-gray-500">
                                Recommended: PNG or SVG with transparent background, Max 5MB<br>
                                This image will be displayed in the blue footer section of your website.
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $footerImage ? 'Replace Image' : 'Upload Image' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
