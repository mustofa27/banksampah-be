<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class News extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image_path',
        'user_id',
    ];
    use HasFactory;
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }
    public function likes(): HasMany
    {
        return $this->hasMany(NewsLike::class);
    }
}
