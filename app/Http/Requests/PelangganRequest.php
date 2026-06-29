<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PelangganRequest extends FormRequest
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
        $pelangganId = $this->route('pelanggan');

        return [
            'nama_lengkap' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pelanggan', 'nama_lengkap')->ignore($pelangganId, 'id_pelanggan'),
            ],
            'alamat' => [
                'required',
                'string',
            ],
            'kontak' => [
                'required',
                'string',
                'max:20',
            ],
            'kategori' => [
                'required',
                'string',
                Rule::in(['Distributor', 'Retail', 'Personal']),
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
            'nama_lengkap.required' => 'Nama pelanggan wajib diisi.',
            'nama_lengkap.max' => 'Nama pelanggan maksimal 100 karakter.',
            'nama_lengkap.unique' => 'Nama pelanggan sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kontak.required' => 'No. Telp / Kontak wajib diisi.',
            'kontak.max' => 'No. Telp / Kontak maksimal 20 karakter.',
            'kategori.required' => 'Kategori pelanggan wajib dipilih.',
            'kategori.in' => 'Kategori pelanggan tidak valid.',
        ];
    }
}
