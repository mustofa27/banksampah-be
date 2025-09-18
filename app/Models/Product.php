<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image_path',
        'price',
        'stock',
        'point',
    ];
    public function comments(): HasMany
    {
        return $this->hasMany(ProductComment::class);
    }
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }
    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
