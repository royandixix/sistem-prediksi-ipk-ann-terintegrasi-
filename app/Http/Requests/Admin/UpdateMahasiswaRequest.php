<?php

namespace App\Http\Requests\Admin;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $mahasiswa = $this->route('mahasiswa');

        $nim = preg_replace(
            '/\s+/',
            '',
            (string) $this->input('nim')
        );

        $status = $mahasiswa instanceof Mahasiswa
            ? $mahasiswa->status
            : 'aktif';

        $this->merge([
            'nim' => Str::upper($nim ?? ''),
            'nama' => Str::of(
                (string) $this->input('nama')
            )->squish()->toString(),
            'angkatan' => 2023,
            'program_studi' => 'Teknik Informatika',
            'status' => $status,
        ]);
    }

    public function rules(): array
    {
        $mahasiswa = $this->route('mahasiswa');

        $mahasiswaId = $mahasiswa instanceof Mahasiswa
            ? $mahasiswa->getKey()
            : null;

        return [
            'nim' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9.-]+$/',
                Rule::unique('mahasiswas', 'nim')
                    ->ignore($mahasiswaId),
            ],

            'nama' => [
                'required',
                'string',
                'min:3',
                'max:150',
            ],

            'angkatan' => [
                'required',
                'integer',
                Rule::in([2023]),
            ],

            'program_studi' => [
                'required',
                'string',
                Rule::in(['Teknik Informatika']),
            ],

            'status' => [
                'required',
                'string',
                Rule::in(['aktif', 'nonaktif']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required' => 'NIM mahasiswa wajib diisi.',
            'nim.string' => 'Format NIM mahasiswa tidak valid.',
            'nim.max' => 'NIM maksimal terdiri dari 30 karakter.',
            'nim.regex' => 'NIM hanya boleh berisi huruf, angka, titik, dan tanda hubung.',
            'nim.unique' => 'NIM tersebut sudah digunakan mahasiswa lain.',

            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.string' => 'Format nama mahasiswa tidak valid.',
            'nama.min' => 'Nama mahasiswa minimal terdiri dari 3 karakter.',
            'nama.max' => 'Nama mahasiswa maksimal terdiri dari 150 karakter.',

            'angkatan.required' => 'Angkatan mahasiswa wajib diisi.',
            'angkatan.integer' => 'Angkatan mahasiswa harus berupa tahun.',
            'angkatan.in' => 'Penelitian ini hanya menggunakan mahasiswa angkatan 2023.',

            'program_studi.required' => 'Program studi wajib diisi.',
            'program_studi.string' => 'Format program studi tidak valid.',
            'program_studi.in' => 'Program studi harus Teknik Informatika.',

            'status.required' => 'Status mahasiswa wajib tersedia.',
            'status.in' => 'Status mahasiswa tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nim' => 'NIM',
            'nama' => 'nama mahasiswa',
            'angkatan' => 'angkatan',
            'program_studi' => 'program studi',
            'status' => 'status mahasiswa',
        ];
    }
}