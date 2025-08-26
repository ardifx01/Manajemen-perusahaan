<?php

namespace App\Http\Controllers;

use App\Models\JatuhTempo;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JatuhTempoController extends Controller
{
    public function index()
    {
        $jatuhTempos = JatuhTempo::orderBy('tanggal_jatuh_tempo', 'asc')->get();
        
        // Update hari terlambat untuk semua record
        foreach ($jatuhTempos as $jt) {
            if ($jt->status_pembayaran !== 'Lunas') {
                $today = Carbon::now();
                $dueDate = Carbon::parse($jt->tanggal_jatuh_tempo);
                $hariTerlambat = $today->gt($dueDate) ? $today->diffInDays($dueDate) : 0;
                
                $jt->update(['hari_terlambat' => $hariTerlambat]);
            }
        }

        // Statistik
        $totalTagihan = $jatuhTempos->sum('jumlah_tagihan');
        $totalTerbayar = $jatuhTempos->sum('jumlah_terbayar');
        $totalOverdue = $jatuhTempos->where('is_overdue', true)->sum('sisa_tagihan');
        $countOverdue = $jatuhTempos->where('is_overdue', true)->count();

        return view('dashboard.jatuh_tempo_index', compact('jatuhTempos', 'totalTagihan', 'totalTerbayar', 'totalOverdue', 'countOverdue'));
    }

    public function create()
    {
        $invoices = Invoice::all();
        return view('dashboard.jatuh_tempo_create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_invoice' => 'required|string|unique:jatuh_tempos',
            'no_po' => 'required|string',
            'customer' => 'required|string',
            'tanggal_invoice' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_tagihan' => 'required|integer|min:0',
            'jumlah_terbayar' => 'nullable|integer|min:0',
            'status_pembayaran' => 'required|in:Belum Bayar,Sebagian,Lunas',
            'denda' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string'
        ]);

        // Hitung sisa tagihan
        $data['jumlah_terbayar'] = $data['jumlah_terbayar'] ?? 0;
        $data['sisa_tagihan'] = $data['jumlah_tagihan'] - $data['jumlah_terbayar'];

        // Hitung hari terlambat
        $today = Carbon::now();
        $dueDate = Carbon::parse($data['tanggal_jatuh_tempo']);
        $data['hari_terlambat'] = $today->gt($dueDate) ? $today->diffInDays($dueDate) : 0;

        JatuhTempo::create($data);

        return redirect()->route('jatuh-tempo.index')->with('success', 'Data Jatuh Tempo berhasil dibuat.');
    }

    public function edit($id)
    {
        $jatuhTempo = JatuhTempo::findOrFail($id);
        $jatuhTempos = JatuhTempo::orderBy('tanggal_jatuh_tempo', 'asc')->get();
        $invoices = Invoice::all();

        // Statistik
        $totalTagihan = $jatuhTempos->sum('jumlah_tagihan');
        $totalTerbayar = $jatuhTempos->sum('jumlah_terbayar');
        $totalOverdue = $jatuhTempos->where('is_overdue', true)->sum('sisa_tagihan');
        $countOverdue = $jatuhTempos->where('is_overdue', true)->count();

        return view('dashboard.jatuh_tempo_index', compact('jatuhTempo', 'jatuhTempos', 'invoices', 'totalTagihan', 'totalTerbayar', 'totalOverdue', 'countOverdue'));
    }

    public function update(Request $request, JatuhTempo $jatuhTempo)
    {
        $data = $request->validate([
            'no_invoice' => 'required|string|unique:jatuh_tempos,no_invoice,' . $jatuhTempo->id,
            'no_po' => 'required|string',
            'customer' => 'required|string',
            'tanggal_invoice' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_tagihan' => 'required|integer|min:0',
            'jumlah_terbayar' => 'nullable|integer|min:0',
            'status_pembayaran' => 'required|in:Belum Bayar,Sebagian,Lunas',
            'denda' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string'
        ]);

        // Hitung sisa tagihan
        $data['jumlah_terbayar'] = $data['jumlah_terbayar'] ?? 0;
        $data['sisa_tagihan'] = $data['jumlah_tagihan'] - $data['jumlah_terbayar'];

        // Hitung hari terlambat
        $today = Carbon::now();
        $dueDate = Carbon::parse($data['tanggal_jatuh_tempo']);
        $data['hari_terlambat'] = $today->gt($dueDate) ? $today->diffInDays($dueDate) : 0;

        $jatuhTempo->update($data);

        return redirect()->route('jatuh-tempo.index')->with('success', 'Data Jatuh Tempo berhasil diperbarui.');
    }

    public function destroy(JatuhTempo $jatuhTempo)
    {
        $jatuhTempo->delete();
        return redirect()->route('jatuh-tempo.index')->with('success', 'Data Jatuh Tempo berhasil dihapus.');
    }

    public function sendReminder($id)
    {
        $jatuhTempo = JatuhTempo::findOrFail($id);
        
        // Update reminder status
        $jatuhTempo->update([
            'reminder_sent' => true,
            'last_reminder_date' => Carbon::now()
        ]);

        return redirect()->route('jatuh-tempo.index')->with('success', 'Reminder berhasil dikirim.');
    }
}
