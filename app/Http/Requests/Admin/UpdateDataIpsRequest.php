<?php

namespace App\Http\Requests\Admin;

use App\Models\DataIps;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDataIpsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $dataIps = $this->route('dataIps');

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

        if ($dataIps instanceof DataIps) {
            $normalized['mahasiswa_id'] = $dataIps->mahasiswa_id;
        }

        $this->merge($normalized);
    }

    public function rules(): array
    {
        $dataIps = $this->route('dataIps');

        $dataIpsId = $dataIps instanceof DataIps
            ? $dataIps->getKey()
            : null;

        return [
            'mahasiswa_id' => [
                'required',
                'integer',
                Rule::exists('mahasiswas', 'id'),
                Rule::unique('data_ips', 'mahasiswa_id')
                    ->ignore($dataIpsId),
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
            'mahasiswa_id.required' => 'Mahasiswa wajib tersedia.',
            'mahasiswa_id.exists' => 'Mahasiswa tidak ditemukan.',
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
}