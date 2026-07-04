<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Penjualan - {{ $penjualan->no_faktur_jual }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .header table { width: 100%; }
        .header td { vertical-align: middle; }
        .logo { width: 80px; height: auto; }
        .company-name { font-size: 18px; font-weight: bold; margin: 0 0 5px 0; text-transform: uppercase; }
        .company-info { margin: 0; font-size: 11px; line-height: 1.4; }
        .title { text-align: center; margin-bottom: 15px; }
        .title h2 { margin: 0 0 5px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 2px; }
        .info-box { width: 100%; margin-bottom: 15px; }
        .info-box td { padding: 2px 0; font-size: 11px; vertical-align: top; }
        .info-box td.label { font-weight: bold; width: 130px; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-data th, .table-data td { border: 1px solid #333; padding: 6px 8px; }
        .table-data th { background-color: #e5e7eb; text-align: center; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .table-data td.text-left { text-align: left; }
        .table-data td.text-center { text-align: center; }
        .table-data td.text-right { text-align: right; }
        .total-box { width: 50%; margin-left: auto; border: 1px solid #333; }
        .total-box td { padding: 5px 10px; font-size: 12px; }
        .total-box td.label { font-weight: bold; }
        .total-box td.value { text-align: right; font-weight: bold; }
        .total-box tr.grand-total { background-color: #e5e7eb; }
        .total-box tr.grand-total td { font-size: 14px; }
        .signature { width: 100%; text-align: center; margin-top: 40px; }
        .signature table { width: 100%; }
        .signature td { width: 50%; vertical-align: bottom; }
        .sign-line { display: inline-block; width: 180px; border-bottom: 1px solid #000; margin-top: 60px; margin-bottom: 5px; }
        .payment-info { margin-top: 10px; padding: 8px; border: 1px dashed #999; font-size: 11px; }
        .print-date { margin-top: 20px; font-size: 10px; font-style: italic; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>

    @include('laporan.cetak._kop_surat')

    <div class="title">
        <h2>Nota Penjualan</h2>
    </div>

    <table class="info-box">
        <tr>
            <td class="label">No. Faktur</td>
            <td>: {{ $penjualan->no_faktur_jual }}</td>
            <td class="label">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Pelanggan</td>
            <td>: {{ $penjualan->pelanggan->nama_lengkap ?? '-' }}</td>
            <td class="label">Kategori</td>
            <td>: {{ strtoupper($penjualan->kategori_penjualan) }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>: {{ $penjualan->pelanggan->alamat ?? '-' }}</td>
            <td class="label">Kontak</td>
            <td>: {{ $penjualan->pelanggan->kontak ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pembayaran</td>
            <td>: {{ $penjualan->metode_pembayaran }}</td>
            <td class="label">Kasir</td>
            <td>: {{ $penjualan->pengguna->nama_lengkap ?? '-' }}</td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th class="text-left">Nama Barang</th>
                <th width="10%">Kuantitas</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga Satuan</th>
                <th width="15%">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->detailPenjualan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->kuantitas, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $item->barang->satuan ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-box">
        <tr class="grand-total">
            <td class="label">GRAND TOTAL</td>
            <td class="value">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($penjualan->catatan)
        <div class="payment-info">
            <strong>Catatan:</strong> {{ $penjualan->catatan }}
        </div>
    @endif

    <div class="signature">
        <table>
            <tr>
                <td>
                    <p>Penjual / Sales</p>
                    <div class="sign-line"></div>
                    <p>( {{ $penjualan->pengguna->nama_lengkap ?? '...' }} )</p>
                </td>
                <td>
                    <p>Pembeli / Penerima</p>
                    <div class="sign-line"></div>
                    <p>( {{ $penjualan->pelanggan->nama_lengkap ?? '...' }} )</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="print-date">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>
