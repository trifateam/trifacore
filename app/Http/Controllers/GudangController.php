<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogPenyesuaianStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    /**
     * Tampilkan halaman inventory gudang
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        // 1. Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_barang', $request->kategori);
        }

        // 2. Filter Search (Nama Barang)
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
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
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $items = $sortedBarang->forPage($page, $perPage);
        $paginatedBarang = new \Illuminate\Pagination\LengthAwarePaginator(
            $items, 
            $sortedBarang->count(), 
            $perPage, 
            $page, 
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('gudang.index', compact('paginatedBarang', 'countKritis', 'countHabis'));
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
            DB::transaction(function () use ($request, $id_barang) {
                $barang = Barang::lockForUpdate()->findOrFail($id_barang);
                $stokLama = $barang->stok_barang;
                $stokBaru = $request->stok_fisik;

                if ($stokLama == $stokBaru) {
                    throw new \Exception("Stok fisik sama dengan stok sistem. Tidak ada perubahan yang disimpan.");
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
                \App\Services\AuditService::log("Melakukan stock opname pada barang '{$barang->nama_barang}' (Dari {$stokLama} menjadi {$stokBaru}). Alasan: {$request->alasan}");
            });

            return redirect()->route('gudang.index')->with('success', 'Berhasil melakukan penyesuaian stok.');
        } catch (\Exception $e) {
            return redirect()->route('gudang.index')->with('error', $e->getMessage());
        }
    }
}
