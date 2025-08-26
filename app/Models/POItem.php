<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POItem extends Model
{
    use HasFactory;

    protected $table = 'po_items';

    protected $fillable = [
        'po_id',
        'produk_id',
        'qty',
        'qty_jenis',
        'harga',
        'total',
    ];

    public function po()
    {
        return $this->belongsTo(PO::class, 'po_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
