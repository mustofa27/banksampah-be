<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balance extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
    ];
    use HasFactory;
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
