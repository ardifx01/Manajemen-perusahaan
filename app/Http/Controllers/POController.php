<?php

namespace App\Http\Controllers;

use App\Models\PO;
use App\Models\POItem;
use App\Models\SuratJalan;
use App\Models\Kendaraan;
use App\Models\Produk;
use App\Models\Customer;
use App\Models\Pengirim; // Tambahkan model Pengirim
use App\Models\JatuhTempo; // Sinkronisasi Jatuh Tempo dari PO
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class POController extends Controller
{
    public function index()
    {
        // Ambil semua PO beserta relasi items->produk dan kendaraan
        $pos        = PO::with(['items.produk', 'kendaraanRel'])->latest()->get();
        $kendaraans = Kendaraan::all();
        $produks    = Produk::all();
        $customers  = Customer::all();
        
        // CHANGE: Ambil data pengirim dari tabel pengirim
        $pengirims = Pengirim::select('nama')
            ->whereNotNull('nama')
            ->where('nama', '!=', '')
            ->orderBy('nama', 'asc')
            ->get();

        return view('dashboard.po_index', compact('pos', 'kendaraans', 'produks', 'customers', 'pengirims'));
    }

    public function create()
    {
        $kendaraans = Kendaraan::all();
        $produks    = Produk::all();
        $customers  = Customer::all();
        
        // CHANGE: Ambil data pengirim dari tabel pengirim
        $pengirims = Pengirim::select('nama')
            ->whereNotNull('nama')
            ->where('nama', '!=', '')
            ->orderBy('nama', 'asc')
            ->get();

        // Optional: tampilkan juga daftar PO seperti di index agar view konsisten
        $pos = PO::with(['items.produk', 'kendaraanRel'])->latest()->get();

        return view('dashboard.po_index', compact('pos', 'kendaraans', 'produks', 'customers', 'pengirims'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_surat_jalan_nomor' => 'required|string',
            'no_surat_jalan_pt'    => 'required|string',
            'no_surat_jalan_tahun' => 'required|integer',
            'no_po'                => 'required|string',
            'no_invoice_nomor'     => 'nullable|string|max:255',
            'no_invoice_pt'        => 'nullable|string|max:255',
            'no_invoice_tahun'     => 'nullable|integer',
            'customer_id'          => 'required|exists:customers,id',
            'tanggal_po'           => 'required|date',
            'kendaraan'            => 'required|string',
            'no_polisi'            => 'required|string',
            'address_1'            => 'required|string|max:255',
            'address_2'            => 'nullable|string|max:255',
            'pengirim'             => 'nullable|string|max:255',
            'items'                => 'required|array|min:1',
            'items.*.produk_id'    => 'required|exists:produks,id',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.qty_jenis'    => 'required|in:PCS,SET',
            'items.*.harga'        => 'nullable|integer|min:0',
            'items.*.total'        => 'nullable|integer|min:0',
        ]);

        // Gabungkan nomor surat jalan
        $noSuratJalan = "{$data['no_surat_jalan_nomor']}/{$data['no_surat_jalan_pt']}/{$data['no_surat_jalan_tahun']}";

        // Ambil nama customer dari customer_id
        $customer = Customer::find($data['customer_id']);
        $customerName = $customer ? $customer->name : '';

        if (empty($data['address_1']) || $data['address_1'] === '-') {
            $data['address_1'] = $customer->address_1 ?? '';
        }
        if (empty($data['address_2']) || $data['address_2'] === '-') {
            $data['address_2'] = $customer->address_2 ?? '';
        }

        // Bentuk no_invoice jika ada inputnya
        $noInvoice = null;
        if (!empty($data['no_invoice_nomor']) || !empty($data['no_invoice_pt']) || !empty($data['no_invoice_tahun'])) {
            $nom = $data['no_invoice_nomor'] ?? '';
            $pt  = $data['no_invoice_pt'] ?? '';
            $thn = $data['no_invoice_tahun'] ?? '';
            $noInvoice = trim($nom) . '/' . trim($pt) . '/' . trim((string) $thn);
        }

        // Simpan ke database PO (header) + items dalam transaksi
        $po = DB::transaction(function () use ($data, $customerName, $noSuratJalan, $noInvoice) {
            $rawItems = collect($data['items'])
                ->filter(fn ($it) => !empty($it['produk_id']) && !empty($it['qty']))
                ->values();

            // Recompute harga & total server-side for each item
            $items = $rawItems->map(function ($it) {
                $produk = Produk::find($it['produk_id']);
                $qtyJenis = strtoupper($it['qty_jenis'] ?? 'PCS');
                $harga = 0;
                if ($produk) {
                    $harga = $qtyJenis === 'SET' ? (int) ($produk->harga_set ?? 0) : (int) ($produk->harga_pcs ?? 0);
                }
                $qty = (int) ($it['qty'] ?? 0);
                $total = $harga * $qty;
                return [
                    'produk_id' => (int) $it['produk_id'],
                    'qty'       => $qty,
                    'qty_jenis' => $qtyJenis,
                    'harga'     => $harga,
                    'total'     => $total,
                ];
            });

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal 1 item dengan produk dan qty >= 1.',
                ]);
            }

            // Header mengambil item pertama untuk kolom legacy
            $first = $items->first();
            $sumTotal = (int) $items->sum(fn ($it) => (int) ($it['total'] ?? 0));

            $po = PO::create([
                'no_surat_jalan' => $noSuratJalan,
                'no_po'          => $data['no_po'],
                'no_invoice'     => $noInvoice,
                'customer_id'    => $data['customer_id'],
                'customer'       => $customerName,
                'tanggal_po'     => $data['tanggal_po'],
                'produk_id'      => $first['produk_id'] ?? null,
                'qty'            => $first['qty'] ?? 0,
                'qty_jenis'      => $first['qty_jenis'] ?? 'PCS',
                'harga'          => $first['harga'] ?? 0,
                'total'          => $sumTotal,
                'kendaraan'      => $data['kendaraan'],
                'no_polisi'      => $data['no_polisi'],
                'alamat_1'       => $data['address_1'],
                'alamat_2'       => $data['address_2'] ?? null,
                'pengirim'       => $data['pengirim'] ?? null,
            ]);

            foreach ($items as $it) {
                POItem::create([
                    'po_id'     => $po->id,
                    'produk_id' => $it['produk_id'],
                    'qty'       => $it['qty'],
                    'qty_jenis' => $it['qty_jenis'],
                    'harga'     => $it['harga'],
                    'total'     => $it['total'],
                ]);
            }

            return $po;
        });

        // === Ekspor ke Excel ===
        $templatePath = storage_path('app/template/SJ CAM & OUTSTANDING 2024.xlsm');
        if (file_exists($templatePath)) {
            $spreadsheet  = IOFactory::load($templatePath);
            $sheet        = $spreadsheet->getActiveSheet();
            $row = 10; // baris awal tulis data
            foreach ($po->items()->with('produk')->get() as $item) {
                $sheet->setCellValue("A{$row}", $noSuratJalan);
                $sheet->setCellValue("B{$row}", $po->no_po);
                $sheet->setCellValue("C{$row}", $po->customer);
                $sheet->setCellValue("D{$row}", date('d/m/Y', strtotime($po->tanggal_po)));
                $sheet->setCellValue("E{$row}", $item->produk->nama_produk ?? 'Produk Tidak Ditemukan');
                $sheet->setCellValue("F{$row}", $item->qty . ' ' . $item->qty_jenis);
                $sheet->setCellValue("G{$row}", $item->harga);
                $sheet->setCellValue("H{$row}", $item->total);
                $sheet->setCellValue("I{$row}", ($po->kendaraan ?? '-') . ' / ' . ($po->no_polisi ?? '-'));
                $sheet->setCellValue("J{$row}", ($po->alamat_1 ?? '-') . ' ' . ($po->alamat_2 ?? ''));
                $row++;
            }

            $filename   = 'PO_' . now()->format('Ymd_His') . '.xlsm';
            $exportPath = storage_path('app/public/exports/');

            if (!file_exists($exportPath)) {
                mkdir($exportPath, 0777, true);
            }

            $savePath = $exportPath . $filename;
            IOFactory::createWriter($spreadsheet, 'Xls')->save($savePath);
        }

        // === Sinkronisasi ke Jatuh Tempo ===
        try {
            $invoiceKey = $noInvoice ?: $noSuratJalan; // gunakan invoice jika ada, fallback ke no_surat_jalan (unik)
            $tanggalInvoice = \Carbon\Carbon::parse($po->tanggal_po);
            // Gunakan payment_terms_days dari customer jika tersedia, fallback +1 bulan
            $termsDays = (int) (($customer->payment_terms_days ?? 0));
            if ($termsDays > 0) {
                $tanggalJatuhTempo = (clone $tanggalInvoice)->addDays($termsDays);
            } else {
                $tanggalJatuhTempo = (clone $tanggalInvoice)->addMonth();
            }

            JatuhTempo::updateOrCreate(
                ['no_invoice' => $invoiceKey],
                [
                    'no_po' => $po->no_po,
                    'customer' => $po->customer,
                    'tanggal_invoice' => $tanggalInvoice->format('Y-m-d'),
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                    'jumlah_tagihan' => (int) ($po->total ?? 0),
                    'jumlah_terbayar' => 0,
                    'sisa_tagihan' => (int) ($po->total ?? 0),
                    'status_pembayaran' => 'Belum Bayar',
                    'status_approval' => 'Pending',
                ]
            );
        } catch (\Throwable $e) {
            \Log::warning('Sync JatuhTempo gagal: ' . $e->getMessage());
        }

        return redirect()->route('po.index')->with('success', 'Data PO berhasil disimpan dan diekspor.');
    }

    public function edit($id)
    {
        $po         = PO::findOrFail($id);
        $pos        = PO::with(['produkRel', 'kendaraanRel'])->latest()->get();
        $kendaraans = Kendaraan::all();
        $produks    = Produk::all();
        $customers  = Customer::all();
        
        // CHANGE: Ambil data pengirim dari tabel pengirim
        $pengirims = Pengirim::select('nama')
            ->whereNotNull('nama')
            ->where('nama', '!=', '')
            ->orderBy('nama', 'asc')
            ->get();

        return view('dashboard.po_index', compact('po', 'pos', 'kendaraans', 'produks', 'customers', 'pengirims'));
    }

    public function update(Request $request, PO $po)
    {
        $data = $request->validate([
            'no_surat_jalan_nomor' => 'required|string',
            'no_surat_jalan_pt'    => 'required|string',
            'no_surat_jalan_tahun' => 'required|integer',
            'no_po'                => 'required|string',
            'no_invoice_nomor'     => 'nullable|string|max:255',
            'no_invoice_pt'        => 'nullable|string|max:255',
            'no_invoice_tahun'     => 'nullable|integer',
            'customer_id'          => 'required|exists:customers,id',
            'tanggal_po'           => 'required|date',
            'kendaraan'            => 'required|string',
            'no_polisi'            => 'required|string',
            'address_1'            => 'required|string|max:255',
            'address_2'            => 'nullable|string|max:255',
            'pengirim'             => 'nullable|string|max:255',
            'items'                => 'required|array|min:1',
            'items.*.produk_id'    => 'required|exists:produks,id',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.qty_jenis'    => 'required|in:PCS,SET',
            'items.*.harga'        => 'nullable|integer|min:0',
            'items.*.total'        => 'nullable|integer|min:0',
        ]);

        // Gabungkan nomor surat jalan
        $noSuratJalan = "{$data['no_surat_jalan_nomor']}/{$data['no_surat_jalan_pt']}/{$data['no_surat_jalan_tahun']}";

        // Ambil nama customer dari customer_id
        $customer = Customer::find($data['customer_id']);
        $customerName = $customer ? $customer->name : '';

        if (empty($data['address_1']) || $data['address_1'] === '-') {
            $data['address_1'] = $customer->address_1 ?? '';
        }
        if (empty($data['address_2']) || $data['address_2'] === '-') {
            $data['address_2'] = $customer->address_2 ?? '';
        }

        // Bentuk no_invoice jika ada inputnya
        $noInvoice = null;
        if (!empty($data['no_invoice_nomor']) || !empty($data['no_invoice_pt']) || !empty($data['no_invoice_tahun'])) {
            $nom = $data['no_invoice_nomor'] ?? '';
            $pt  = $data['no_invoice_pt'] ?? '';
            $thn = $data['no_invoice_tahun'] ?? '';
            $noInvoice = trim($nom) . '/' . trim($pt) . '/' . trim((string) $thn);
        }

        DB::transaction(function () use ($po, $data, $customerName, $noSuratJalan, $noInvoice) {
            $rawItems = collect($data['items'])
                ->filter(fn ($it) => !empty($it['produk_id']) && !empty($it['qty']))
                ->values();

            // Recompute harga & total server-side
            $items = $rawItems->map(function ($it) {
                $produk = Produk::find($it['produk_id']);
                $qtyJenis = strtoupper($it['qty_jenis'] ?? 'PCS');
                $harga = 0;
                if ($produk) {
                    $harga = $qtyJenis === 'SET' ? (int) ($produk->harga_set ?? 0) : (int) ($produk->harga_pcs ?? 0);
                }
                $qty = (int) ($it['qty'] ?? 0);
                $total = $harga * $qty;
                return [
                    'produk_id' => (int) $it['produk_id'],
                    'qty'       => $qty,
                    'qty_jenis' => $qtyJenis,
                    'harga'     => $harga,
                    'total'     => $total,
                ];
            });

            $first = $items->first();
            $sumTotal = (int) $items->sum(fn ($it) => (int) ($it['total'] ?? 0));

            $po->update([
                'no_surat_jalan' => $noSuratJalan,
                'no_po'          => $data['no_po'],
                'no_invoice'     => $noInvoice,
                'customer_id'    => $data['customer_id'],
                'customer'       => $customerName,
                'tanggal_po'     => $data['tanggal_po'],
                'produk_id'      => $first['produk_id'] ?? null,
                'qty'            => $first['qty'] ?? 0,
                'qty_jenis'      => $first['qty_jenis'] ?? 'PCS',
                'harga'          => $first['harga'] ?? 0,
                'total'          => $sumTotal,
                'kendaraan'      => $data['kendaraan'],
                'no_polisi'      => $data['no_polisi'],
                'alamat_1'       => $data['address_1'],
                'alamat_2'       => $data['address_2'] ?? null,
                'pengirim'       => $data['pengirim'] ?? null,
            ]);

            // Replace all items
            $po->items()->delete();
            foreach ($items as $it) {
                POItem::create([
                    'po_id'     => $po->id,
                    'produk_id' => $it['produk_id'],
                    'qty'       => $it['qty'],
                    'qty_jenis' => $it['qty_jenis'],
                    'harga'     => $it['harga'],
                    'total'     => $it['total'],
                ]);
            }
        });

        // === Sinkronisasi ke Jatuh Tempo (update) ===
        try {
            $invoiceKey = $noInvoice ?: $noSuratJalan;
            $tanggalInvoice = \Carbon\Carbon::parse($po->tanggal_po);
            // Gunakan payment_terms_days dari customer jika tersedia, fallback +1 bulan
            $termsDays = (int) (($customer->payment_terms_days ?? 0));
            if ($termsDays > 0) {
                $tanggalJatuhTempo = (clone $tanggalInvoice)->addDays($termsDays);
            } else {
                $tanggalJatuhTempo = (clone $tanggalInvoice)->addMonth();
            }

            // Pertahankan jumlah_terbayar jika sudah ada data sebelumnya
            $existingJT = JatuhTempo::where('no_invoice', $invoiceKey)->first();
            $jumlahTerbayar = $existingJT ? (int) ($existingJT->jumlah_terbayar ?? 0) : 0;
            $jumlahTagihan = (int) ($po->total ?? 0);

            JatuhTempo::updateOrCreate(
                ['no_invoice' => $invoiceKey],
                [
                    'no_po' => $po->no_po,
                    'customer' => $po->customer,
                    'tanggal_invoice' => $tanggalInvoice->format('Y-m-d'),
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                    'jumlah_tagihan' => $jumlahTagihan,
                    'jumlah_terbayar' => $jumlahTerbayar,
                    'sisa_tagihan' => max(0, $jumlahTagihan - $jumlahTerbayar),
                    'status_pembayaran' => $jumlahTerbayar >= $jumlahTagihan ? 'Lunas' : ($jumlahTerbayar > 0 ? 'Sebagian' : 'Belum Bayar'),
                    'status_approval' => $existingJT->status_approval ?? 'Pending',
                ]
            );
        } catch (\Throwable $e) {
            \Log::warning('Sync JatuhTempo (update) gagal: ' . $e->getMessage());
        }

        return redirect()->route('po.index')->with('success', 'Data PO berhasil diperbarui.');
    }

    public function destroy(PO $po)
    {
        $po->delete();
        
        return redirect()->route('po.index')->with('success', 'Data PO berhasil dihapus.');
    }
}
