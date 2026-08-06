<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationAccount extends Model
{
    use HasFactory;

    protected $table = 'donation_accounts';

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
    ];
}

