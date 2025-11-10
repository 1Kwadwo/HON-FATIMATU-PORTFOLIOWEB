<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Site Settings</h1>
                    <p class="mt-2 text-gray-600">Manage foundation link and other site settings</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
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

                <!-- Settings Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" wire:navigate.stop>
                        @csrf
                        @method('PUT')

                        <h3 class="text-xl font-bold text-gray-900 mb-6">Foundation Settings</h3>

                        <!-- Foundation Name -->
                        <div class="mb-6">
                            <label for="foundation_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Foundation Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="foundation_name" id="foundation_name" value="{{ old('foundation_name', $settings['foundation_name']) }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('foundation_name') border-red-300 @enderror">
                            @error('foundation_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foundation URL -->
                        <div class="mb-6">
                            <label for="foundation_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Foundation Website URL <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="foundation_url" id="foundation_url" value="{{ old('foundation_url', $settings['foundation_url']) }}" required
                                placeholder="https://example.com"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('foundation_url') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">The external website URL for the foundation</p>
                            @error('foundation_url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foundation Description -->
                        <div class="mb-6">
                            <label for="foundation_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="foundation_description" id="foundation_description" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('foundation_description') border-red-300 @enderror">{{ old('foundation_description', $settings['foundation_description']) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Brief description shown on the homepage (max 500 characters)</p>
                            @error('foundation_description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foundation Background Image -->
                        <div class="mb-6">
                            <label for="foundation_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Background Image (Optional)
                            </label>
                            
                            @if($settings['foundation_image'])
                                <div class="mb-4">
                                    <img src="{{ Storage::url($settings['foundation_image']) }}" alt="Foundation Background" class="w-full max-w-md h-48 object-cover rounded-lg shadow-md">
                                    <form action="{{ route('admin.settings.remove-image') }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Remove Image</button>
                                    </form>
                                </div>
                            @endif

                            <input type="file" name="foundation_image" id="foundation_image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">If no image is uploaded, a blue gradient background will be used</p>
                            @error('foundation_image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Enable/Disable -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="foundation_enabled" id="foundation_enabled" value="1" {{ old('foundation_enabled', $settings['foundation_enabled']) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="foundation_enabled" class="ml-2 block text-sm text-gray-900">
                                    Show foundation section on homepage
                                </label>
                            </div>
                            <p class="mt-1 ml-6 text-sm text-gray-500">Uncheck to hide the foundation section from the homepage</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 mt-8">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Save Settings
                            </button>
                        </div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.querySelector('form');
                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        console.log('Form is submitting...');
                                        const button = form.querySelector('button[type="submit"]');
                                        if (button) {
                                            button.disabled = true;
                                            button.textContent = 'Saving...';
                                        }
                                    });
                                }
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
