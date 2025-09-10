<?php

namespace App\Exports;

use App\Models\PO;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Log;

class SuratJalanExport implements FromCollection, WithHeadings
{
    protected $selectedIds;

    public function __construct($selectedIds = null)
    {
        $this->selectedIds = $selectedIds;
    }

    public function collection()
    {
        $query = PO::with(['items.produk']);

        if (is_array($this->selectedIds) && !empty($this->selectedIds)) {
            $query->whereIn('id', $this->selectedIds);
        }

        $pos = $query->orderBy('tanggal_po', 'desc')->get();

        Log::info('[export] PO count: ' . $pos->count());
        Log::info('[export] Selected IDs: ' . json_encode($this->selectedIds));

        // Deteksi multi-PO (lebih dari satu No PO di kumpulan data)
        $uniquePOs = [];
        foreach ($pos as $poCheck) {
            if (!empty($poCheck->no_po)) {
                $uniquePOs[$poCheck->no_po] = true;
            }
        }
        $multiPO = count($uniquePOs) > 1;

        // Flatten to one row per item
        $rows = collect();
        $counter = 1;
        foreach ($pos as $po) {
            foreach ($po->items as $it) {
                // Produk + No PO di kanan nama (hanya saat multi-PO)
                $produkBase = $it->produk->nama_produk ?? 'N/A';
                $produkOut = ($multiPO && !empty($po->no_po))
                    ? trim($produkBase) . ' (' . trim($po->no_po) . ')'
                    : $produkBase;

                $rows->push([
                    'no' => $counter++,
                    'tanggal' => $this->formatDate($po->tanggal_po),
                    'customer' => $po->customer,
                    'no_surat_jalan' => $po->no_surat_jalan,
                    // Kolom No PO dikosongkan saat multi-PO agar konsisten dengan Invoice/PDF
                    'no_po' => $multiPO ? '' : ($po->no_po ?? ''),
                    'kendaraan' => $po->kendaraan,
                    'no_polisi' => $po->no_polisi,
                    'qty' => (int) ($it->qty ?? 0),
                    'qty_jenis' => strtoupper($it->qty_jenis ?? 'PCS'),
                    'produk' => $produkOut,
                    'tanggal_po_detail' => $this->formatDate($po->tanggal_po),
                    'tanggal_surat_jalan' => $this->formatDate($po->tanggal_po),
                    'tanggal_kirim' => $this->formatDate($po->tanggal_po),
                    'tanggal_terima' => $this->formatDate($po->tanggal_po),
                    'pengirim' => $po->pengirim ?? '-',
                    'total' => 'Rp ' . number_format((int) ($it->total ?? 0), 0, ',', '.')
                ]);
            }
        }

        return $rows;
    }

    private function formatDate($date)
    {
        if (empty($date) || is_null($date)) {
            Log::info('[export] Date is empty or null: ' . $date);
            return '-';
        }
        
        try {
            $formatted = \Carbon\Carbon::parse($date)->format('d/m/Y');
            Log::info('[export] Formatted date: ' . $date . ' -> ' . $formatted);
            return $formatted;
        } catch (\Exception $e) {
            Log::error('[export] Date format error: ' . $e->getMessage());
            return '-';
        }
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Customer', 
            'No Surat Jalan',
            'No PO',
            'Kendaraan',
            'No Polisi',
            'Qty',
            'Jenis',
            'Produk',
            'Tanggal PO',
            'Tanggal Surat Jalan',
            'Tanggal Kirim',
            'Tanggal Terima',
            'Pengirim',
            'Total'
        ];
    }
}
