<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait CreatedByUpdatedByTrait
{
    public static function bootCreatedByUpdatedByTrait(): void
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? 1; // Hardcoded user id for demo purposes
            $model->updated_by = Auth::id() ?? 1; // Hardcoded user id for demo purposes
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? 1; // Hardcoded user id for demo purposes
        });
    }
}
