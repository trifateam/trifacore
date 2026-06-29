<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $barangId = $this->route('barang');

        $rules = [
            'nama_barang' => [
                'required',
                'string',
                'max:100',
                Rule::unique('barang', 'nama_barang')->ignore($barangId, 'id_barang'),
            ],
            'kategori_barang' => [
                'required',
                'string',
                Rule::in(['Telur', 'Pakan', 'Vitamin', 'Pupuk', 'Obat', 'Lainnya']),
            ],
            'sku' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('barang', 'sku')->ignore($barangId, 'id_barang'),
            ],
            'satuan' => [
                'required',
                'string',
                Rule::in(['butir', 'kg', 'karung', 'liter', 'box', 'botol', 'ekor']),
            ],
            'stok_minimum' => [
                'required',
                'numeric',
                'min:0',
            ],
            'harga' => [
                'required',
                'numeric',
                'min:0',
            ],
            'dapat_dijual' => [
                'required',
                'boolean',
            ],
            'dapat_dibeli' => [
                'required',
                'boolean',
            ],
        ];

        // Stok awal hanya saat tambah (store), bukan saat edit (update)
        if ($this->isMethod('POST')) {
            $rules['stok_barang'] = [
                'required',
                'numeric',
                'min:0',
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 100 karakter.',
            'nama_barang.unique' => 'Nama barang sudah digunakan.',
            'kategori_barang.required' => 'Kategori barang wajib dipilih.',
            'kategori_barang.in' => 'Kategori barang tidak valid.',
            'sku.max' => 'SKU maksimal 50 karakter.',
            'sku.unique' => 'SKU sudah digunakan oleh barang lain.',
            'satuan.required' => 'Satuan wajib dipilih.',
            'satuan.in' => 'Satuan tidak valid.',
            'stok_barang.required' => 'Stok awal wajib diisi.',
            'stok_barang.numeric' => 'Stok awal harus berupa angka.',
            'stok_barang.min' => 'Stok awal tidak boleh kurang dari 0.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'stok_minimum.numeric' => 'Stok minimum harus berupa angka.',
            'stok_minimum.min' => 'Stok minimum tidak boleh kurang dari 0.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'dapat_dijual.required' => 'Field dapat dijual wajib diisi.',
            'dapat_dibeli.required' => 'Field dapat dibeli wajib diisi.',
        ];
    }
}
