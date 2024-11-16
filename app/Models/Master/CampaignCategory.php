<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Campaign;


class CampaignCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_program_category';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function campaign()
    {
        return $this->hasMany(Campaign::class, 'category_id', 'id');
    }
}
