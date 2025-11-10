<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'hero_image',
        'meta_title',
        'meta_description',
        'updated_by',
    ];

    /**
     * Get the revisions for the page.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class);
    }

    /**
     * Get the user who last updated the page.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Save a revision of the current page content.
     */
    public function saveRevision(User $user)
    {
        return $this->revisions()->create([
            'content' => $this->content,
            'updated_by' => $user->id,
        ]);
    }
}
