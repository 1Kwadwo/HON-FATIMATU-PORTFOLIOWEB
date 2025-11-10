<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Contact Information</h1>
                    <p class="mt-2 text-gray-600">Manage the contact details displayed on your contact page</p>
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

                <!-- Contact Info Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="{{ route('admin.contact-info.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Office Address -->
                        <div class="mb-6">
                            <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Office Address
                            </label>
                            <textarea name="contact_address" id="contact_address" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contact_address') border-red-300 @enderror">{{ old('contact_address', $contactInfo['address']) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Full office address (use line breaks for multiple lines)</p>
                            @error('contact_address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Email
                            </label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $contactInfo['email']) }}"
                                placeholder="info@example.com"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contact_email') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Public contact email address</p>
                            @error('contact_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-6">
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Phone
                            </label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $contactInfo['phone']) }}"
                                placeholder="+233 123 456 789"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contact_phone') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Public contact phone number</p>
                            @error('contact_phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Office Hours -->
                        <div class="mb-6">
                            <label for="contact_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Office Hours
                            </label>
                            <textarea name="contact_hours" id="contact_hours" rows="2"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contact_hours') border-red-300 @enderror">{{ old('contact_hours', $contactInfo['hours']) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Office hours (use line breaks for multiple lines)</p>
                            @error('contact_hours')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-8"></div>

                        <!-- Form Submission Settings -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Submission Settings</h3>
                            <p class="text-sm text-gray-600 mb-4">Configure where contact form submissions are sent</p>
                        </div>

                        <!-- Recipient Email -->
                        <div class="mb-6">
                            <label for="contact_recipient_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Form Submission Recipient Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="contact_recipient_email" id="contact_recipient_email" value="{{ old('contact_recipient_email', $contactInfo['recipient_email']) }}"
                                placeholder="admin@example.com"
                                required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contact_recipient_email') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Email address that will receive contact form submissions (not displayed publicly)</p>
                            @error('contact_recipient_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 mt-8">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Contact Information
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
