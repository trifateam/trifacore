<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriBiayaRequest extends FormRequest
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
        $kategoriId = $this->route('kategori_biaya');

        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_biaya', 'nama_kategori')->ignore($kategoriId, 'id_kategori_biaya'),
            ],
            'keterangan' => [
                'nullable',
                'string',
                'max:255',
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
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ];
    }
}
