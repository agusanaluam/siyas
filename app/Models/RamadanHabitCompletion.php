<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanHabitCompletion extends Model
{
    use HasFactory;

    protected $table = 'ramadan_habit_completions';

    protected $fillable = [
        'habit_id',
        'user_id',
        'completed_date',
    ];

    protected function casts(): array
    {
        return [
            'completed_date' => 'date',
        ];
    }

    public function habit()
    {
        return $this->belongsTo(RamadanHabit::class, 'habit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
