<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian Pakan & Vitamin</title>
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
        <h2>Laporan Pembelian Pakan & Vitamin</h2>
        @php
            $namaBulan = \Carbon\Carbon::createFromFormat('n', $bulan)->translatedFormat('F');
        @endphp
        <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
        @if($supplier)
            <p>Supplier: {{ $supplier->nama_supplier }}</p>
        @else
            <p>Supplier: Semua Supplier</p>
        @endif
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">No. Nota</th>
                <th width="10%">Tanggal</th>
                <th class="text-left">Supplier</th>
                <th class="text-left">Jenis Material</th>
                <th width="8%">Qty (kg)</th>
                <th width="12%">Harga/Unit</th>
                <th width="12%">Total (Rp)</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailData as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row['no_nota'] }}</td>
                    <td class="text-center">{{ $row['tanggal'] }}</td>
                    <td class="text-left">{{ $row['supplier'] }}</td>
                    <td class="text-left">{{ $row['jenis_material'] }}</td>
                    <td>{{ number_format($row['qty'], 2, ',', '.') }}</td>
                    <td>{{ number_format($row['harga_unit'], 2, ',', '.') }}</td>
                    <td style="font-weight: bold;">{{ number_format($row['total'], 2, ',', '.') }}</td>
                    <td class="text-center">{{ $row['status'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data pembelian pakan & vitamin untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <table>
            <tr>
                <td class="label">Total Pembelian Bulan</td>
                <td>:</td>
                <td class="value">@rupiah($totalPembelian)</td>
            </tr>
            <tr>
                <td class="label">Total Qty Dibeli</td>
                <td>:</td>
                <td class="value">{{ number_format($totalQty, 2, ',', '.') }} kg</td>
            </tr>
            <tr>
                <td class="label">Rata-rata Harga/Kg</td>
                <td>:</td>
                <td class="value">@rupiah($rataHarga)</td>
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
