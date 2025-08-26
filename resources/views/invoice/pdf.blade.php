<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PDF</title>
    <style>
        /* Force exact A4 page with controlled margins in DomPDF */
        @page { size: A4 portrait; margin: 12mm 10mm 10mm 10mm; }
        html, body { margin: 0; padding: 0; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11.5px; color: #000; }
        /* Center the page content while respecting @page margins */
        .page { width: 190mm; min-height: auto; padding: 6mm 0 0 0; margin: 0 auto; background: #fff; }
        .row { display: flex; }
        .between { justify-content: space-between; }
        .mb-8 { margin-bottom: 14px; }
        .mb-4 { margin-bottom: 10px; }
        .border { border: 1px solid #000; }
        .p-2 { padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; }
        th, td { border: 1px solid #000; padding: 6px; page-break-inside: avoid; }
        th { text-align: center; }
        .no-border { border: 0; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body>
<div class="page">
    @php
        try {
            $logoFile = public_path('image/LOGO.png');
            $logoData = file_exists($logoFile) ? base64_encode(file_get_contents($logoFile)) : null;
        } catch (\Throwable $e) { $logoData = null; }
    @endphp
    <div class="row mb-8 no-break" style="align-items: flex-start; border-bottom: 2px solid #000; padding-bottom: 10px;">
        <img src="{{ $logoData ? ('data:image/png;base64,'.$logoData) : asset('image/LOGO.png') }}" alt="Logo" style="height:70px;width:auto;object-fit:contain;margin-right:16px;">
        <div style="flex: 1;">
            <h2 style="margin:0; font-size:16px; font-weight:bold; color:#d32f2f;">PT. CAM JAYA ABADI</h2>
            <p style="margin:2px 0; font-size:9.5px; line-height:1.2; color: rgb(38,73,186);">
                <strong>MANUFACTURING PROFESSIONAL WOODEN PALLET</strong><br>
                <strong>KILN DRYING WOOD WORKING INDUSTRY</strong><br>
                Factory & Office : Jl. Wahana Bakti No.28, Mangunjaya, Kec. Tambun Sel. Bekasi Jawa Barat<br>
                17510<br>
                Telp: (021) 6617 1626 - Fax: (021) 6617 3986
            </p>
        </div>
    </div>

    <div class="mb-4">
        <div class="border p-2">
            <strong>Kepada Yth.</strong><br>
            <span class="bold">{{ $invoiceDetails['customer'] ?? '-' }}</span><br>
            di .<br>
            @php
                $rawAddress = $invoiceDetails['address'] ?? '-';
                if (is_string($rawAddress)) {
                    // Pastikan setelah koma selalu ada spasi dan hilangkan spasi berlebih
                    $addr = preg_replace('/,\s*/', ', ', $rawAddress); // koma diikuti satu spasi
                    $addr = preg_replace('/\s+/', ' ', $addr);          // kompres spasi ganda
                    $addr = trim($addr, " \t\n\r\0\x0B, ");         // trim spasi/koma tak perlu di ujung
                } else {
                    $addr = '-';
                }
            @endphp
            <b>{{ $addr }}</b>
        </div>
    </div>

    <div class="text-center mb-4 no-break">
        <h1 style="font-size: 32px; font-weight: bold; letter-spacing: 4px; margin: 0; color:#333;">INVOICE</h1>
    </div>

    <div class="row between mb-4 no-break">
        <div><strong>No. PO : {{ $invoiceDetails['no_po'] ?? '-' }}</strong></div>
        <div class="text-center"><strong>No : {{ $invoiceDetails['invoice_no'] ?? '-' }}</strong></div>
        <div class="text-right"><strong>Date : {{ $invoiceDetails['invoice_date'] ?? '-' }}</strong></div>
    </div>

    <table class="mb-4">
        <thead>
            <tr>
                <th>DESCRIPTION</th>
                <th style="width:15%">QTY</th>
                <th style="width:20%">UNIT PRICE</th>
                <th style="width:20%">AMMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @php $items = $invoiceDetails['items'] ?? []; @endphp
            @foreach($items as $it)
                @php
                    $qty = (int)($it->qty ?? 0);
                    $total = (int)($it->total ?? 0);
                    $unit = $qty > 0 ? round($total / max(1,$qty)) : 0;
                    $jenis = ($it->qty_jenis ?? '') !== '' && ($it->qty_jenis ?? '0') !== '0' ? $it->qty_jenis : 'PCS';
                    $namaProduk = $it->produk->nama_produk ?? $it->produk->nama ?? $it->produk->name ?? '-';
                @endphp
                <tr>
                    <td class="bold">{{ $namaProduk }}</td>
                    <td class="bold" style="text-align:center;">{{ number_format($qty, 0, ',', '.') }} {{ $jenis }}</td>
                    <td class="bold" style="text-align:right;">Rp. {{ number_format($unit, 0, ',', '.') }}</td>
                    <td class="bold" style="text-align:right;">Rp. {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            {{-- Removed filler empty rows to avoid pushing totals to the next page --}}
        </tbody>
        <tfoot>
            <tr>
                <td class="bold" style="text-align:right;">SUB TOTAL :</td>
                <td class="bold" style="text-align:center;">{{ number_format($invoiceDetails['total_qty'] ?? 0, 0, ',', '.') }}</td>
                <td></td>
                <td style="text-align:right;">Rp. {{ number_format($invoiceDetails['subtotal'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="bold" style="text-align:right;">PPN 11% :</td>
                <td></td>
                <td></td>
                <td style="text-align:right;">Rp. {{ number_format($invoiceDetails['ppn'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="bold" style="text-align:right;">GRAND TOTAL :</td>
                <td></td>
                <td></td>
                <td class="bold" style="text-align:right;">Rp. {{ number_format($invoiceDetails['grand_total'] ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="row between no-break" style="margin-top: 18px; align-items:flex-start; gap: 8mm; flex-wrap: nowrap;">
        <div style="flex: 0 0 58%;">
            <p style="margin:0; font-size:10.5px; line-height:1.3;">
                <strong>Pembayaran Mohon Di Transfer Ke rekening</strong><br>
                <strong>Bank BRI PEJATEN</strong><br>
                <strong>NO REK : 1182-01-000039-30-3</strong><br>
                <strong>ATAS NAMA : PT. CAM JAYA ABADI</strong>
            </p>
        </div>
        <div style="flex: 0 0 38%; margin-left:auto;">
            <p style="margin:0; margin-bottom:10px; text-align:right;"><strong>Bekasi, {{ $invoiceDetails['date_location'] ?? ($invoiceDetails['invoice_date'] ?? '') }}</strong></p>
            @php
                try {
                    $stampFile = public_path('image/LOGO.png');
                    $stampData = file_exists($stampFile) ? base64_encode(file_get_contents($stampFile)) : null;
                } catch (\Throwable $e) { $stampData = null; }
            @endphp
            <div style="width:160px; margin: 0 0 6px auto; text-align:center;">
                <img src="{{ $stampData ? ('data:image/png;base64,'.$stampData) : asset('image/LOGO.png') }}" alt="Stamp" style="height:74px;width:auto;object-fit:contain;opacity:0.95; display:block; margin: 0 auto;">
            </div>
            <div style="text-align:center; margin-left:auto; width:170px;">
                <p style="margin:0; font-size:10px;">
                    <strong><u>NANIK PURNAMI</u></strong><br>
                    <span style="font-size:8px;">DIREKTUR UTAMA</span>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
