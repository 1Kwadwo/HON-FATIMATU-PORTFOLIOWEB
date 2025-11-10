<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Contact Submissions</h1>
                        <p class="mt-2 text-gray-600">Manage and respond to contact form submissions</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Filter Tabs -->
                <div class="mb-6 bg-white rounded-lg shadow-sm">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <a href="{{ route('admin.contacts.index', ['filter' => 'all']) }}" 
                               class="@if($filter === 'all') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                All
                                <span class="@if($filter === 'all') bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-900 @endif ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                    {{ \App\Models\ContactSubmission::count() }}
                                </span>
                            </a>
                            <a href="{{ route('admin.contacts.index', ['filter' => 'unread']) }}" 
                               class="@if($filter === 'unread') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Unread
                                <span class="@if($filter === 'unread') bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-900 @endif ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                    {{ \App\Models\ContactSubmission::unread()->count() }}
                                </span>
                            </a>
                            <a href="{{ route('admin.contacts.index', ['filter' => 'read']) }}" 
                               class="@if($filter === 'read') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Read
                                <span class="@if($filter === 'read') bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-900 @endif ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                    {{ \App\Models\ContactSubmission::where('is_read', true)->count() }}
                                </span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Submissions List -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    @if($submissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($submissions as $submission)
                                        <tr class="@if(!$submission->is_read) bg-blue-50 @endif hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($submission->is_read)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Read</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Unread</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $submission->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $submission->email }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ Str::limit($submission->subject, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $submission->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.contacts.show', $submission) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                @if(!$submission->is_read)
                                                    <form action="{{ route('admin.contacts.mark-as-read', $submission) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Mark Read</button>
                                                    </form>
                                                @endif
                                                <button onclick="confirmDelete({{ $submission->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                                <form id="delete-form-{{ $submission->id }}" action="{{ route('admin.contacts.destroy', $submission) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $submissions->links() }}
                        </div>
                    @else
                        <div class="p-6 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2">No contact submissions found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this contact submission? This action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-layouts.app>
