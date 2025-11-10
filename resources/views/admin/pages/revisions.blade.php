<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">Revision History: {{ $page->title }}</h1>
                            <p class="mt-1 text-gray-600">View and restore previous versions of this page</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Page
                            </a>
                        </div>
                    </div>
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

                <!-- Current Version Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border-2 border-green-500">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Current Version</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Last updated: {{ $page->updated_at->format('M d, Y g:i A') }}
                                    @if($page->updatedBy)
                                        by <strong>{{ $page->updatedBy->name }}</strong>
                                    @endif
                                </p>
                                <div class="mt-3 prose prose-sm max-w-none">
                                    <div class="text-gray-700 line-clamp-3">
                                        {!! Str::limit(strip_tags($page->content), 200) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Current
                        </span>
                    </div>
                </div>

                <!-- Revisions List -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Previous Revisions</h2>
                        <p class="text-sm text-gray-600 mt-1">Showing the last 10 revisions</p>
                    </div>

                    @if($revisions->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($revisions as $revision)
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start flex-1">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <div class="flex items-center gap-3">
                                                    <h4 class="text-sm font-semibold text-gray-900">
                                                        Revision from {{ $revision->created_at->format('M d, Y g:i A') }}
                                                    </h4>
                                                    <span class="text-xs text-gray-500">
                                                        ({{ $revision->created_at->diffForHumans() }})
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Updated by: <strong>{{ $revision->updatedBy->name }}</strong>
                                                </p>
                                                
                                                <!-- Content Preview -->
                                                <div class="mt-3">
                                                    <button 
                                                        onclick="togglePreview({{ $revision->id }})" 
                                                        class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                                                    >
                                                        <span id="preview-toggle-{{ $revision->id }}">Show preview</span>
                                                    </button>
                                                    <div id="preview-{{ $revision->id }}" class="hidden mt-2 p-4 bg-gray-50 rounded-md border border-gray-200">
                                                        <div class="prose prose-sm max-w-none text-gray-700">
                                                            {!! Str::limit(strip_tags($revision->content), 300) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Restore Button -->
                                        <div class="ml-4">
                                            <button 
                                                onclick="confirmRestore({{ $revision->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                                                title="Restore this revision"
                                            >
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Restore
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Hidden Restore Form -->
                                    <form id="restore-form-{{ $revision->id }}" action="{{ route('admin.pages.restore', $revision) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No revisions yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Revisions will appear here when you make changes to the page.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div id="restoreModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Restore Revision</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to restore this revision? The current version will be saved as a new revision before restoring.
                    </p>
                </div>
                <div class="flex gap-4 px-4 py-3">
                    <button onclick="closeRestoreModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button onclick="submitRestore()" class="flex-1 px-4 py-2 bg-purple-600 text-white text-base font-medium rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Restore
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let restoreFormId = null;

        function confirmRestore(revisionId) {
            restoreFormId = revisionId;
            document.getElementById('restoreModal').classList.remove('hidden');
        }

        function closeRestoreModal() {
            restoreFormId = null;
            document.getElementById('restoreModal').classList.add('hidden');
        }

        function submitRestore() {
            if (restoreFormId) {
                document.getElementById('restore-form-' + restoreFormId).submit();
            }
        }

        function togglePreview(revisionId) {
            const preview = document.getElementById('preview-' + revisionId);
            const toggle = document.getElementById('preview-toggle-' + revisionId);
            
            if (preview.classList.contains('hidden')) {
                preview.classList.remove('hidden');
                toggle.textContent = 'Hide preview';
            } else {
                preview.classList.add('hidden');
                toggle.textContent = 'Show preview';
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeRestoreModal();
            }
        });
    </script>
</x-layouts.app>
