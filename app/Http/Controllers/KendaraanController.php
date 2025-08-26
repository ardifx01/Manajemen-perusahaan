<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Menampilkan daftar semua kendaraan.
     */
    public function index()
    {
        $kendaraans = Kendaraan::all();
        return view('kendaraan.index', compact('kendaraans'));
    }

    /**
     * Menampilkan form input kendaraan (jika digunakan terpisah).
     */
    public function create()
    {
        return view('kendaraan.create'); // hanya jika form tambah kendaraan berdiri sendiri
    }

    /**
     * Simpan data kendaraan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255', // changed from 'nama_kendaraan' to 'nama'
            'no_polisi' => 'nullable|string|max:50',
        ]);

        $kendaraan = Kendaraan::create($validated);

        // Cek apakah request ini dari AJAX (fetch/axios)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'kendaraan' => $kendaraan
            ]);
        }

        // Jika bukan AJAX, redirect seperti biasa
        return redirect()->route('kendaraan.index')
                         ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk edit kendaraan.
     */
    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        return view('kendaraan.edit', compact('kendaraan'));
    }

    /**
     * Update data kendaraan.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255', // changed from 'nama_kendaraan' to 'nama'
            'no_polisi' => 'nullable|string|max:50',
        ]);

        $kendaraan->update($validated);

        // Cek AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'kendaraan' => $kendaraan
            ]);
        }

        return redirect()->route('kendaraan.index')
                         ->with('success', 'Data kendaraan berhasil diupdate.');
    }

    /**
     * Hapus kendaraan dari database.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();

        // Cek AJAX
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('kendaraan.index')
                         ->with('success', 'Data kendaraan berhasil dihapus.');
    }
}
