<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageRevision;
use App\Models\User;
use Illuminate\Support\Collection;

class PageService
{
    /**
     * Update a page's content and create a revision.
     *
     * @param Page $page The page to update
     * @param array $data Updated page data
     * @param User $user The user making the update
     * @return Page
     */
    public function updatePage(Page $page, array $data, User $user): Page
    {
        // Save current content as a revision before updating
        // Only save revision if content has actually changed
        if (isset($data['content']) && $data['content'] !== $page->content) {
            $page->saveRevision($user);
        }

        // Set the user who updated the page
        $data['updated_by'] = $user->id;

        // Update the page
        $page->update($data);

        return $page->fresh();
    }

    /**
     * Get the revision history for a page.
     *
     * @param Page $page The page to get revisions for
     * @param int $limit Maximum number of revisions to retrieve (default: 10)
     * @return Collection
     */
    public function getRevisionHistory(Page $page, int $limit = 10): Collection
    {
        return $page->revisions()
            ->with('updatedBy:id,name,email')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Restore a page to a previous revision.
     *
     * @param PageRevision $revision The revision to restore
     * @param User $user The user performing the restore
     * @return Page
     */
    public function restoreRevision(PageRevision $revision, User $user): Page
    {
        $page = $revision->page;

        // Save current content as a revision before restoring
        $page->saveRevision($user);

        // Restore the content from the revision
        $page->content = $revision->content;
        $page->updated_by = $user->id;
        $page->save();

        return $page->fresh();
    }
}
