<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PO;
use App\Models\Produk;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['produkRel'])->latest()->get();
        $pos = PO::all();
        $produks = Produk::all();
        $customers = Customer::all();

        return view('dashboard.invoice_index', compact('invoices', 'pos', 'produks', 'customers'));
    }

    public function create()
    {
        $pos = PO::all();
        $produks = Produk::all();
        $customers = Customer::all();

        return view('dashboard.invoice_create', compact('pos', 'produks', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_invoice' => 'required|string|unique:invoices',
            'no_po' => 'required|string',
            'customer' => 'required|string',
            'alamat_1' => 'nullable|string',
            'alamat_2' => 'nullable|string',
            'tanggal_invoice' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date',
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
            'qty_jenis' => 'required|in:PCS,SET',
            'harga' => 'required|integer',
            'total' => 'required|integer',
            'pajak' => 'nullable|integer',
            'grand_total' => 'required|integer',
            'status' => 'required|in:Draft,Sent,Paid,Overdue',
            'keterangan' => 'nullable|string'
        ]);

        Invoice::create($data);

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dibuat.');
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoices = Invoice::with(['produkRel'])->latest()->get();
        $pos = PO::all();
        $produks = Produk::all();
        $customers = Customer::all();

        return view('dashboard.invoice_index', compact('invoice', 'invoices', 'pos', 'produks', 'customers'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'no_invoice' => 'required|string|unique:invoices,no_invoice,' . $invoice->id,
            'no_po' => 'required|string',
            'customer' => 'required|string',
            'alamat_1' => 'nullable|string',
            'alamat_2' => 'nullable|string',
            'tanggal_invoice' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date',
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
            'qty_jenis' => 'required|in:PCS,SET',
            'harga' => 'required|integer',
            'total' => 'required|integer',
            'pajak' => 'nullable|integer',
            'grand_total' => 'required|integer',
            'status' => 'required|in:Draft,Sent,Paid,Overdue',
            'keterangan' => 'nullable|string'
        ]);

        $invoice->update($data);

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }
}
