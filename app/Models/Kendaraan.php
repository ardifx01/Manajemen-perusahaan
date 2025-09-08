<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama model tidak jamak)
    protected $table = 'kendaraans';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'nama',
        'nama_kendaraan',
        'no_polisi',
        'jenis_kendaraan',
        'status',
    ];
}
