<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogsAdminActions
{
    /**
     * Log an admin action.
     */
    protected function logAdminAction(string $action, string $model, $modelId = null, array $context = []): void
    {
        Log::channel('admin')->info($action, array_merge([
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'model' => $model,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $context));
    }

    /**
     * Log a create action.
     */
    protected function logCreate(string $model, $modelId, array $context = []): void
    {
        $this->logAdminAction("Created {$model}", $model, $modelId, $context);
    }

    /**
     * Log an update action.
     */
    protected function logUpdate(string $model, $modelId, array $context = []): void
    {
        $this->logAdminAction("Updated {$model}", $model, $modelId, $context);
    }

    /**
     * Log a delete action.
     */
    protected function logDelete(string $model, $modelId, array $context = []): void
    {
        $this->logAdminAction("Deleted {$model}", $model, $modelId, $context);
    }

    /**
     * Log a publish action.
     */
    protected function logPublish(string $model, $modelId, array $context = []): void
    {
        $this->logAdminAction("Published {$model}", $model, $modelId, $context);
    }
}
