<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use CreatedByUpdatedByTrait;
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'price',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['price_in_pounds'];

    public function getPriceInPoundsAttribute(): float
    {
        return $this->price / 100;
    }

    public function skus(): HasMany
    {
        return $this->hasMany(SKU::class);
    }

    public function configurations(): HasMany
    {
        return $this->hasMany(Configuration::class);
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
