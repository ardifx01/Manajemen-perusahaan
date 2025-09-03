<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\AnnualSummary;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;

class GenerateAnnualSummary extends Command
{
    protected $signature = 'summary:generate-annual {year? : Tahun yang akan direkap, default tahun lalu}';

    protected $description = 'Hitung dan simpan rekap tahunan untuk dashboard';

    public function handle(): int
    {
        $year = (int) ($this->argument('year') ?: Carbon::now()->subYear()->year);

        // Pastikan hanya merekap jika tahun sudah selesai (lebih kecil dari tahun berjalan)
        $currentYear = (int) Carbon::now()->format('Y');
        if ($year >= $currentYear) {
            $this->warn("Tahun $year belum selesai. Lewati.");
            return self::SUCCESS;
        }

        // Jika sudah ada, update saja (idempotent)
        DB::beginTransaction();
        try {
            // Revenue neto: gunakan field 'total' pada Invoice (diasumsikan net sebelum PPN)
            $revenueNet = (int) Invoice::whereYear('tanggal_invoice', $year)->sum('total');
            $invoiceCount = (int) Invoice::whereYear('tanggal_invoice', $year)->count();

            // Expense: gaji + expense lainnya
            $expenseSalary = (int) Salary::where('tahun', $year)->sum('total_gaji');
            $expenseOther = (int) Expense::whereYear('tanggal', $year)->sum('amount');
            $expenseTotal = $expenseSalary + $expenseOther;

            // Stok movement
            $barangMasukQty = (int) BarangMasuk::whereYear('tanggal', $year)->sum('qty');
            $barangKeluarQty = (int) BarangKeluar::whereYear('tanggal', $year)->sum('qty');

            // Employee count (snapshot sederhana: jumlah record saat ini)
            $employeeCount = (int) Employee::count();

            $payload = [
                'year' => $year,
                'revenue_net_total' => $revenueNet,
                'expense_salary_total' => $expenseSalary,
                'expense_other_total' => $expenseOther,
                'expense_total' => $expenseTotal,
                'employee_count' => $employeeCount,
                'invoice_count' => $invoiceCount,
                'barang_masuk_qty' => $barangMasukQty,
                'barang_keluar_qty' => $barangKeluarQty,
                'meta' => [
                    'generated_at' => Carbon::now()->toDateTimeString(),
                    'source' => 'artisan:summary:generate-annual',
                ],
            ];

            // Upsert by year
            AnnualSummary::updateOrCreate(['year' => $year], $payload);

            DB::commit();

            $this->info("Rekap tahunan untuk tahun $year berhasil dibuat/diperbarui.");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal generate annual summary', [
                'year' => $year,
                'error' => $e->getMessage(),
            ]);
            $this->error('Terjadi kesalahan saat membuat rekap tahunan: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
