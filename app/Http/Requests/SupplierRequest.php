<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $supplierId = $this->route('supplier');

        return [
            'nama_supplier' => [
                'required',
                'string',
                'max:100',
                Rule::unique('supplier', 'nama_supplier')->ignore($supplierId, 'id_supplier'),
            ],
            'alamat_supplier' => [
                'required',
                'string',
            ],
            'kontak_supplier' => [
                'required',
                'string',
                'max:20',
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
            ],
            'nama_pic' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.max' => 'Nama supplier maksimal 100 karakter.',
            'nama_supplier.unique' => 'Nama supplier sudah digunakan.',
            'alamat_supplier.required' => 'Alamat supplier wajib diisi.',
            'kontak_supplier.required' => 'No. Telp / Kontak wajib diisi.',
            'kontak_supplier.max' => 'No. Telp / Kontak maksimal 20 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'nama_pic.max' => 'Nama PIC maksimal 100 karakter.',
        ];
    }
}
