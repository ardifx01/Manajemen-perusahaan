<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\POItem;
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

        $totalGajiKaryawan = Employee::where('status', 'aktif')->sum('gaji_pokok');
        $rataRataGaji = $karyawanAktif > 0 ? $totalGajiKaryawan / $karyawanAktif : 0;

        // Pendapatan bulanan (sum total item PO bulan ini + PPN 11%)
        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();
        $monthlySubtotal = POItem::whereHas('po', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_po', [$start, $end]);
            })
            ->sum('total');
        $monthlyPpn = (int) round($monthlySubtotal * 0.11);
        $monthlyRevenue = (int) ($monthlySubtotal + $monthlyPpn);

        // Laporan pendapatan per perusahaan (customer) - NET (tanpa PPN)
        $revenueByCustomer = POItem::select('pos.customer',
                DB::raw('COUNT(DISTINCT po_items.po_id) as orders'),
                DB::raw('SUM(po_items.total) as subtotal')
            )
            ->join('pos', 'po_items.po_id', '=', 'pos.id')
            ->whereBetween('pos.tanggal_po', [$start, $end])
            ->groupBy('pos.customer')
            ->orderByDesc('subtotal')
            ->get();

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
            'revenueByCustomer'
        ));
    }
}
