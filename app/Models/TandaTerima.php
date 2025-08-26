<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TandaTerima extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_tanda_terima',
        'no_po',
        'no_surat_jalan',
        'customer',
        'alamat_1',
        'alamat_2',
        'tanggal_terima',
        'produk_id',
        'qty_dikirim',
        'qty_diterima',
        'qty_jenis',
        'kondisi_barang',
        'status',
        'penerima_nama',
        'penerima_jabatan',
        'catatan',
        'foto_bukti'
    ];

    protected $casts = [
        'tanggal_terima' => 'date',
    ];

    public function produkRel()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
