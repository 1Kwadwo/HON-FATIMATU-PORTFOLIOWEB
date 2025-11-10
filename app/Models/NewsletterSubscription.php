<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    protected $fillable = [
        'email',
        'is_active',
        'ip_address',
        'subscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
    ];

    /**
     * Scope to get only active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get recent subscriptions
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('subscribed_at', 'desc')->limit($limit);
    }

    /**
     * Unsubscribe the email
     */
    public function unsubscribe(): bool
    {
        $this->is_active = false;
        return $this->save();
    }
}
