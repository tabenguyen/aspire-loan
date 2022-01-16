<?php

namespace App\Models;

use App\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const TYPE_FEE = 'fee';
    const TYPE_REPAYMENT = 'repayment';
    const TYPE_INTEREST = 'interest';

    use HasFactory;
    use HasCreator;
}
