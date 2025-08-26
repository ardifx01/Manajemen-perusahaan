<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengirim extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pengirim';

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi ke tabel PO
     * Satu pengirim bisa punya banyak PO
     */
    public function pos()
    {
        return $this->hasMany(PO::class, 'pengirim', 'nama');
    }

    /**
     * Relasi ke tabel Surat Jalan
     * (opsional, kalau memang di surat_jalan ada kolom pengirim)
     */
    public function suratJalans()
    {
        return $this->hasMany(SuratJalan::class, 'pengirim', 'nama');
    }
}
