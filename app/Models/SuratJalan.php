<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SuratJalan;

class PengirimController extends Controller
{
    public function index()
    {
        $pengirims = DB::table('pos')
            ->select('pengirim as nama')
            ->whereNotNull('pengirim')
            ->where('pengirim', '!=', '')
            ->distinct()
            ->orderBy('pengirim', 'asc')
            ->get()
            ->map(function($item, $index) {
                $item->id = $index + 1; // Add fake ID for compatibility
                return $item;
            });
        
        return view('pengirim.index', compact('pengirims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $exists = DB::table('pos')
            ->where('pengirim', $request->nama)
            ->exists();

        if ($exists) {
            return redirect()->route('pengirim.index')->with('error', 'Nama pengirim sudah ada');
        }

        // Insert ke tabel pos (sementara tetap ada)
        DB::table('pos')->insert([
            'pengirim' => $request->nama,
            'no_surat_jalan' => 'TEMP-' . date('YmdHis') . '-' . rand(1000, 9999),
            'no_po' => 'PO-TEMP-' . date('YmdHis') . '-' . rand(1000, 9999),
            'customer' => 'CUSTOMER-TEMP-' . date('YmdHis') . '-' . rand(1000, 9999),
            'tanggal_po' => now(),
            'qty' => 0,
            'harga' => 0,
            'qty_jenis' => 'PCS',
            'alamat_1' => 'Alamat Temporary',
            'alamat_2' => 'Alamat Temporary',
            'produk_id' => null,
            'total' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // INSERT JUGA ke tabel surat_jalans via Model
        SuratJalan::create([
            'tanggal_po'    => now(),
            'customer'      => 'CUSTOMER-TEMP-' . date('YmdHis') . '-' . rand(1000, 9999),
            'alamat_1'      => 'Alamat Temporary',
            'alamat_2'      => 'Alamat Temporary',
            'no_surat_jalan'=> 'SJ-' . date('YmdHis') . '-' . rand(1000, 9999),
            'no_po'         => 'PO-' . date('YmdHis') . '-' . rand(1000, 9999),
            'kendaraan'     => 'KENDARAAN-TEMP',
            'no_polisi'     => 'NOPOL-TEMP',
            'qty'           => 0,
            'qty_jenis'     => 'PCS',
            'produk_id'     => null,
            'harga'         => 0,
            'total'         => 0,
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        return redirect()->route('pengirim.index')->with('success', 'Data pengirim berhasil ditambahkan dan masuk ke Surat Jalan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $oldName = $request->input('old_nama');
        
        DB::table('pos')
            ->where('pengirim', $oldName)
            ->update([
                'pengirim' => $request->nama,
                'updated_at' => now()
            ]);

        return redirect()->route('pengirim.index')->with('success', 'Data pengirim berhasil diperbarui');
    }

    public function destroy($id)
    {
        return redirect()->route('pengirim.index')->with('error', 'Hapus data pengirim tidak tersedia untuk struktur ini');
    }

    public function getInvoiceData(Request $request)
    {
        $ids = $request->input('ids', []);
        $suratjalan = \App\Models\SuratJalan::with('produkRel')->whereIn('id', $ids)->get();
        return response()->json(['data' => $suratjalan]);
    }

    public function generateInvoicePDF(Request $request)
    {
        $selectedIds = $request->input('selected_ids');
        
        $data = DB::table('pos')
            ->leftJoin('produk', 'pos.produk_id', '=', 'produk.id')
            ->select(
                'pos.*',
                'produk.nama_produk'
            )
            ->whereIn('pos.id', $selectedIds)
            ->get();

        // Calculate totals
        $subtotal = $data->sum('total');
        $ppn = round($subtotal * 0.11);
        $grandTotal = $subtotal + $ppn;

        return view('invoice.pdf', compact('data', 'subtotal', 'ppn', 'grandTotal'));
    }

    public function getPengirimStats()
    {
        $stats = DB::table('pos')
            ->select('pengirim')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total) as total_amount')
            ->whereNotNull('pengirim')
            ->where('pengirim', '!=', '')
            ->groupBy('pengirim')
            ->orderBy('total_amount', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
