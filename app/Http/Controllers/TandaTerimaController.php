<?php

namespace App\Http\Controllers;

use App\Models\TandaTerima;
use App\Models\PO;
use App\Models\Produk;
use App\Models\Customer;
use Illuminate\Http\Request;

class TandaTerimaController extends Controller
{
    public function index()
    {
        $tandaTerimas = TandaTerima::with(['produkRel'])->latest()->get();
        $pos = PO::all();
        $produks = Produk::all();
        $customers = Customer::all();

        return view('dashboard.tanda_terima_index', compact('tandaTerimas', 'pos', 'produks', 'customers'));
    }

    public function create()
    {
        $pos = PO::all();
        $produks = Produk::all();
        $customers = Customer::all();

        return view('dashboard.tanda_terima_create', compact('pos', 'produks', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_tanda_terima' => 'required|string|unique:tanda_terimas',
            'no_po' => 'required|string',
            'no_surat_jalan' => 'nullable|string',
            'customer' => 'required|string',
            'alamat_1' => 'nullable|string',
            'alamat_2' => 'nullable|string',
            'tanggal_terima' => 'required|date',
            'produk_id' => 'required|exists:produks,id',
            'qty_dikirim' => 'required|integer|min:1',
            'qty_diterima' => 'required|integer|min:0',
            'qty_jenis' => 'required|in:PCS,SET',
            'kondisi_barang' => 'required|in:Baik,Rusak,Sebagian Rusak',
            'status' => 'required|in:Lengkap,Sebagian,Pending',
            'penerima_nama' => 'required|string',
            'penerima_jabatan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('foto_bukti')) {
            $data['foto_bukti'] = $request->file('foto_bukti')->store('tanda_terima', 'public');
        }

        TandaTerima::create($data);

        return redirect()->route('tanda-terima.index')->with('success', 'Tanda Terima berhasil dibuat.');
    }

    public function edit($id)
    {
        $tandaTerima = TandaTerima::findOrFail($id);
        $tandaTerimas = TandaTerima::with(['produkRel'])->latest()->get();
        $pos = PO::all();
        $produks = Produk::all();
        $customers = Customer::all();

        return view('dashboard.tanda_terima_index', compact('tandaTerima', 'tandaTerimas', 'pos', 'produks', 'customers'));
    }

    public function update(Request $request, TandaTerima $tandaTerima)
    {
        $data = $request->validate([
            'no_tanda_terima' => 'required|string|unique:tanda_terimas,no_tanda_terima,' . $tandaTerima->id,
            'no_po' => 'required|string',
            'no_surat_jalan' => 'nullable|string',
            'customer' => 'required|string',
            'alamat_1' => 'nullable|string',
            'alamat_2' => 'nullable|string',
            'tanggal_terima' => 'required|date',
            'produk_id' => 'required|exists:produks,id',
            'qty_dikirim' => 'required|integer|min:1',
            'qty_diterima' => 'required|integer|min:0',
            'qty_jenis' => 'required|in:PCS,SET',
            'kondisi_barang' => 'required|in:Baik,Rusak,Sebagian Rusak',
            'status' => 'required|in:Lengkap,Sebagian,Pending',
            'penerima_nama' => 'required|string',
            'penerima_jabatan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('foto_bukti')) {
            $data['foto_bukti'] = $request->file('foto_bukti')->store('tanda_terima', 'public');
        }

        $tandaTerima->update($data);

        return redirect()->route('tanda-terima.index')->with('success', 'Tanda Terima berhasil diperbarui.');
    }

    public function destroy(TandaTerima $tandaTerima)
    {
        if ($tandaTerima->foto_bukti) {
            \Storage::disk('public')->delete($tandaTerima->foto_bukti);
        }
        
        $tandaTerima->delete();
        return redirect()->route('tanda-terima.index')->with('success', 'Tanda Terima berhasil dihapus.');
    }
}
