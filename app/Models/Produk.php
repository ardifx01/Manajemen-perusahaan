<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Nama tabel sesuai database
    protected $table = 'produks';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'harga',
        'harga_pcs',
        'harga_set',
        'satuan',
        'deskripsi',
    ];

    /**
     * Relasi ke Pos
     * Satu produk bisa digunakan di banyak surat jalan / PO
     */
    public function pos()
    {
        return $this->hasMany(Pos::class, 'produk_id');
    }
}
