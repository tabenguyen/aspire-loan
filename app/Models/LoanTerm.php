<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTerm extends Model
{
    use HasFactory;

    protected $fillable = ['apr', 'length', 'fee', 'interest_type'];
}
