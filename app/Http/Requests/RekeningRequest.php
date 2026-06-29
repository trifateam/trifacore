<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RekeningRequest extends FormRequest
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
        $rekeningId = $this->route('rekening');

        $rules = [
            'nama_akun' => [
                'required',
                'string',
                'max:50',
                Rule::unique('akun_kas', 'nama_akun')->ignore($rekeningId, 'id_akun'),
            ],
            'kategori_akun' => [
                'required',
                'string',
                Rule::in(['Tunai', 'Bank', 'E-Wallet']),
            ],
            'no_rekening' => [
                $this->input('kategori_akun') !== 'Tunai' ? 'required' : 'nullable',
                'string',
                'max:50',
                Rule::unique('akun_kas', 'no_rekening')->ignore($rekeningId, 'id_akun'),
            ],
            'nama_pemilik' => [
                'required',
                'string',
                'max:100',
            ],
        ];

        // Saldo awal hanya saat tambah (store), bukan saat edit (update)
        if ($this->isMethod('POST')) {
            $rules['saldo'] = [
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
            'nama_akun.required' => 'Nama bank/kas wajib diisi.',
            'nama_akun.max' => 'Nama bank/kas maksimal 50 karakter.',
            'nama_akun.unique' => 'Nama bank/kas sudah digunakan.',
            'kategori_akun.required' => 'Kategori akun wajib dipilih.',
            'kategori_akun.in' => 'Kategori akun tidak valid.',
            'no_rekening.required' => 'Nomor rekening wajib diisi untuk kategori Bank/E-Wallet.',
            'no_rekening.max' => 'Nomor rekening maksimal 50 karakter.',
            'no_rekening.unique' => 'Nomor rekening sudah digunakan.',
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'nama_pemilik.max' => 'Nama pemilik maksimal 100 karakter.',
            'saldo.required' => 'Saldo awal wajib diisi.',
            'saldo.numeric' => 'Saldo awal harus berupa angka.',
            'saldo.min' => 'Saldo awal tidak boleh kurang dari 0.',
        ];
    }
}
