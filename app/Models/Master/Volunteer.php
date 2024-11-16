<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\ProgramVolunteer;
use App\Models\Master\Group;
use App\Models\Transaction\Donation;
use App\Models\User;


class Volunteer extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'm_volunteer';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function program()
    {
        return $this->belongsTo(ProgramVolunteer::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'volunteer_id');
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
    public function donationList()
    {
        return $this->hasMany(Donation::class, 'volunteer_id', 'id');
    }
}
