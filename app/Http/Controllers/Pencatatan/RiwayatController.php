<?php

namespace App\Http\Controllers\Pencatatan;

use App\Http\Controllers\Controller;
use App\Models\Deplesi;
use App\Models\Kandang;
use App\Models\KonsumsiPakan;
use App\Models\KonsumsiVitamin;
use App\Models\ProduksiPupukKandang;
use App\Models\ProduksiTelur;
use App\Models\RiwayatAktivitas;
use App\Models\SuhuKandang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('tanggal', Carbon::today()->toDateString());
        $kandangId = $request->input('id_kandang');
        $typeFilter = $request->input('jenis_pencatatan');

        $kandangs = Kandang::where('is_active', true)->get();
        $records = collect();

        // 1. Produksi Telur
        if (!$typeFilter || $typeFilter === 'telur') {
            $query = ProduksiTelur::with(['batch.kandang', 'pengguna']);
            if ($date) $query->whereDate('tanggal_produksi', $date);
            if ($kandangId) {
                $query->whereHas('batch', function ($q) use ($kandangId) {
                    $q->where('id_kandang', $kandangId);
                });
            }
            $telur = $query->get()->map(function ($item) {
                $total = $item->jml_telur_rb + $item->jml_telur_mb + $item->jml_telur_mk + $item->jml_telur_pecah;
                return [
                    'id' => $item->id_produksi,
                    'type' => 'telur',
                    'type_label' => 'Produksi Telur',
                    'badge_variant' => 'success',
                    'waktu' => Carbon::parse($item->tanggal_produksi)->startOfDay(),
                    'kandang_nama' => $item->batch->kandang->nama_kandang ?? '-',
                    'batch_nama' => $item->batch->nama_batch ?? '-',
                    'ringkasan' => "RB:{$item->jml_telur_rb}, MB:{$item->jml_telur_mb}, MK:{$item->jml_telur_mk}, Pecah:{$item->jml_telur_pecah} = {$total} butir",
                    'pencatat' => $item->pengguna->nama_lengkap ?? 'Sistem',
                ];
            });
            $records = $records->concat($telur);
        }

        // 2. Konsumsi Pakan
        if (!$typeFilter || $typeFilter === 'pakan') {
            $query = KonsumsiPakan::with(['batch.kandang', 'barang', 'pengguna']);
            if ($date) $query->whereDate('tanggal_konsumsi', $date);
            if ($kandangId) {
                $query->whereHas('batch', function ($q) use ($kandangId) {
                    $q->where('id_kandang', $kandangId);
                });
            }
            $pakan = $query->get()->map(function ($item) {
                $waktuStr = $item->waktu_pemberian ? " {$item->waktu_pemberian}" : " 00:00:00";
                return [
                    'id' => $item->id_konsumsi,
                    'type' => 'pakan',
                    'type_label' => 'Konsumsi Pakan',
                    'badge_variant' => 'info',
                    'waktu' => Carbon::parse($item->tanggal_konsumsi . $waktuStr),
                    'kandang_nama' => $item->batch->kandang->nama_kandang ?? '-',
                    'batch_nama' => $item->batch->nama_batch ?? '-',
                    'ringkasan' => ($item->barang->nama_barang ?? 'Pakan') . ": " . number_format($item->jumlah_pakan_kg, 2) . " kg",
                    'pencatat' => $item->pengguna->nama_lengkap ?? 'Sistem',
                ];
            });
            $records = $records->concat($pakan);
        }

        // 3. Konsumsi Vitamin
        if (!$typeFilter || $typeFilter === 'vitamin') {
            $query = KonsumsiVitamin::with(['batch.kandang', 'barang', 'pengguna']);
            if ($date) $query->whereDate('tanggal_konsumsi', $date);
            if ($kandangId) {
                $query->whereHas('batch', function ($q) use ($kandangId) {
                    $q->where('id_kandang', $kandangId);
                });
            }
            $vitamin = $query->get()->map(function ($item) {
                $waktuStr = $item->waktu_pemberian ? " {$item->waktu_pemberian}" : " 00:00:00";
                $metode = $item->metode_pemberian ? ", metode: {$item->metode_pemberian}" : "";
                return [
                    'id' => $item->id_konsumsi_vitamin,
                    'type' => 'vitamin',
                    'type_label' => 'Konsumsi Vitamin',
                    'badge_variant' => 'info',
                    'waktu' => Carbon::parse($item->tanggal_konsumsi . $waktuStr),
                    'kandang_nama' => $item->batch->kandang->nama_kandang ?? '-',
                    'batch_nama' => $item->batch->nama_batch ?? '-',
                    'ringkasan' => ($item->barang->nama_barang ?? 'Vitamin') . ": " . number_format($item->dosis, 2) . " dosis{$metode}",
                    'pencatat' => $item->pengguna->nama_lengkap ?? 'Sistem',
                ];
            });
            $records = $records->concat($vitamin);
        }

        // 4. Deplesi
        if (!$typeFilter || $typeFilter === 'deplesi') {
            $query = Deplesi::with(['batch.kandang', 'pengguna']);
            if ($date) $query->whereDate('tanggal_deplesi', $date);
            if ($kandangId) {
                $query->whereHas('batch', function ($q) use ($kandangId) {
                    $q->where('id_kandang', $kandangId);
                });
            }
            $deplesi = $query->get()->map(function ($item) {
                $total = $item->jml_mati + $item->jml_afkir;
                return [
                    'id' => $item->id_deplesi,
                    'type' => 'deplesi',
                    'type_label' => 'Deplesi',
                    'badge_variant' => 'danger',
                    'waktu' => Carbon::parse($item->tanggal_deplesi)->startOfDay(),
                    'kandang_nama' => $item->batch->kandang->nama_kandang ?? '-',
                    'batch_nama' => $item->batch->nama_batch ?? '-',
                    'ringkasan' => "Mati:{$item->jml_mati}, Afkir:{$item->jml_afkir} = {$total} ekor",
                    'pencatat' => $item->pengguna->nama_lengkap ?? 'Sistem',
                ];
            });
            $records = $records->concat($deplesi);
        }

        // 5. Suhu Kandang
        if (!$typeFilter || $typeFilter === 'suhu') {
            $query = SuhuKandang::with(['kandang', 'pengguna']);
            if ($date) $query->whereDate('tanggal_waktu', $date);
            if ($kandangId) $query->where('id_kandang', $kandangId);
            $suhu = $query->get()->map(function ($item) {
                $kelembabanStr = $item->kelembaban ? ", Kelembaban: {$item->kelembaban}%" : "";
                return [
                    'id' => $item->id_suhu,
                    'type' => 'suhu',
                    'type_label' => 'Suhu Kandang',
                    'badge_variant' => 'warning',
                    'waktu' => Carbon::parse($item->tanggal_waktu),
                    'kandang_nama' => $item->kandang->nama_kandang ?? '-',
                    'batch_nama' => '-',
                    'ringkasan' => "Suhu: {$item->suhu}°C{$kelembabanStr}",
                    'pencatat' => $item->pengguna->nama_lengkap ?? 'Sistem',
                ];
            });
            $records = $records->concat($suhu);
        }

        // 6. Produksi Pupuk
        if (!$typeFilter || $typeFilter === 'pupuk') {
            $query = ProduksiPupukKandang::with(['kandang', 'pengguna']);
            if ($date) $query->whereDate('tanggal_kumpul', $date);
            if ($kandangId) $query->where('id_kandang', $kandangId);
            $pupuk = $query->get()->map(function ($item) {
                return [
                    'id' => $item->id_pupuk,
                    'type' => 'pupuk',
                    'type_label' => 'Produksi Pupuk',
                    'badge_variant' => 'success',
                    'waktu' => Carbon::parse($item->tanggal_kumpul)->startOfDay(),
                    'kandang_nama' => $item->kandang->nama_kandang ?? '-',
                    'batch_nama' => '-',
                    'ringkasan' => "{$item->jumlah_karung} karung, " . number_format($item->total_berat_kg, 2) . " kg",
                    'pencatat' => $item->pengguna->nama_lengkap ?? 'Sistem',
                ];
            });
            $records = $records->concat($pupuk);
        }

        // Sort by waktu descending
        $sortedRecords = $records->sortByDesc(function ($item) {
            return $item['waktu']->timestamp;
        })->values();

        // Paginate manually
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $paginatedItems = new LengthAwarePaginator(
            $sortedRecords->forPage($page, $perPage),
            $sortedRecords->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
        $paginatedItems->appends($request->all());

        return view('pencatatan.riwayat.index', compact('paginatedItems', 'kandangs', 'date', 'kandangId', 'typeFilter'));
    }

    public function destroy(Request $request, $type, $id)
    {
        // Fitur hapus hanya untuk Admin
        if (!Auth::user()->hasRole('Admin')) {
            abort(403, 'Hanya Admin yang dapat menghapus data.');
        }

        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        try {
            $info = '';
            switch ($type) {
                case 'telur':
                    $record = ProduksiTelur::findOrFail($id);
                    $info = "Produksi Telur ({$record->kode_produksi})";
                    $record->delete();
                    break;
                case 'pakan':
                    $record = KonsumsiPakan::findOrFail($id);
                    $info = "Konsumsi Pakan ({$record->kode_pakan})";
                    $record->delete();
                    break;
                case 'vitamin':
                    $record = KonsumsiVitamin::findOrFail($id);
                    $info = "Konsumsi Vitamin ({$record->kode_vitamin})";
                    $record->delete();
                    break;
                case 'deplesi':
                    $record = Deplesi::findOrFail($id);
                    $info = "Deplesi ({$record->kode_deplesi})";
                    $record->delete();
                    break;
                case 'suhu':
                    $record = SuhuKandang::findOrFail($id);
                    $info = "Suhu Kandang ({$record->kode_suhu})";
                    $record->delete();
                    break;
                case 'pupuk':
                    $record = ProduksiPupukKandang::findOrFail($id);
                    $info = "Produksi Pupuk ({$record->kode_pupuk})";
                    $record->delete();
                    break;
                default:
                    throw new \Exception('Jenis pencatatan tidak valid.');
            }

            RiwayatAktivitas::create([
                'id_pengguna' => Auth::id(),
                'aktivitas' => "Menghapus pencatatan {$info}. Alasan: {$request->alasan}",
            ]);

            return back()->with('success', "Pencatatan {$info} berhasil dihapus.");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
