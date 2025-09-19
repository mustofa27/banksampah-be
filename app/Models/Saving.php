<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saving extends Model
{
    protected $fillable = [
        'weight',
        'total_price',
        'status',
        'garbage_id',
        'user_id',
    ];
    use HasFactory;
    public function garbage(): BelongsTo
    {
        return $this->belongsTo(Garbage::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
