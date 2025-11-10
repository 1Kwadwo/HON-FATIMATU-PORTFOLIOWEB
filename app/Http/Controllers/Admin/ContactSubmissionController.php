<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Services\CacheService;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display a listing of contact submissions with filtering.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = ContactSubmission::query()->recent();
        
        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }
        
        $submissions = $query->paginate(20);
        
        return view('admin.contacts.index', compact('submissions', 'filter'));
    }

    /**
     * Display the specified contact submission.
     */
    public function show(ContactSubmission $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark the specified contact submission as read.
     */
    public function markAsRead(ContactSubmission $contact)
    {
        $contact->markAsRead();
        
        // Invalidate unread count cache
        $this->cacheService->clearContactCountCache();
        
        return redirect()->back()->with('success', 'Contact submission marked as read.');
    }

    /**
     * Remove the specified contact submission from storage.
     */
    public function destroy(ContactSubmission $contact)
    {
        $contact->delete();
        
        // Invalidate unread count cache
        $this->cacheService->clearContactCountCache();
        
        return redirect()->route('admin.contacts.index')->with('success', 'Contact submission deleted successfully.');
    }
}
