<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Social Media Links</h1>
                    <p class="mt-2 text-gray-600">Manage your social media profile links</p>
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

                <!-- Social Media Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="{{ route('admin.social-media.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Facebook URL -->
                        <div class="mb-6">
                            <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-2">
                                Facebook URL
                            </label>
                            <input type="url" name="social_facebook" id="social_facebook" value="{{ old('social_facebook', $socialMedia['facebook']) }}"
                                placeholder="https://facebook.com/yourpage"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('social_facebook') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Full URL to your Facebook page</p>
                            @error('social_facebook')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Twitter/X URL -->
                        <div class="mb-6">
                            <label for="social_twitter" class="block text-sm font-medium text-gray-700 mb-2">
                                Twitter/X URL
                            </label>
                            <input type="url" name="social_twitter" id="social_twitter" value="{{ old('social_twitter', $socialMedia['twitter']) }}"
                                placeholder="https://twitter.com/yourhandle"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('social_twitter') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Full URL to your Twitter/X profile</p>
                            @error('social_twitter')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instagram URL -->
                        <div class="mb-6">
                            <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-2">
                                Instagram URL
                            </label>
                            <input type="url" name="social_instagram" id="social_instagram" value="{{ old('social_instagram', $socialMedia['instagram']) }}"
                                placeholder="https://instagram.com/yourhandle"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('social_instagram') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Full URL to your Instagram profile</p>
                            @error('social_instagram')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- LinkedIn URL -->
                        <div class="mb-6">
                            <label for="social_linkedin" class="block text-sm font-medium text-gray-700 mb-2">
                                LinkedIn URL
                            </label>
                            <input type="url" name="social_linkedin" id="social_linkedin" value="{{ old('social_linkedin', $socialMedia['linkedin']) }}"
                                placeholder="https://linkedin.com/in/yourprofile"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('social_linkedin') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Full URL to your LinkedIn profile</p>
                            @error('social_linkedin')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 mt-8">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Social Media Links
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
