<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Mutation;

class MutationDetail extends Model
{
    use HasFactory;

    protected $table = 't_mutation_detail';
    protected $fillable = [
        'mutation_id',
        'donation_id',
        'liq_number',
        'amount',
    ];

    public function mutation()
    {
        return $this->belongsTo(Mutation::class);
    }
    public function donation()
    {
        return $this->hasOne(Donation::class, 'id', 'donation_id');
    }
}
