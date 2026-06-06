<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\Kandang;
use App\Models\Pelanggan;
use App\Services\TransaksiPenjualanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    protected $penjualanService;

    public function __construct(TransaksiPenjualanService $penjualanService)
    {
        $this->penjualanService = $penjualanService;
    }

    /**
     * Dispatcher menu untuk Transaksi Penjualan.
     */
    public function index()
    {
        return view('transaksi.penjualan.index');
    }

    /**
     * Tampilkan form transaksi berdasarkan parameter ?jenis=telur|afkir|pupuk
     */
    public function create(Request $request)
    {
        $jenis = $request->query('jenis');
        if (!in_array($jenis, ['telur', 'afkir', 'pupuk'])) {
            return redirect()->route('transaksi.penjualan.index')
                ->with('error', 'Jenis penjualan tidak valid. Silakan pilih dari menu yang tersedia.');
        }

        $pelanggans = Pelanggan::where('is_active', true)->get();
        $akunKas = AkunKas::where('is_active', true)->get();
        
        $barangs = collect();
        $kandangs = collect();

        if ($jenis === 'telur') {
            $barangs = Barang::where('kategori_barang', 'Telur')
                ->where('dapat_dijual', true)
                ->get();
        } elseif ($jenis === 'pupuk') {
            $barangs = Barang::where('kategori_barang', 'Pupuk')
                ->where('dapat_dijual', true)
                ->get();
        } elseif ($jenis === 'afkir') {
            // Untuk ayam afkir, kita perlu data kandang untuk update populasi
            $kandangs = Kandang::where('is_active', true)->get();
            // Cari master data untuk Ayam Afkir
            $barangs = Barang::where('kategori_barang', 'Ayam')
                ->orWhere('nama_barang', 'like', '%Afkir%')
                ->where('dapat_dijual', true)
                ->get();
                
            if ($barangs->isEmpty()) {
                return redirect()->route('transaksi.penjualan.index')
                    ->with('error', 'Master data untuk Ayam Afkir tidak ditemukan. Pastikan ada barang dengan nama mengandung "Afkir" atau kategori "Ayam" yang dapat dijual.');
            }
        }

        return view('transaksi.penjualan.create', compact('jenis', 'pelanggans', 'akunKas', 'barangs', 'kandangs'));
    }

    /**
     * Proses simpan transaksi penjualan
     */
    public function store(Request $request)
    {
        // Validasi header
        $request->validate([
            'jenis' => 'required|in:telur,afkir,pupuk',
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'metode_pembayaran' => 'required|in:LUNAS,PIUTANG',
            'id_kandang' => 'required_if:jenis,afkir',
            'id_akun_kas' => 'required_if:metode_pembayaran,LUNAS',
            
            // Validasi detail array
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.kuantitas' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0.01',
        ]);

        $jenis = $request->input('jenis');
        $items = $request->input('items');

        // Kalkulasi total harga
        $totalHarga = 0;
        $details = [];

        foreach ($items as $item) {
            $subTotal = $item['kuantitas'] * $item['harga_satuan'];
            $totalHarga += $subTotal;

            $details[] = [
                'id_barang' => $item['id_barang'],
                'kuantitas' => $item['kuantitas'],
                'harga_satuan' => $item['harga_satuan'],
                'sub_total' => $subTotal,
            ];
        }

        $headerData = [
            'kategori_penjualan' => $jenis,
            'id_pelanggan' => $request->id_pelanggan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'id_akun_kas' => $request->id_akun_kas,
            'id_kandang' => $request->id_kandang,
            'catatan' => $request->catatan,
            'total_harga' => $totalHarga,
        ];

        try {
            $penjualan = $this->penjualanService->prosesTransaksi($headerData, $details);

            return redirect()->route('transaksi.penjualan.index')
                ->with('success', "Transaksi penjualan {$jenis} berhasil disimpan dengan No Faktur: {$penjualan->no_faktur_jual}");
                
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}
