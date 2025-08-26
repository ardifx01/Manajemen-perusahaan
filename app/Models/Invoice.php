<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'no_po',
        'customer',
        'alamat_1',
        'alamat_2',
        'tanggal_invoice',
        'tanggal_jatuh_tempo',
        'produk_id',
        'qty',
        'qty_jenis',
        'harga',
        'total',
        'pajak',
        'grand_total',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function produkRel()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
