<?php

namespace App\Http\Requests\Admin;

use App\Models\DataIps;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePrediksiIpkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'mahasiswa_id' => $this->filled('mahasiswa_id')
                ? (int) $this->input('mahasiswa_id')
                : null,
        ]);
    }


    public function rules(): array
    {
        return [
            'mahasiswa_id' => [
                'required',
                'integer',
                Rule::exists('mahasiswas', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('status', 'aktif')
                    ),
            ],
        ];
    }

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(function (
            Validator $validator
        ): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $dataIps = DataIps::query()
                ->where(
                    'mahasiswa_id',
                    $this->integer('mahasiswa_id')
                )
                ->first();

            if (!$dataIps) {
                $validator->errors()->add(
                    'mahasiswa_id',
                    'Mahasiswa belum memiliki Data IPS.'
                );

                return;
            }

            $values = [
                $dataIps->ips_1,
                $dataIps->ips_2,
                $dataIps->ips_3,
                $dataIps->ips_4,
                $dataIps->ips_5,
            ];

            foreach ($values as $value) {
                if (
                    $value === null
                    || (float) $value < 0
                    || (float) $value > 4
                ) {
                    $validator->errors()->add(
                        'mahasiswa_id',
                        'Data IPS Semester 1–5 mahasiswa belum lengkap atau tidak valid.'
                    );

                    return;
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'mahasiswa_id.required' => 'Mahasiswa wajib dipilih.',
            'mahasiswa_id.integer' => 'Data mahasiswa tidak valid.',
            'mahasiswa_id.exists' => 'Mahasiswa tidak ditemukan atau tidak aktif.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mahasiswa_id' => 'mahasiswa',
        ];
    }
}