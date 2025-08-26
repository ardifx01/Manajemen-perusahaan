<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratJalan;
use App\Models\PO;
use App\Models\Produk;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\DB;
use App\Exports\SuratJalanExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    /**
     * Menampilkan daftar Surat Jalan.
     */
    public function index()
    {
        // PERBAIKAN: Hilangkan constraint yang menyebabkan relasi return true
        $suratjalan = PO::with(['produkRel', 'kendaraanRel'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($po) {
                // Filter out records with invalid relationships
                return $po->produkRel !== null;
            });

        // Ambil semua produk untuk dropdown
        $produk = Produk::all();

        // PERBAIKAN: Hilangkan constraint yang bermasalah
        $pos = PO::with(['produkRel', 'kendaraanRel'])
            ->whereHas('produkRel') // Only get POs that have valid produk relationship
            ->orderBy('created_at', 'desc')
            ->get();

        $kendaraans = Kendaraan::all();

        // CHANGE: Tambahkan data pengirim untuk dropdown
        $pengirims = DB::table('pos')
            ->select('pengirim as nama')
            ->whereNotNull('pengirim')
            ->where('pengirim', '!=', '')
            ->distinct()
            ->orderBy('pengirim', 'asc')
            ->get();

        return view('suratjalan.index', [
            'suratjalan' => $suratjalan,
            'produk'     => $produk,
            'pos'        => $pos,
            'kendaraans' => $kendaraans,
            'pengirims'  => $pengirims // CHANGE: Tambahkan pengirims ke view
        ]);
    }

    /**
     * Menyimpan Surat Jalan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_po'    => 'required|date',
            'customer'      => 'required|string',
            'no_surat_jalan'=> 'required|string',
            'no_po'         => 'required|string',
            'kendaraan'     => 'nullable|string',
            'no_polisi'     => 'required|string',
            'qty'           => 'required|integer',
            'qty_jenis'     => 'required|string',
            'produk_id'     => 'required|exists:produks,id',
            'total'         => 'required|numeric',
            'alamat_1'      => 'required|string|max:500',
            'alamat_2'      => 'nullable|string|max:500',
            'pengirim'      => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'tanggal_po', 'customer', 'no_surat_jalan', 'no_po', 'kendaraan',
            'no_polisi', 'qty', 'qty_jenis', 'produk_id', 'total', 'alamat_1', 'alamat_2',
            'pengirim'
        ]);

        // PERBAIKAN: Bersihkan data kendaraan yang invalid
        if (isset($data['kendaraan'])) {
            if ($data['kendaraan'] === '0' || $data['kendaraan'] === '' || $data['kendaraan'] === 'tes' || empty($data['kendaraan'])) {
                $data['kendaraan'] = null;
            } elseif (is_numeric($data['kendaraan']) && (int)$data['kendaraan'] === 0) {
                $data['kendaraan'] = null;
            }
        }

        if (empty($data['alamat_1']) || $data['alamat_1'] === '-' || trim($data['alamat_1']) === '') {
            return redirect()->back()
                ->withErrors(['alamat_1' => 'Alamat 1 harus diisi dan tidak boleh kosong'])
                ->withInput();
        }

        PO::create($data);

        return redirect()->route('suratjalan.index')
            ->with('success', 'Surat jalan berhasil ditambahkan');
    }

    /**
     * Mengupdate Surat Jalan.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_po'    => 'required|date',
            'customer'      => 'required|string',
            'no_surat_jalan'=> 'required|string',
            'no_po'         => 'required|string',
            'kendaraan'     => 'nullable|string',
            'no_polisi'     => 'required|string',
            'qty'           => 'required|integer',
            'qty_jenis'     => 'required|string',
            'produk_id'     => 'required|exists:produks,id',
            'total'         => 'required|numeric',
            'alamat_1'      => 'required|string|max:500',
            'alamat_2'      => 'nullable|string|max:500',
            'pengirim'      => 'nullable|string|max:255',
        ]);

        $suratJalan = PO::findOrFail($id);
        
        $data = $request->only([
            'tanggal_po', 'customer', 'no_surat_jalan', 'no_po', 'kendaraan',
            'no_polisi', 'qty', 'qty_jenis', 'produk_id', 'total', 'alamat_1', 'alamat_2',
            'pengirim'
        ]);

        // PERBAIKAN: Bersihkan data kendaraan yang invalid
        if (isset($data['kendaraan'])) {
            if ($data['kendaraan'] === '0' || $data['kendaraan'] === '' || $data['kendaraan'] === 'tes' || empty($data['kendaraan'])) {
                $data['kendaraan'] = null;
            } elseif (is_numeric($data['kendaraan']) && (int)$data['kendaraan'] === 0) {
                $data['kendaraan'] = null;
            }
        }

        if (empty($data['alamat_1']) || $data['alamat_1'] === '-' || trim($data['alamat_1']) === '') {
            return redirect()->back()
                ->withErrors(['alamat_1' => 'Alamat 1 harus diisi dan tidak boleh kosong'])
                ->withInput();
        }

        $suratJalan->update($data);

        return redirect()->route('suratjalan.index')
            ->with('success', 'Surat jalan berhasil diupdate');
    }

    /**
     * Menghapus Surat Jalan.
     */
    public function destroy($id)
    {
        try {
            $suratJalan = PO::findOrFail($id);
            $suratJalan->delete();

            return redirect()->route('suratjalan.index')
                ->with('success', 'Surat jalan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('suratjalan.index')
                ->with('error', 'Gagal menghapus surat jalan: ' . $e->getMessage());
        }
    }

    /**
     * Export Surat Jalan ke Excel (1 baris per item)
     */
    public function export(Request $request)
    {
        try {
            Carbon::setLocale('id');

            // selected_ids dikirim sebagai JSON string dari form hidden di view
            // Jika kosong, ekspor semua data.
            $ids = null;
            if ($request->has('selected_ids')) {
                $decoded = json_decode($request->input('selected_ids'), true);
                if (is_array($decoded) && !empty($decoded)) {
                    $ids = $decoded;
                }
            }

            $fileName = ($ids ? 'Surat_Jalan_Selected_' : 'Surat_Jalan_All_') . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new SuratJalanExport($ids), $fileName);
        } catch (\Exception $e) {
            \Log::error('Surat Jalan Export Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil data invoice berdasarkan ID yang dipilih
     */
    public function getInvoiceData(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['data' => []]);
        }

        // Ambil data PO beserta detail item dan produk agar invoice bisa menampilkan multi-produk
        $suratjalan = \App\Models\PO::with(['items.produk'])
            ->whereIn('id', $ids)
            ->get();

        return response()->json(['data' => $suratjalan]);
    }

    /**
     * Generate Invoice PDF (opsional untuk future development)
     */
    public function generateInvoicePDF(Request $request)
    {
        try {
            $raw = $request->input('selected_ids');
            // selected_ids dikirim sebagai JSON string dari front-end
            $selectedIds = is_array($raw) ? $raw : json_decode($raw, true);

            if (empty($selectedIds) || !is_array($selectedIds)) {
                return back()->with('error', 'Tidak ada data yang dipilih untuk invoice');
            }

            // Ambil PO dengan detail items dan produk untuk invoice multi-produk
            $pos = PO::with(['items.produk'])
                ->whereIn('id', $selectedIds)
                ->get();

            if ($pos->isEmpty()) {
                return back()->with('error', 'Data tidak ditemukan');
            }

            // Flatten items
            $allItems = [];
            foreach ($pos as $po) {
                foreach (($po->items ?? []) as $item) {
                    $allItems[] = $item;
                }
            }

            // Hitung totals
            $subtotal = collect($allItems)->sum(function ($it) {
                return (int) ($it->total ?? 0);
            });
            $totalQty = collect($allItems)->sum(function ($it) {
                return (int) ($it->qty ?? 0);
            });
            $ppn = (int) round($subtotal * 0.11);
            $grandTotal = $subtotal + $ppn;

            // Nomor invoice: gunakan no_invoice dari PO jika tersedia; fallback ke format lama
            $today = Carbon::now();
            $firstPo = $pos->first();
            $invoiceNo = trim((string)($firstPo->no_invoice ?? ''));
            if ($invoiceNo === '') {
                $invoiceNo = rand(1000, 9999) . ' / CAM-GM / ' . $today->month . ' / ' . $today->format('y');
            }

            $invoiceDetails = [
                'invoice_no' => $invoiceNo,
                'invoice_date' => $today->locale('id')->translatedFormat('d F Y'),
                'customer' => $firstPo->customer,
                'address' => trim(($firstPo->alamat_1 ?? '') . ' ' . ($firstPo->alamat_2 ?? '')),
                'no_po' => $firstPo->no_po ?? '-',
                'no_surat_jalan' => $firstPo->no_surat_jalan ?? '-',
                'items' => $allItems,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'grand_total' => $grandTotal,
                'total_qty' => $totalQty,
                'date_location' => $today->locale('id')->translatedFormat('d F Y'),
            ];

            // Hanya download PDF; tidak ada fallback HTML
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return back()->with('error', 'DomPDF belum terpasang. Jalankan: composer require barryvdh/laravel-dompdf');
            }

            $forPdf = true;
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoice.pdf', compact('invoiceDetails', 'forPdf'))
                ->setPaper('a4', 'portrait');
            $fileName = 'Invoice_'.now()->format('Ymd_His').'.pdf';
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            \Log::error('Invoice PDF Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat generate invoice: ' . $e->getMessage());
        }
    }
}
