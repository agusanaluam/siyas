<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction\MutationDetail;

class Mutation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_mutation';
    protected $fillable = [
        'invoice_number',
        'trans_date',
        'total_amount',
        'total_liq',
        'description',
        'approve_date',
        'approve_by',
        'created_by',
        'status',
    ];

    public function detail()
    {
        return $this->hasMany(MutationDetail::class, 'mutation_id', 'id');
    }
}
