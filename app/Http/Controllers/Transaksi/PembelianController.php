<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\Supplier;
use App\Services\TransaksiPembelianService;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    protected $pembelianService;

    public function __construct(TransaksiPembelianService $pembelianService)
    {
        $this->pembelianService = $pembelianService;
    }

    /**
     * Dispatcher menu untuk Transaksi Pembelian.
     */
    public function index()
    {
        $stokMaterial = Barang::where('dapat_dibeli', true)
            ->where('kategori_barang', '!=', 'Ayam')
            ->get(['nama_barang', 'stok_barang', 'satuan']);
            
        return view('transaksi.pembelian.index', compact('stokMaterial'));
    }

    /**
     * Tampilkan form transaksi berdasarkan parameter ?jenis=material|pullet
     */
    public function create(Request $request)
    {
        $jenis = $request->query('jenis');
        if (!in_array($jenis, ['material', 'pullet'])) {
            return redirect()->route('transaksi.pembelian.index')
                ->with('error', 'Jenis pembelian tidak valid.');
        }

        $suppliers = Supplier::all();
        $akunKas = AkunKas::all();

        if ($jenis === 'material') {
            // Material: Pakan, Vitamin, Obat, dll yang dapat dibeli
            $barangs = Barang::where('dapat_dibeli', true)
                ->where('kategori_barang', '!=', 'Ayam')
                ->get();
            return view('transaksi.pembelian.create-material', compact('suppliers', 'akunKas', 'barangs', 'jenis'));
        } else {
            // Pullet: Tidak perlu list barang, form khusus
            return view('transaksi.pembelian.create-pullet', compact('suppliers', 'akunKas', 'jenis'));
        }
    }

    /**
     * Proses simpan transaksi pembelian
     */
    public function store(Request $request)
    {
        $jenis = $request->input('jenis');

        if ($jenis === 'material') {
            return $this->storeMaterial($request);
        } elseif ($jenis === 'pullet') {
            return $this->storePullet($request);
        }

        return back()->with('error', 'Jenis transaksi tidak dikenali.');
    }

    private function storeMaterial(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'metode_pembayaran' => 'required|in:LUNAS,TEMPO',
            'id_akun_kas' => 'required_if:metode_pembayaran,LUNAS',
            'tanggal_jatuh_tempo' => 'required_if:metode_pembayaran,TEMPO|date',
            
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.kuantitas' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0.01',
        ]);

        $items = $request->input('items');
        $totalPembelian = 0;
        $details = [];

        foreach ($items as $item) {
            $subTotal = $item['kuantitas'] * $item['harga_satuan'];
            $totalPembelian += $subTotal;

            $details[] = [
                'id_barang' => $item['id_barang'],
                'kuantitas' => $item['kuantitas'],
                'harga_satuan' => $item['harga_satuan'],
                'sub_total' => $subTotal,
            ];
        }

        $headerData = [
            'id_supplier' => $request->id_supplier,
            'metode_pembayaran' => $request->metode_pembayaran,
            'id_akun_kas' => $request->id_akun_kas,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'catatan' => $request->catatan,
            'total_pembelian' => $totalPembelian,
        ];

        try {
            $pembelian = $this->pembelianService->prosesBeliMaterial($headerData, $details);

            return redirect()->route('transaksi.pembelian.index')
                ->with('success', "Pembelian Material berhasil disimpan dengan No Faktur: {$pembelian->no_faktur_beli}");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    private function storePullet(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'metode_pembayaran' => 'required|in:LUNAS,TEMPO',
            'id_akun_kas' => 'required_if:metode_pembayaran,LUNAS',
            'tanggal_jatuh_tempo' => 'required_if:metode_pembayaran,TEMPO|date',
            
            'jenis_ayam' => 'required|string|max:50',
            'umur_masuk' => 'required|integer|min:0|max:52',
            'jumlah_awal' => 'required|integer|min:1',
            'harga_per_ekor' => 'required|numeric|min:1',
        ]);

        $totalPembelian = $request->jumlah_awal * $request->harga_per_ekor;

        $data = [
            'id_supplier' => $request->id_supplier,
            'metode_pembayaran' => $request->metode_pembayaran,
            'id_akun_kas' => $request->id_akun_kas,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'catatan' => $request->catatan,
            'total_pembelian' => $totalPembelian,
            
            'jenis_ayam' => $request->jenis_ayam,
            'umur_masuk' => $request->umur_masuk,
            'jumlah_awal' => $request->jumlah_awal,
            'harga_per_ekor' => $request->harga_per_ekor,
        ];

        try {
            $pembelian = $this->pembelianService->prosesBeliPullet($data);

            return redirect()->route('transaksi.pembelian.index')
                ->with('success', "Pembelian Pullet berhasil disimpan. Batch baru berstatus Pending telah dibuat. No Faktur: {$pembelian->no_faktur_beli}");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}
