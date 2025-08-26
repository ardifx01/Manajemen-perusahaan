<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JatuhTempo extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'no_po',
        'customer',
        'tanggal_invoice',
        'tanggal_jatuh_tempo',
        'jumlah_tagihan',
        'jumlah_terbayar',
        'sisa_tagihan',
        'status_pembayaran',
        'hari_terlambat',
        'denda',
        'catatan',
        'reminder_sent',
        'last_reminder_date'
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'last_reminder_date' => 'date',
        'reminder_sent' => 'boolean',
    ];

    // Accessor untuk menghitung hari terlambat
    public function getHariTerlambatAttribute()
    {
        if ($this->status_pembayaran === 'Lunas') {
            return 0;
        }
        
        $today = Carbon::now();
        $dueDate = Carbon::parse($this->tanggal_jatuh_tempo);
        
        return $today->gt($dueDate) ? $today->diffInDays($dueDate) : 0;
    }

    // Accessor untuk status overdue
    public function getIsOverdueAttribute()
    {
        return $this->hari_terlambat > 0 && $this->status_pembayaran !== 'Lunas';
    }

    // Accessor untuk warna status
    public function getStatusColorAttribute()
    {
        switch ($this->status_pembayaran) {
            case 'Lunas':
                return 'green';
            case 'Sebagian':
                return 'yellow';
            case 'Belum Bayar':
                return $this->is_overdue ? 'red' : 'gray';
            default:
                return 'gray';
        }
    }
}
