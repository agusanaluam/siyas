<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\CampaignCategory;
use App\Models\Transaction\DonationDetail;

class Campaign extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'm_program';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(CampaignCategory::class);
    }

    public function image()
    {
        return $this->hasMany(CampaignImage::class, 'program_id', 'id');
    }

    public function donationDetail()
    {
        return $this->belongsTo(DonationDetail::class);
    }
}
