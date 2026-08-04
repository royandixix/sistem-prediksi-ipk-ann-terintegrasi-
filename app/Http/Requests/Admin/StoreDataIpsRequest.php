<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDataIpsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $fields = [
            'ips_1',
            'ips_2',
            'ips_3',
            'ips_4',
            'ips_5',
            'ipk_akhir_aktual',
        ];

        $normalized = [];

        foreach ($fields as $field) {
            $value = $this->input($field);

            $normalized[$field] = $value === null || $value === ''
                ? null
                : str_replace(',', '.', trim((string) $value));
        }

        $this->merge($normalized);
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id' => [
                'required',
                'integer',
                Rule::exists('mahasiswas', 'id'),
                Rule::unique('data_ips', 'mahasiswa_id'),
            ],

            'ips_1' => [
                'required',
                'numeric',
                'between:0,4',
            ],

            'ips_2' => [
                'required',
                'numeric',
                'between:0,4',
            ],

            'ips_3' => [
                'required',
                'numeric',
                'between:0,4',
            ],

            'ips_4' => [
                'required',
                'numeric',
                'between:0,4',
            ],

            'ips_5' => [
                'required',
                'numeric',
                'between:0,4',
            ],

            'ipk_akhir_aktual' => [
                'nullable',
                'numeric',
                'between:0,4',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'mahasiswa_id.required' => 'Mahasiswa wajib dipilih.',
            'mahasiswa_id.exists' => 'Mahasiswa yang dipilih tidak ditemukan.',
            'mahasiswa_id.unique' => 'Mahasiswa tersebut sudah memiliki Data IPS.',

            'ips_1.required' => 'IPS Semester 1 wajib diisi.',
            'ips_2.required' => 'IPS Semester 2 wajib diisi.',
            'ips_3.required' => 'IPS Semester 3 wajib diisi.',
            'ips_4.required' => 'IPS Semester 4 wajib diisi.',
            'ips_5.required' => 'IPS Semester 5 wajib diisi.',

            'ips_1.numeric' => 'IPS Semester 1 harus berupa angka.',
            'ips_2.numeric' => 'IPS Semester 2 harus berupa angka.',
            'ips_3.numeric' => 'IPS Semester 3 harus berupa angka.',
            'ips_4.numeric' => 'IPS Semester 4 harus berupa angka.',
            'ips_5.numeric' => 'IPS Semester 5 harus berupa angka.',

            'ips_1.between' => 'IPS Semester 1 harus berada antara 0.00 sampai 4.00.',
            'ips_2.between' => 'IPS Semester 2 harus berada antara 0.00 sampai 4.00.',
            'ips_3.between' => 'IPS Semester 3 harus berada antara 0.00 sampai 4.00.',
            'ips_4.between' => 'IPS Semester 4 harus berada antara 0.00 sampai 4.00.',
            'ips_5.between' => 'IPS Semester 5 harus berada antara 0.00 sampai 4.00.',

            'ipk_akhir_aktual.numeric' => 'IPK akhir aktual harus berupa angka.',
            'ipk_akhir_aktual.between' => 'IPK akhir aktual harus berada antara 0.00 sampai 4.00.',

            'catatan.max' => 'Catatan maksimal terdiri dari 1.000 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mahasiswa_id' => 'mahasiswa',
            'ips_1' => 'IPS Semester 1',
            'ips_2' => 'IPS Semester 2',
            'ips_3' => 'IPS Semester 3',
            'ips_4' => 'IPS Semester 4',
            'ips_5' => 'IPS Semester 5',
            'ipk_akhir_aktual' => 'IPK akhir aktual',
            'catatan' => 'catatan',
        ];
    }
}