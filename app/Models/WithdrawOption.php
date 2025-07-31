<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WithdrawOption extends Model
{
    use HasFactory;
    public function withdraws(): HasMany
    {
        return $this->hasMany(Withdraw::class);
    }
}
