<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $casts = [
        'about_service' => 'array',
    ];

    // Jika tabel settings hanya menyimpan satu record (singleton)
    public static function getSettings()
    {
        return static::first() ?? new static();
    }
}

