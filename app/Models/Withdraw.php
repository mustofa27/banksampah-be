<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    protected $fillable = [
        'count',
        'balance_used',
        'status',
        'withdraw_option_id',
        'user_id',
    ];
    use HasFactory;
    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(WithdrawOption::class,'withdraw_option_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
