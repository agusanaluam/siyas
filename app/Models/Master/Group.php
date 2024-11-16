<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Volunteer;


class Group extends Model
{
    use HasFactory;

    protected $table = 'm_group';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $fillable = [
        'name',
        'description'
    ];

    public function volunteer()
    {
        return $this->hasMany(Volunteer::class, 'group_id', 'id');
    }
}
