<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogPenyesuaianStok;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public function stokKonsumsi(Request $request)
    {
        return $this->getIndexView($request, 'konsumsi');
    }

    public function stokProduksi(Request $request)
    {
        return $this->getIndexView($request, 'produksi');
    }

    public function riwayatPenyesuaian(Request $request)
    {
        $logs = LogPenyesuaianStok::with(['barang', 'pengguna'])
            ->latest('created_at')
            ->paginate(15);

        return view('gudang.riwayat', compact('logs'));
    }

    private function getIndexView(Request $request, $type)
    {
        $query = Barang::query();

        if ($type === 'konsumsi') {
            $query->where('dapat_dibeli', 1);
            $pageTitle = 'Stok Konsumsi (Kategori Beli)';
            $pageSubtitle = 'Monitor persediaan barang yang dibeli (pakan, vitamin, obat, dll).';
        } else {
            $query->where('dapat_dijual', 1);
            $pageTitle = 'Stok Hasil Produksi (Kategori Jual)';
            $pageSubtitle = 'Monitor persediaan barang hasil produksi (telur, pupuk, dll).';
        }

        // 1. Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_barang', $request->kategori);
        }

        // 2. Filter Search (Nama Barang)
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%'.$request->search.'%');
        }

        $allBarang = $query->get();

        // Calculate Status dynamically and attach to object
        $countKritis = 0;
        $countHabis = 0;

        $mappedBarang = $allBarang->map(function ($barang) use (&$countKritis, &$countHabis) {
            $stok = $barang->stok_barang;
            $min = $barang->stok_minimum;

            if ($stok == 0) {
                $status = 'Habis';
                $badge = 'dark';
                $orderPriority = 1;
                $countHabis++;
            } elseif ($stok <= $min) {
                $status = 'Kritis';
                $badge = 'danger';
                $orderPriority = 2;
                $countKritis++;
            } elseif ($stok <= ($min * 2)) {
                $status = 'Warning';
                $badge = 'warning';
                $orderPriority = 3;
            } else {
                $status = 'Normal';
                $badge = 'success';
                $orderPriority = 4;
            }

            $barang->status_stok = $status;
            $barang->badge_color = $badge;
            $barang->order_priority = $orderPriority;

            return $barang;
        });

        // 3. Filter Status Stok (after calculation)
        if ($request->filled('status')) {
            $statusFilter = $request->status;
            $mappedBarang = $mappedBarang->filter(function ($barang) use ($statusFilter) {
                return $barang->status_stok === $statusFilter;
            });
        }

        // 4. Sorting (Habis -> Kritis -> Warning -> Normal) then by name
        $sortedBarang = $mappedBarang->sortBy([
            ['order_priority', 'asc'],
            ['nama_barang', 'asc'],
        ])->values();

        // Manual Pagination since we manipulated a Collection
        $perPage = 15;
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $sortedBarang->forPage($page, $perPage);
        $paginatedBarang = new LengthAwarePaginator(
            $items,
            $sortedBarang->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('gudang.index', compact('paginatedBarang', 'countKritis', 'countHabis', 'pageTitle', 'pageSubtitle'));
    }

    /**
     * Tampilkan form stock opname di halaman penuh.
     */
    public function showAdjustForm($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);

        return view('gudang.adjust', compact('barang'));
    }

    /**
     * Proses penyesuaian stok (Stock Opname)
     */
    public function adjust(Request $request, $id_barang)
    {
        $request->validate([
            'stok_fisik' => 'required|numeric|min:0',
            'alasan' => 'required|string|max:255',
        ]);

        try {
            $barang = DB::transaction(function () use ($request, $id_barang) {
                $barang = Barang::lockForUpdate()->findOrFail($id_barang);
                $stokLama = $barang->stok_barang;
                $stokBaru = $request->stok_fisik;

                if ($stokLama == $stokBaru) {
                    throw new \Exception('Stok fisik sama dengan stok sistem. Tidak ada perubahan yang disimpan.');
                }

                // Create Log
                LogPenyesuaianStok::create([
                    'id_barang' => $barang->id_barang,
                    'id_pengguna' => Auth::id(),
                    'stok_lama' => $stokLama,
                    'stok_baru' => $stokBaru,
                    'alasan' => $request->alasan,
                ]);

                // Update Stok
                $barang->stok_barang = $stokBaru;
                $barang->save();

                // Catat Aktivitas
                AuditService::log("Melakukan stock opname pada barang '{$barang->nama_barang}' (Dari {$stokLama} menjadi {$stokBaru}). Alasan: {$request->alasan}");

                return $barang;
            });

            $redirectRoute = $barang->dapat_dibeli ? 'gudang.stok-konsumsi' : 'gudang.stok-produksi';

            return redirect()->route($redirectRoute)->with('success', 'Berhasil melakukan penyesuaian stok.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
