<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'unique_code',
        'image_path',
        'total_point',
        'total_discount',
    ];
    use HasFactory;
    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
