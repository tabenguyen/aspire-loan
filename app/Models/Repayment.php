<?php

namespace App\Models;

use App\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repayment extends Model
{
    use HasFactory;
    use HasCreator;

    public $fillable = ['user_id', 'contract_id', 'amount', 'target_before', 'target_after'];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'target');
    }
}
