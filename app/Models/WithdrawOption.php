<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WithdrawOption extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image_path',
    ];
    use HasFactory;
    public function withdraws(): HasMany
    {
        return $this->hasMany(Withdraw::class);
    }
}
