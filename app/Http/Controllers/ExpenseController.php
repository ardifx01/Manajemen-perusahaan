<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'   => ['required','date'],
            'jenis'     => ['required','string','max:100'],
            'deskripsi' => ['nullable','string','max:255'],
            'amount'    => ['required','numeric','min:0'],
        ]);

        $data['user_id'] = Auth::id();
        // Simpan sebagai integer rupiah
        $data['amount'] = (int) round($data['amount']);
        Expense::create($data);

        return back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }
}
