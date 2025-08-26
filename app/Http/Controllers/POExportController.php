<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\PO;
use App\Models\Kendaraan;
use Carbon\Carbon;

class POExportController extends Controller
{
    public function exportToExcel(Request $request)
    {
        try {
            // Dukung dua cara input:
            // 1) selected_ids (JSON array dari view Surat Jalan) -> gunakan ID pertama
            // 2) no_surat_jalan (string)
            $po = null;
            $selectedRaw = $request->input('selected_ids');
            if (!empty($selectedRaw)) {
                $selected = is_array($selectedRaw) ? $selectedRaw : json_decode($selectedRaw, true);
                if (is_array($selected) && !empty($selected)) {
                    $firstId = (int) $selected[0];
                    $po = \App\Models\PO::with(['produkRel', 'kendaraanRel', 'pengirimRel', 'items.produk'])->find($firstId);
                }
            }
            if (!$po) {
                $noSuratJalan = $request->input('no_surat_jalan');
                if ($noSuratJalan) {
                    $po = \App\Models\PO::with(['produkRel', 'kendaraanRel', 'pengirimRel', 'items.produk'])->where('no_surat_jalan', $noSuratJalan)->first();
                }
            }

            if (!$po) {
                return response()->json([
                    'error' => 'Data PO tidak ditemukan.',
                    'hint' => 'Pilih minimal satu baris di tabel sehingga selected_ids terisi, atau kirim no_surat_jalan secara langsung.',
                ], 404);
            }

            // Path template Excel (fallback .xlsm -> .xlsx)
            $templateDir = storage_path('app/template');
            $candidates = [
                $templateDir . DIRECTORY_SEPARATOR . 'Surat_Jalan_Template.xlsm',
                $templateDir . DIRECTORY_SEPARATOR . 'Surat_Jalan_Template.xlsx',
            ];
            $templatePath = null;
            foreach ($candidates as $candidate) {
                if (file_exists($candidate)) {
                    $templatePath = $candidate;
                    break;
                }
            }
            if (!$templatePath) {
                return response()->json([
                    'error' => 'Template Excel tidak ditemukan.',
                    'checked_paths' => $candidates,
                ], 404);
            }

            // Load template Excel
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Isi data ke sel yang sesuai
            // Bagi no_surat_jalan menjadi dua bagian untuk D10 dan F10
            $noSj = trim((string) ($po->no_surat_jalan ?? ''));
            $part1 = $noSj;
            $part2 = '';
            if ($noSj !== '') {
                // Coba split berdasarkan delimiter umum terlebih dahulu
                $delims = [' / ', '/', ' - ', '-', ' | ', '|'];
                $splitDone = false;
                foreach ($delims as $d) {
                    if (strpos($noSj, $d) !== false) {
                        [$part1, $part2] = explode($d, $noSj, 2);
                        $splitDone = true;
                        break;
                    }
                }
                if (!$splitDone) {
                    // Fallback: belah di tengah kata terdekat
                    $len = mb_strlen($noSj);
                    $mid = (int) floor($len / 2);
                    // Cari spasi terdekat ke kiri/kanan dari mid
                    $left = mb_strrpos(mb_substr($noSj, 0, $mid), ' ');
                    $rightPos = mb_strpos($noSj, ' ', $mid);
                    if ($left !== false) {
                        $part1 = trim(mb_substr($noSj, 0, $left));
                        $part2 = trim(mb_substr($noSj, $left + 1));
                    } elseif ($rightPos !== false) {
                        $part1 = trim(mb_substr($noSj, 0, $rightPos));
                        $part2 = trim(mb_substr($noSj, $rightPos + 1));
                    } else {
                        // Tidak ada spasi, pakai pembagian kasar
                        $part1 = trim(mb_substr($noSj, 0, $mid));
                        $part2 = trim(mb_substr($noSj, $mid));
                    }
                }
            }
            // Tampilkan bagian pertama di D10 dengan akhiran ' /' sebagai pemisah
            $sheet->setCellValue('D10', $part1 !== '' ? ($part1 . ' /') : '');
            $sheet->setCellValue('F10', $part2);
            $sheet->setCellValue('E12', $po->no_po);
            $sheet->setCellValue('J6', $po->customer);
            // tanggal_po sekarang diletakkan pada range merge K1:N2, cukup tulis ke K1
            $sheet->setCellValue('K1', $po->tanggal_po ? Carbon::parse($po->tanggal_po)->format('d F Y') : '');
            // Baris item: dari D14 kebawah (hingga D24), beserta qty (A) dan jenis (C)
            $startRow = 14;
            $endRow = 24;
            // Kosongkan area terlebih dahulu agar tidak ada sisa data
            for ($r = $startRow; $r <= $endRow; $r++) {
                $sheet->setCellValue("A{$r}", '');
                $sheet->setCellValue("C{$r}", '');
                $sheet->setCellValue("D{$r}", '');
            }
            if ($po->relationLoaded('items')) {
                $row = $startRow;
                foreach ($po->items as $item) {
                    if ($row > $endRow) { break; }
                    $sheet->setCellValue("A{$row}", $item->qty);
                    $sheet->setCellValue("C{$row}", $item->qty_jenis);
                    $sheet->setCellValue("D{$row}", $item->produk?->nama_produk ?? '');
                    // Jarakkan 1 baris kosong antar item
                    $row += 2;
                }
            }
            $sheet->setCellValue('G2', $po->harga);
            $sheet->setCellValue('H2', $po->total);

            // Kendaraan dan No Polisi
            // Ambil nama kendaraan dari relasi; jika kosong, coba query langsung berdasarkan ID di kolom `kendaraan`;
            // jika tetap tidak ada, pakai nilai mentah di kolom `kendaraan` (bisa jadi sudah berupa nama)
            $kendaraanName = $po->kendaraanRel->nama
                ?? (function($id){
                        if (empty($id)) return null;
                        $k = Kendaraan::find($id);
                        return $k->nama ?? null;
                   })($po->kendaraan)
                ?? (is_scalar($po->kendaraan) ? (string)$po->kendaraan : '');
            $sheet->setCellValue('L10', $kendaraanName);
            $noPolisi = $po->no_polisi ?: ($po->kendaraanRel->no_polisi ?? (function($id){
                if (empty($id)) return '';
                $k = Kendaraan::find($id);
                return $k->no_polisi ?? '';
            })($po->kendaraan));
            $sheet->setCellValue('K12', $noPolisi);

            // Tambahan alamat
            $sheet->setCellValue('J7', $po->alamat_1);
            $sheet->setCellValue('J8', $po->alamat_2);
            // Pengirim di H26 (fallback ke relasi jika kolom kosong)
            $sheet->setCellValue('H26', $po->pengirim ?? ($po->pengirimRel->nama ?? ''));

            // Nama file download
            $fileName = 'PO-' . now()->format('Ymd-His') . '.xlsx';

            // Set headers yang benar
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
            ];

            // Download langsung ke browser
            return response()->streamDownload(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }, $fileName, $headers);

        } catch (\Exception $e) {
            \Log::error('Excel Export Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat export Excel.'], 500);
        }
    }
}

