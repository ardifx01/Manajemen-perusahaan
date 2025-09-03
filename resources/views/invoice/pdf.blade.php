<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PDF</title>
    @php
        $items = $invoiceDetails['items'] ?? [];
        $itemCount = is_countable($items) ? count($items) : 0;
        // Tentukan mode skala berdasarkan jumlah item
        if ($itemCount <= 12) {
            $mode = 'normal';
        } elseif ($itemCount <= 22) {
            $mode = 'compact';
        } else {
            $mode = 'ultra';
        }

        $fsBase = $mode === 'normal' ? 11.5 : ($mode === 'compact' ? 10.2 : 9.2);
        $padCell = $mode === 'normal' ? 6 : ($mode === 'compact' ? 4 : 3);
        $padBox  = $mode === 'normal' ? 8 : ($mode === 'compact' ? 6 : 4);
        $mb8     = $mode === 'normal' ? 14 : ($mode === 'compact' ? 10 : 8);
        $mb4     = $mode === 'normal' ? 10 : ($mode === 'compact' ? 8 : 6);
        $hLogo   = $mode === 'normal' ? 70 : ($mode === 'compact' ? 60 : 50);
        $hStamp  = $mode === 'normal' ? 74 : ($mode === 'compact' ? 64 : 54);
        $titleFs = $mode === 'normal' ? 32 : ($mode === 'compact' ? 26 : 22);
        $addrFs  = $mode === 'normal' ? 9.5 : ($mode === 'compact' ? 9 : 8.5);
        $pageTop = $mode === 'normal' ? 12 : ($mode === 'compact' ? 10 : 8); // @page top margin (mm)
        $pageSide = 10; // left/right margin constant (mm)
        $pageBottom = $mode === 'normal' ? 10 : ($mode === 'compact' ? 9 : 8);
        $tdRightFs = $mode === 'normal' ? 10.5 : ($mode === 'compact' ? 9.8 : 9.2);
        $lineHeight = $mode === 'normal' ? 1.25 : ($mode === 'compact' ? 1.2 : 1.1);
        // Lebar kolom dinamis (sisanya otomatis untuk DESCRIPTION)
        $wQty = $mode === 'normal' ? '15%' : ($mode === 'compact' ? '13%' : '12%');
        $wUnit = $mode === 'normal' ? '20%' : ($mode === 'compact' ? '18%' : '16%');
        $wAmt = $mode === 'normal' ? '20%' : ($mode === 'compact' ? '18%' : '16%');
    @endphp
    <style>
        /* Force exact A4 page with controlled margins in DomPDF */
        @page { size: A4 portrait; margin: {{ $pageTop }}mm {{ $pageSide }}mm {{ $pageBottom }}mm {{ $pageSide }}mm; }
        html, body { margin: 0; padding: 0; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: {{ $fsBase }}px; color: #000; }
        /* Center the page content while respecting @page margins */
        .page { width: 190mm; min-height: auto; padding: 4mm 0 0 0; margin: 0 auto; background: #fff; }
        .row { display: flex; }
        .between { justify-content: space-between; }
        .mb-8 { margin-bottom: {{ $mb8 }}px; }
        .mb-4 { margin-bottom: {{ $mb4 }}px; }
        .border { border: 1px solid #000; }
        .p-2 { padding: {{ $padBox }}px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: {{ $padCell }}px; page-break-inside: avoid; line-height: {{ $lineHeight }}; }
        th { text-align: center; }
        .no-border { border: 0; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        .no-break { page-break-inside: avoid; }
        .col-desc { word-wrap: break-word; word-break: break-word; white-space: normal; }
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
    <div class="row mb-8 no-break" style="align-items: flex-start; border-bottom: 2px solid #000; padding-bottom: {{ max(6, $padBox - 2) }}px;">
        <img src="{{ $logoData ? ('data:image/png;base64,'.$logoData) : asset('image/LOGO.png') }}" alt="Logo" style="height:{{ $hLogo }}px;width:auto;object-fit:contain;margin-right:16px;">
        <div style="flex: 1;">
            <h2 style="margin:0; font-size:16px; font-weight:bold; color:#d32f2f;">PT. CAM JAYA ABADI</h2>
            <p style="margin:2px 0; font-size:{{ $addrFs }}px; line-height:1.2; color: rgb(38,73,186);">
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
        <h1 style="font-size: {{ $titleFs }}px; font-weight: bold; letter-spacing: 3px; margin: 0; color:#333;">INVOICE</h1>
    </div>

    <table class="no-break" style="width:100%; border-collapse:collapse; margin:0;">
        <tr>
            <td style="width:33.33%; text-align:left; vertical-align:bottom; padding:0;">
                <span style="font-weight:bold;">No. PO : {{ $invoiceDetails['no_po'] ?? '-' }}</span>
            </td>
            <td style="width:33.33%; text-align:center; vertical-align:bottom; padding:0;">
                <span style="font-weight:bold;">No : {{ $invoiceDetails['invoice_no'] ?? '-' }}</span>
            </td>
            <td style="width:33.33%; text-align:right; vertical-align:bottom; padding:0;">
                <span style="font-weight:bold;">Date : {{ $invoiceDetails['invoice_date'] ?? '-' }}</span>
            </td>
        </tr>
    </table>

    <table class="mb-4" style="margin-top: 8px;">
        <thead>
            <tr>
                <th>DESCRIPTION</th>
                <th style="width:{{ $wQty }}">QTY</th>
                <th style="width:{{ $wUnit }}">UNIT PRICE</th>
                <th style="width:{{ $wAmt }}">AMMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $it)
                @php
                    $qty = (int)($it->qty ?? 0);
                    $total = (int)($it->total ?? 0);
                    $unit = $qty > 0 ? round($total / max(1,$qty)) : 0;
                    $jenis = ($it->qty_jenis ?? '') !== '' && ($it->qty_jenis ?? '0') !== '0' ? $it->qty_jenis : 'PCS';
                    $namaProduk = $it->produk->nama_produk ?? $it->produk->nama ?? $it->produk->name ?? '-';
                @endphp
                <tr>
                    <td class="bold col-desc">{{ $namaProduk }}</td>
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

    <div class="row between no-break" style="margin-top: {{ max(10, $mb4) }}px; align-items:flex-start; gap: 6mm; flex-wrap: nowrap;">
        <div style="flex: 0 0 58%;">
            <p style="margin:0; font-size:{{ $tdRightFs }}px; line-height:1.25;">
                <strong>Pembayaran Mohon Di Transfer Ke rekening</strong><br>
                <strong>Bank BRI PEJATEN</strong><br>
                <strong>NO REK : 1182-01-000039-30-3</strong><br>
                <strong>ATAS NAMA : PT. CAM JAYA ABADI</strong>
            </p>
        </div>
        <div style="flex: 0 0 38%; margin-left:auto;">
            <p style="margin:0; margin-bottom:8px; text-align:right;"><strong>Bekasi, {{ $invoiceDetails['date_location'] ?? ($invoiceDetails['invoice_date'] ?? '') }}</strong></p>
            {{-- Logo perusahaan di area tanda tangan disembunyikan sesuai permintaan --}}
            {{-- Stamp dihapus agar tidak tercetak di PDF --}}
            <div style="text-align:center; margin-left:auto; width:170px;">
                <p style="margin:0; font-size:9.5px;">
                    <strong><u>NANIK PURNAMI</u></strong><br>
                    <span style="font-size:8px;">DIREKTUR UTAMA</span>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
