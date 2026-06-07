<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the supplier.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_supplier', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only('search'));

        return view('master-data.supplier.index', compact('suppliers', 'search'));
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(SupplierRequest $request)
    {
        Supplier::create([
            'id_pengguna' => Auth::id(),
            'nama_supplier' => $request->nama_supplier,
            'alamat_supplier' => $request->alamat_supplier,
            'kontak_supplier' => $request->kontak_supplier,
            'email' => $request->email,
            'nama_pic' => $request->nama_pic,
        ]);

        \App\Services\AuditService::log('Menambah supplier baru: ' . $request->nama_supplier);

        return redirect()->route('master-data.supplier.index')
            ->with('success', 'Data supplier berhasil ditambahkan.');
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(SupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'alamat_supplier' => $request->alamat_supplier,
            'kontak_supplier' => $request->kontak_supplier,
            'email' => $request->email,
            'nama_pic' => $request->nama_pic,
        ]);

        \App\Services\AuditService::log('Mengedit supplier: ' . $request->nama_supplier);

        return redirect()->route('master-data.supplier.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Cek apakah supplier masih punya transaksi pembelian aktif
        $hasTransactions = DB::table('pembelian')
            ->where('id_supplier', $supplier->id_supplier)
            ->exists();

        if ($hasTransactions) {
            return redirect()->route('master-data.supplier.index')
                ->with('error', "Supplier \"{$supplier->nama_supplier}\" tidak bisa dihapus karena masih memiliki transaksi pembelian.");
        }

        $supplierName = $supplier->nama_supplier;
        $supplier->delete();

        \App\Services\AuditService::log('Menghapus supplier: ' . $supplierName);

        return redirect()->route('master-data.supplier.index')
            ->with('success', 'Data supplier berhasil dihapus.');
    }
}
