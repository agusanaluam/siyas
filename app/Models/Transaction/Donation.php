<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\DonationDetail;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\Volunteer;


class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_donation';
    protected $fillable = [
        'volunteer_id',
        'liq_number',
        'donatur_name',
        'donatur_phone',
        'donatur_address',
        'description',
        'total_amount',
        'trans_date',
        'via_transfer',
        'reference_code',
        'reference_picture',
        'approve_lead',
        'status',
    ];

    public function detail()
    {
        return $this->hasMany(DonationDetail::class, 'donation_id', 'id');
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function mutation()
    {
        return $this->belongsTo(MutationDetail::class);
    }
}
