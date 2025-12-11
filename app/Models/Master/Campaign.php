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

    /**
     * Determine campaign status based on dates
     * 1 = Pending (before start_date)
     * 2 = Running (between start_date and end_date)
     * 3 = Closed (after end_date)
     */
    public static function determineStatus($startDate, $endDate)
    {
        $now = now();
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        if ($now->lt($start)) {
            return 1; // Pending
        } elseif ($now->between($start, $end)) {
            return 2; // Running
        } else {
            return 3; // Closed
        }
    }

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
