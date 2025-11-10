<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Contact Submission</h1>
                            <p class="mt-2 text-gray-600">Received on {{ $contact->created_at->format('F d, Y \a\t H:i') }}</p>
                        </div>
                        <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to List
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Contact Details Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">{{ strtoupper(substr($contact->name, 0, 1)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $contact->name }}</h2>
                                    <p class="text-sm text-gray-500">{{ $contact->email }}</p>
                                </div>
                            </div>
                            <div>
                                @if($contact->is_read)
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Read</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Unread</span>
                                @endif
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Subject</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $contact->subject }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $contact->created_at->diffForHumans() }}</dd>
                                </div>
                                @if($contact->ip_address)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $contact->ip_address }}</dd>
                                    </div>
                                @endif
                                @if($contact->user_agent)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Str::limit($contact->user_agent, 50) }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Message Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Message</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $contact->message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <div class="flex space-x-4">
                            @if(!$contact->is_read)
                                <form action="{{ route('admin.contacts.mark-as-read', $contact) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Mark as Read
                                    </button>
                                </form>
                            @endif
                            
                            <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Reply via Email
                            </a>
                            
                            <button onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                            <form id="delete-form" action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this contact submission? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
</x-layouts.app>
