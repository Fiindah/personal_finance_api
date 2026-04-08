<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Dibutuhkan saat kamu membuat fitur "Add New Budget" di desain.
     */
    protected $fillable = [
        'category',
        'maximum',
        'theme',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'maximum' => 'float',
    ];
}