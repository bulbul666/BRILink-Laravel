<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal'            => ['required', 'date', 'before_or_equal:today'],
            'jenis_transaksi_id' => ['required', 'exists:jenis_transaksi,id'],
            'pelanggan_id'       => ['nullable', 'exists:pelanggan,id'],
            'nominal'            => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'biaya_admin'        => ['required', 'numeric', 'min:0'],
            'keterangan'         => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required'            => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal'     => 'Tanggal tidak boleh melebihi hari ini.',
            'jenis_transaksi_id.required' => 'Jenis transaksi wajib dipilih.',
            'nominal.required'            => 'Nominal wajib diisi.',
            'nominal.numeric'             => 'Nominal harus berupa angka.',
            'nominal.min'                 => 'Nominal minimal 1.',
            'biaya_admin.required'        => 'Biaya admin wajib diisi.',
            'biaya_admin.min'             => 'Biaya admin tidak boleh negatif.',
            'keterangan.max'              => 'Keterangan maksimal 255 karakter.',
        ];
    }

}
