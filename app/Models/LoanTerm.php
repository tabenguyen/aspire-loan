<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTerm extends Model
{
    const INTEREST_TYPE_AMOR = 0;
    const INTEREST_TYPE_NON_AMOR = 1;
    use HasFactory;

    protected $fillable = ['apr', 'length', 'fee', 'interest_type'];
}
