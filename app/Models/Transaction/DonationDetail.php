<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Donation;
use App\Models\Master\Campaign;
use Illuminate\Database\Eloquent\SoftDeletes;


class DonationDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 't_donation_detail';
    protected $fillable = [
        'donation_id',
        'program_id',
        'amount',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function campaign()
    {
        return $this->hasOne(Campaign::class, 'id', 'program_id');
    }
}
