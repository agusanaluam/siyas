<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanMurajaah extends Model
{
    use HasFactory;

    protected $table = 'ramadan_murajaah';

    protected $fillable = [
        'user_id',
        'surah_number',
        'surah_name',
        'ayah_number',
        'ayah_ar',
        'ayah_tr',
        'ayah_idn',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
