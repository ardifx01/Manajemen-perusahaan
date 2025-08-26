<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

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
            'chartLabels'
        ));
    }
}
