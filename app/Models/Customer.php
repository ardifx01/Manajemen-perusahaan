<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'address_1',
        'address_2',
        'delivery_note_number', // Using single field instead of 3 separate fields
        'invoice_number',
        'payment_terms_days', // Default payment terms in days
    ];

    public function getDeliveryNoteNomorAttribute()
    {
        if (!$this->delivery_note_number) return null;
        $parts = explode('/', $this->delivery_note_number);
        return $parts[0] ?? null;
    }

    public function getDeliveryNotePtAttribute()
    {
        if (!$this->delivery_note_number) return null;
        $parts = explode('/', $this->delivery_note_number);
        return $parts[1] ?? null;
    }

    public function getDeliveryNoteTahunAttribute()
    {
        if (!$this->delivery_note_number) return null;
        $parts = explode('/', $this->delivery_note_number);
        return $parts[2] ?? null;
    }

    public function getInvoiceNomorAttribute()
    {
        if (!$this->invoice_number) return null;
        $parts = explode('/', $this->invoice_number);
        return $parts[0] ?? null;
    }

    public function getInvoicePtAttribute()
    {
        if (!$this->invoice_number) return null;
        $parts = explode('/', $this->invoice_number);
        return $parts[1] ?? null;
    }

    public function getInvoiceTahunAttribute()
    {
        if (!$this->invoice_number) return null;
        $parts = explode('/', $this->invoice_number);
        return $parts[2] ?? null;
    }
}
