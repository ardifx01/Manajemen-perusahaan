<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Produk;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BarangKeluar::with('produk')
            ->latest('tanggal')
            ->paginate(10);

        return view('barang.keluar.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('barang.keluar.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'produk_id' => ['required','exists:produks,id'],
            'qty' => ['required','integer','min:1'],
            'tanggal' => ['required','date'],
            'keterangan' => ['nullable','string'],
        ]);

        $data['user_id'] = auth()->id();

        BarangKeluar::create($data);

        return redirect()->route('barang.keluar.index')->with('success', 'Barang Keluar berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $keluar)
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('barang.keluar.edit', compact('keluar','produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangKeluar $keluar)
    {
        $data = $request->validate([
            'produk_id' => ['required','exists:produks,id'],
            'qty' => ['required','integer','min:1'],
            'tanggal' => ['required','date'],
            'keterangan' => ['nullable','string'],
        ]);

        $keluar->update($data);

        return redirect()->route('barang.keluar.index')->with('success', 'Barang Keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $keluar)
    {
        $keluar->delete();
        return redirect()->route('barang.keluar.index')->with('success', 'Barang Keluar berhasil dihapus.');
    }
}
