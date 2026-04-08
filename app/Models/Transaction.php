<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Sangat penting untuk fitur Tambah/Edit Transaksi nanti.
     */
    protected $fillable = [
        'avatar',
        'name',
        'category',
        'date',
        'amount',
        'recurring',
    ];

    /**
     * Casting tipe data agar Laravel otomatis mengubah string 
     * dari SQLite menjadi tipe data yang sesuai di PHP/JSON.
     */
    protected $casts = [
        'date' => 'datetime',
        'amount' => 'float',
        'recurring' => 'boolean',
    ];
}