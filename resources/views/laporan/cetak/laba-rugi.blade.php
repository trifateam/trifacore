<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            color: #333;
        }
        .header { 
            width: 100%; 
            border-bottom: 2px solid #000; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .header table { 
            width: 100%; 
        }
        .header td { 
            vertical-align: middle; 
        }
        .logo { 
            width: 80px; 
            height: auto; 
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .company-info {
            margin: 0;
            font-size: 11px;
            line-height: 1.4;
        }
        .title { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .title h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .title p {
            margin: 0;
            font-size: 12px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #333;
            color: #fff;
            padding: 5px 10px;
            margin-bottom: 0;
        }
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .table-data th, .table-data td { 
            border: 1px solid #333; 
            padding: 6px 10px; 
        }
        .table-data th { 
            background-color: #e5e7eb; 
            text-align: center;
            font-weight: bold;
        }
        .table-data td.text-left {
            text-align: left;
        }
        .table-data td.text-right {
            text-align: right;
        }
        .table-data td.font-bold {
            font-weight: bold;
        }
        .table-data td.bg-gray {
            background-color: #f3f4f6;
        }
        .signature { 
            width: 100%; 
            text-align: center; 
            margin-top: 40px; 
        }
        .signature table {
            width: 100%;
        }
        .signature td { 
            width: 50%; 
            vertical-align: bottom;
        }
        .sign-line {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #000;
            margin-top: 70px;
            margin-bottom: 5px;
        }
        .print-date {
            margin-top: 20px;
            font-size: 10px;
            font-style: italic;
        }
        .bottom-line-box {
            width: 100%;
            border: 2px solid #333;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
        }
        .bottom-line-box h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .bottom-line-box .amount {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    @include('laporan.cetak._kop_surat')

    <div class="title">
        <h2>Laporan Laba Rugi</h2>
        @php
            $namaBulan = \Carbon\Carbon::createFromFormat('n', $bulan)->translatedFormat('F');
        @endphp
        <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    <!-- ARUS KAS MASUK -->
    <div class="section-title">A. ARUS KAS MASUK (PENDAPATAN)</div>
    <table class="table-data">
        <tbody>
            <tr>
                <td width="70%">Penjualan Telur</td>
                <td width="30%" class="text-right">Rp {{ number_format($penjualanTelur, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Penjualan Ayam Afkir</td>
                <td class="text-right">Rp {{ number_format($penjualanAfkir, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Penjualan Pupuk/Kotoran</td>
                <td class="text-right">Rp {{ number_format($penjualanPupuk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pelunasan Piutang (Pembayaran dari Pelanggan)</td>
                <td class="text-right">Rp {{ number_format($pelunasanPiutang, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold bg-gray text-right">TOTAL KAS MASUK</td>
                <td class="font-bold bg-gray text-right">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- ARUS KAS KELUAR -->
    <div class="section-title">B. ARUS KAS KELUAR (PENGELUARAN)</div>
    <table class="table-data">
        <tbody>
            <tr>
                <td width="70%">Pembelian Pakan</td>
                <td width="30%" class="text-right">Rp {{ number_format($pembelianPakan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pembelian Vitamin/Obat</td>
                <td class="text-right">Rp {{ number_format($pembelianVitamin, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pembelian Ayam Pullet</td>
                <td class="text-right">Rp {{ number_format($pembelianPullet, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pelunasan Hutang (Pembayaran ke Supplier)</td>
                <td class="text-right">Rp {{ number_format($pelunasanHutang, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold" colspan="2" style="border-bottom: none;">Biaya Operasional:</td>
            </tr>
            @forelse($opsArr as $ops)
            <tr>
                <td style="padding-left: 20px;">- {{ $ops['kategori'] }}</td>
                <td class="text-right">{{ $ops['total'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="padding-left: 20px; font-style: italic;">Tidak ada biaya operasional bulan ini</td>
            </tr>
            @endforelse
            <tr>
                <td class="font-bold bg-gray text-right">TOTAL KAS KELUAR</td>
                <td class="font-bold bg-gray text-right">Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- BOTTOM LINE -->
    <div class="bottom-line-box">
        <h3>LABA BERSIH (NET PROFIT)</h3>
        <div class="amount" style="color: {{ $netProfitLoss < 0 ? '#dc2626' : '#16a34a' }}">
            Rp {{ number_format($netProfitLoss, 0, ',', '.') }}
        </div>
        <div style="margin-top: 10px;">
            Profit Margin: {{ round($profitMargin, 2) }}%
        </div>
    </div>

    <div class="signature">
        <table>
            <tr>
                <td>
                    <p>Dibuat Oleh,</p>
                    <div class="sign-line"></div>
                    <p>( ........................................ )</p>
                </td>
                <td>
                    <p>Mengetahui,</p>
                    <div class="sign-line"></div>
                    <p>( ........................................ )</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="print-date">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>

    <script>
        window.onload = function() {
            if (window.location.href.includes('preview')) {
                window.print();
            }
        }
    </script>
</body>
</html>
