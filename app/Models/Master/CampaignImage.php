<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CampaignImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_program_image';

    protected $fillable = [
        'program_id',
        'picture_path'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

}
