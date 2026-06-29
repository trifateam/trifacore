<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi Telur - {{ $kandang->nama_kandang }}</title>
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
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .table-data th, .table-data td { 
            border: 1px solid #333; 
            padding: 6px 4px; 
            text-align: right; 
        }
        .table-data th { 
            background-color: #e5e7eb; 
            text-align: center;
            font-weight: bold;
        }
        .table-data td.text-left {
            text-align: left;
        }
        .table-data td.text-center {
            text-align: center;
        }
        .summary-box {
            width: 50%;
            border: 1px solid #333;
            padding: 10px;
            margin-bottom: 30px;
        }
        .summary-box table {
            width: 100%;
        }
        .summary-box td {
            padding: 3px 0;
            font-size: 11px;
        }
        .summary-box td.label {
            font-weight: bold;
            width: 60%;
        }
        .summary-box td.value {
            text-align: right;
            font-weight: bold;
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
    </style>
</head>
<body>

    @include('laporan.cetak._kop_surat')

    <div class="title">
        <h2>Laporan Produksi Telur - {{ $kandang->nama_kandang }}</h2>
        @php
            $namaBulan = \Carbon\Carbon::createFromFormat('n', $bulan)->translatedFormat('F');
        @endphp
        <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th>RB</th>
                <th>MB</th>
                <th>MK</th>
                <th>Pecah</th>
                <th>Total Telur</th>
                <th>Mati</th>
                <th>Afkir</th>
                <th>Populasi</th>
                <th>HDP %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailyData as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row['tanggal'] }}</td>
                    <td>{{ number_format($row['rb'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['mb'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['mk'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['pecah'], 0, ',', '.') }}</td>
                    <td style="font-weight: bold;">{{ number_format($row['total_telur'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['mati'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['afkir'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['populasi'], 0, ',', '.') }}</td>
                    <td>{{ $row['hdp'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <table>
            <tr>
                <td class="label">Total Produksi Bulan</td>
                <td>:</td>
                <td class="value">{{ number_format($totalProduksiBulan, 0, ',', '.') }} butir</td>
            </tr>
            <tr>
                <td class="label">Rata-rata HDP</td>
                <td>:</td>
                <td class="value">{{ $rataHDP }}%</td>
            </tr>
            <tr>
                <td class="label">Total Mortalitas Bulan</td>
                <td>:</td>
                <td class="value">{{ number_format($totalMortalitasBulan, 0, ',', '.') }} ekor</td>
            </tr>
            <tr>
                <td class="label">Populasi Awal Bulan</td>
                <td>:</td>
                <td class="value">{{ number_format($populasiAwalBulan, 0, ',', '.') }} ekor</td>
            </tr>
            <tr>
                <td class="label">Populasi Akhir Bulan</td>
                <td>:</td>
                <td class="value">{{ number_format($populasiAkhirBulan, 0, ',', '.') }} ekor</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <table>
            <tr>
                <td>
                    <p>Penanggung Jawab</p>
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

</body>
</html>
