<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Produk;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BarangMasuk::with('produk')
            ->latest('tanggal')
            ->paginate(10);

        return view('barang.masuk.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('barang.masuk.create', compact('produks'));
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

        BarangMasuk::create($data);

        return redirect()->route('barang.masuk.index')->with('success', 'Barang Masuk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $masuk)
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('barang.masuk.edit', compact('masuk','produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $masuk)
    {
        $data = $request->validate([
            'produk_id' => ['required','exists:produks,id'],
            'qty' => ['required','integer','min:1'],
            'tanggal' => ['required','date'],
            'keterangan' => ['nullable','string'],
        ]);

        $masuk->update($data);

        return redirect()->route('barang.masuk.index')->with('success', 'Barang Masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $masuk)
    {
        $masuk->delete();
        return redirect()->route('barang.masuk.index')->with('success', 'Barang Masuk berhasil dihapus.');
    }
}
