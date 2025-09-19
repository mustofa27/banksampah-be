<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'subtotal_price',
        'subtotal_discount',
        'subtotal_point',
    ];
    use HasFactory;
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
