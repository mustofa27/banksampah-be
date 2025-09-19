<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Garbage extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'price_per_kg',
    ];
    use HasFactory;
    public function savingHistory(): HasMany
    {
        return $this->hasMany(Saving::class);
    }
}
