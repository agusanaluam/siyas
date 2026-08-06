<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanHabit extends Model
{
    use HasFactory;

    protected $table = 'ramadan_habits';

    protected $fillable = [
        'user_id',
        'name',
        'icon',
        'points',
        'type',
        'sort_order',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function completions()
    {
        return $this->hasMany(RamadanHabitCompletion::class, 'habit_id');
    }
}
