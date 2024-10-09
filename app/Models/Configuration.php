<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Configuration extends Model
{
    use HasFactory;
    use CreatedByUpdatedByTrait;

    protected $fillable = [
        'product_id',
        'rule_type',
        'rule_details',
        'active',
        'conditions',
        'actions',
        'created_by',
        'updated_by'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
