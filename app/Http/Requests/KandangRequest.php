<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KandangRequest extends FormRequest
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
        $kandangId = $this->route('kandang');

        return [
            'nama_kandang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kandang', 'nama_kandang')->ignore($kandangId, 'id_kandang'),
            ],
            'kapasitas_kandang' => [
                'required',
                'integer',
                'min:1',
            ],
            'tahun_masuk' => [
                'required',
                'integer',
                'min:2000',
                'max:' . date('Y'),
            ],
            'is_active' => [
                'required',
                'boolean',
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
            'nama_kandang.required' => 'Nama kandang wajib diisi.',
            'nama_kandang.max' => 'Nama kandang maksimal 50 karakter.',
            'nama_kandang.unique' => 'Nama kandang sudah digunakan.',
            'kapasitas_kandang.required' => 'Kapasitas kandang wajib diisi.',
            'kapasitas_kandang.integer' => 'Kapasitas kandang harus berupa angka.',
            'kapasitas_kandang.min' => 'Kapasitas kandang harus lebih dari 0.',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi.',
            'tahun_masuk.integer' => 'Tahun masuk harus berupa angka.',
            'tahun_masuk.min' => 'Tahun masuk minimal 2000.',
            'tahun_masuk.max' => 'Tahun masuk tidak boleh melebihi tahun saat ini.',
            'is_active.required' => 'Status wajib dipilih.',
            'is_active.boolean' => 'Status tidak valid.',
        ];
    }
}
