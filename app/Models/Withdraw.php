<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    use HasFactory;
    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(WithdrawOption::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
