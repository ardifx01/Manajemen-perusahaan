<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'revenue_net_total',
        'expense_salary_total',
        'expense_other_total',
        'expense_total',
        'employee_count',
        'invoice_count',
        'barang_masuk_qty',
        'barang_keluar_qty',
        'meta',
    ];

    protected $casts = [
        'year' => 'integer',
        'revenue_net_total' => 'integer',
        'expense_salary_total' => 'integer',
        'expense_other_total' => 'integer',
        'expense_total' => 'integer',
        'employee_count' => 'integer',
        'invoice_count' => 'integer',
        'barang_masuk_qty' => 'integer',
        'barang_keluar_qty' => 'integer',
        'meta' => 'array',
    ];
}
