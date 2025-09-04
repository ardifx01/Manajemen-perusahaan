<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Invoice;
use App\Models\POItem;
use App\Models\PO;
use App\Models\Salary;
use App\Models\Expense;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Karyawan
        $totalKaryawan = Employee::count();
        $karyawanAktif = Employee::where('status', 'aktif')->count();
        $karyawanBaru = 0; // Tidak pakai filter tanggal, set manual ke 0 atau sesuai logika lain

        // Filter terpisah:
        // - Pengeluaran: month/year
        // - Pendapatan: inc_month/inc_year
        $bulanNow = (int) request()->get('month', (int) Carbon::now()->format('n'));
        $tahunNow = (int) request()->get('year', (int) Carbon::now()->format('Y'));
        $incMonth = (int) request()->get('inc_month', (int) Carbon::now()->format('n'));
        $incYear  = (int) request()->get('inc_year', (int) Carbon::now()->format('Y'));

        // Hormati tahun yang dipilih user; tidak ada pemaksaan ke tahun tertentu

        // Total Invoice (diambil dari Surat Jalan/PO) sesuai bulan & tahun terpilih
        // Menggunakan field tanggal_po pada tabel PO
        $invoiceCount = (int) PO::whereYear('tanggal_po', $tahunNow)
            ->whereMonth('tanggal_po', $bulanNow)
            ->count();

        $totalGajiKaryawan = Employee::where('status', 'aktif')->sum('gaji_pokok');
        $rataRataGaji = $karyawanAktif > 0 ? $totalGajiKaryawan / $karyawanAktif : 0;

        // Pendapatan bulanan (sum total item PO bulan incMonth/incYear + PPN 11%)
        $start = Carbon::create($incYear, $incMonth, 1)->startOfMonth();
        $end   = (clone $start)->endOfMonth();
        $monthlySubtotal = POItem::whereHas('po', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_po', [$start, $end]);
            })
            ->sum('total');
        $monthlyPpn = (int) round($monthlySubtotal * 0.11);
        $monthlyRevenue = (int) ($monthlySubtotal + $monthlyPpn);

        // Laporan pendapatan per perusahaan (customer) - NET (tanpa PPN)
        // Pilihan filter untuk pendapatan pakai incMonth/incYear di atas

        $revenueByCustomer = POItem::select('pos.customer',
                DB::raw('COUNT(DISTINCT po_items.po_id) as orders'),
                DB::raw('SUM(po_items.total) as subtotal')
            )
            ->join('pos', 'po_items.po_id', '=', 'pos.id')
            ->whereBetween('pos.tanggal_po', [$start, $end])
            ->groupBy('pos.customer')
            ->orderByDesc('subtotal')
            ->get();

        // Pendapatan Tahunan (Net per bulan untuk incYear berjalan)
        $revenueNetByMonthRaw = POItem::join('pos', 'po_items.po_id', '=', 'pos.id')
            ->whereYear('pos.tanggal_po', $incYear)
            ->selectRaw('MONTH(pos.tanggal_po) as bulan, SUM(po_items.total) as subtotal')
            ->groupBy('bulan')
            ->pluck('subtotal', 'bulan');
        $revenueNetByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueNetByMonth[$m] = (int) ($revenueNetByMonthRaw[$m] ?? 0);
        }

        // Detail pendapatan per perusahaan per bulan (untuk incYear)
        $revenueByCustomerByMonthRows = POItem::join('pos', 'po_items.po_id', '=', 'pos.id')
            ->whereYear('pos.tanggal_po', $incYear)
            ->selectRaw('MONTH(pos.tanggal_po) as bulan, pos.customer as customer, SUM(po_items.total) as subtotal')
            ->groupBy('bulan', 'pos.customer')
            ->orderBy('bulan')
            ->orderByDesc('subtotal')
            ->get();
        $revenueByCustomerByMonth = [];
        foreach ($revenueByCustomerByMonthRows as $row) {
            $b = (int) ($row->bulan ?? 0);
            if (!isset($revenueByCustomerByMonth[$b])) $revenueByCustomerByMonth[$b] = [];
            $revenueByCustomerByMonth[$b][] = [
                'customer' => $row->customer ?? '-',
                'subtotal' => (int) ($row->subtotal ?? 0),
            ];
        }

        // Pengeluaran: Gaji karyawan (pakai bulanNow/tahunNow)

        // Bulanan: total gaji per karyawan pada bulan/tahun berjalan
        $salaryByEmployee = Salary::with(['employee:id,nama_karyawan'])
            ->where('bulan', $bulanNow)
            ->where('tahun', $tahunNow)
            ->selectRaw('employee_id, SUM(total_gaji) as salary')
            ->groupBy('employee_id')
            ->get()
            ->map(function ($row) {
                return [
                    'employee' => $row->employee->nama_karyawan ?? '-',
                    'salary'   => (int) ($row->salary ?? 0),
                ];
            });

        $monthlySalaryTotal = (int) Salary::where('bulan', $bulanNow)
            ->where('tahun', $tahunNow)
            ->sum('total_gaji');

        // Hilangkan fallback: jika tidak ada Salary bulan ini, biarkan 0 dan daftar karyawan kosong

        // Pengeluaran Lain (Custom Expenses)
        $otherExpensesMonthly = Expense::whereMonth('tanggal', $bulanNow)
            ->whereYear('tanggal', $tahunNow)
            ->orderByDesc('tanggal')
            ->get();
        $monthlyOtherExpenseTotal = (int) Expense::whereMonth('tanggal', $bulanNow)
            ->whereYear('tanggal', $tahunNow)
            ->sum('amount');

        $expensesByMonthRaw = Expense::whereYear('tanggal', $tahunNow)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(amount) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');
        $expensesByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $expensesByMonth[$m] = (int) ($expensesByMonthRaw[$m] ?? 0);
        }
        $yearlyOtherExpenseTotal = array_sum($expensesByMonth);

        // Tahunan: total gaji per bulan untuk tahun berjalan
        $salaryByMonthRaw = Salary::where('tahun', $tahunNow)
            ->selectRaw('bulan, SUM(total_gaji) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan'); // [1=>..., 2=>...]

        $salaryByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $salaryByMonth[$m] = (int) ($salaryByMonthRaw[$m] ?? 0);
        }
        $yearlySalaryTotal = array_sum($salaryByMonth);

        // Hilangkan fallback tahunan: bila tidak ada data, biarkan 0 untuk setiap bulan

        // Total gabungan pengeluaran (dihitung setelah yearlySalaryTotal tersedia)
        $combinedMonthlyExpense = (int) ($monthlySalaryTotal + $monthlyOtherExpenseTotal);
        $combinedYearlyExpenseTotal = (int) ($yearlySalaryTotal + $yearlyOtherExpenseTotal);

        // Barang Masuk & Barang Keluar - total unit bulan berjalan (berdasarkan field tanggal)
        $barangMasukMonthlyQty = (int) BarangMasuk::whereMonth('tanggal', $bulanNow)
            ->whereYear('tanggal', $tahunNow)
            ->sum('qty');
        $barangKeluarMonthlyQty = (int) BarangKeluar::whereMonth('tanggal', $bulanNow)
            ->whereYear('tanggal', $tahunNow)
            ->sum('qty');

        // Daftar tahun untuk selector (2025-2030 sebagai default, plus tahun sekarang jika di luar range)
        $currentYear = (int) Carbon::now()->format('Y');
        $defaultYears = range(2025, 2030);
        
        // Tambahkan tahun sekarang jika tidak dalam range 2025-2030
        if ($currentYear < 2025 || $currentYear > 2030) {
            $defaultYears[] = $currentYear;
            sort($defaultYears);
        }
        
        $availableYears = $defaultYears;
        
        // Daftar semua tahun untuk modal (dari 2020 sampai 2035)
        $allYears = range(2020, 2035);

        // Data untuk Chart (tanpa filter tanggal)
        $chartData = [
            'karyawan' => [],
            'gaji' => []
        ];
        $chartLabels = [];

        // Kalau mau tetap bikin chart 6 bulan terakhir tapi tanpa tanggal bergabung,
        // kita cukup isi data statis berdasarkan semua karyawan aktif.
        for ($i = 5; $i >= 0; $i--) {
            $chartLabels[] = now()->subMonths($i)->format('M');

            $jumlahKaryawan = Employee::where('status', 'aktif')->count();
            $totalGajiBulan = Employee::where('status', 'aktif')->sum('gaji_pokok');

            $chartData['karyawan'][] = $jumlahKaryawan;
            $chartData['gaji'][] = round($totalGajiBulan / 1000000, 1); // Dalam jutaan
        }

        return view('dashboard.index', compact(
            'totalKaryawan',
            'karyawanAktif',
            'karyawanBaru',
            'totalGajiKaryawan',
            'rataRataGaji',
            'chartData',
            'chartLabels',
            'monthlyRevenue',
            'monthlySubtotal',
            'monthlyPpn',
            'revenueByCustomer',
            'revenueNetByMonth',
            'bulanNow',
            'tahunNow',
            'incMonth',
            'incYear',
            'revenueByCustomerByMonth',
            // pengeluaran (gaji)
            'salaryByEmployee',
            'monthlySalaryTotal',
            'salaryByMonth',
            'yearlySalaryTotal',
            // pengeluaran lain
            'otherExpensesMonthly',
            'monthlyOtherExpenseTotal',
            'expensesByMonth',
            'yearlyOtherExpenseTotal',
            // gabungan
            'combinedMonthlyExpense',
            'combinedYearlyExpenseTotal',
            // total invoice untuk kartu
            'invoiceCount',
            // barang masuk/keluar bulan ini
            'barangMasukMonthlyQty',
            'barangKeluarMonthlyQty',
            // selector tahun
            'availableYears',
            'allYears'
        ));
    }
}
