<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanDefaultHabit extends Model
{
    use HasFactory;

    protected $table = 'ramadan_default_habits';

    protected $fillable = [
        'name',
        'icon',
        'points',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
