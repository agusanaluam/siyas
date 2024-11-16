<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Program;
use App\Models\Master\Volunteer;



class ProgramVolunteer extends Model
{
    use HasFactory;

    protected $table = 'm_program_volunteer';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class);
    }
}
