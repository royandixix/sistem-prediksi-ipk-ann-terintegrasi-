<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDataIpsRequest;
use App\Models\DataIps;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DataIpsController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $selectedMahasiswa = null;

        $nim = trim(
            $request
                ->string('mahasiswa')
                ->toString()
        );

        if ($nim !== '') {
            $selectedMahasiswa = Mahasiswa::query()
                ->with('dataIps')
                ->where('nim', $nim)
                ->first();

            if ($selectedMahasiswa?->dataIps) {
                return redirect()
                    ->route('operator.data-ips.create')
                    ->with(
                        'error',
                        'Mahasiswa tersebut sudah memiliki Data IPS.'
                    );
            }
        }

        $mahasiswas = Mahasiswa::query()
            ->aktif()
            ->whereDoesntHave('dataIps')
            ->orderBy('nama')
            ->get([
                'id',
                'nim',
                'nama',
                'angkatan',
                'program_studi',
            ]);

        $totalMahasiswa = Mahasiswa::query()
            ->aktif()
            ->count();

        $totalDataIps = DataIps::query()
            ->count();

        $remainingStudents = Mahasiswa::query()
            ->aktif()
            ->whereDoesntHave('dataIps')
            ->count();

        $recentDataIps = DataIps::query()
            ->with([
                'mahasiswa',
                'createdBy',
            ])
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view(
            'operator.data-ips.create',
            [
                'mahasiswas' => $mahasiswas,
                'selectedMahasiswa' => $selectedMahasiswa,
                'totalMahasiswa' => $totalMahasiswa,
                'totalDataIps' => $totalDataIps,
                'remainingStudents' => $remainingStudents,
                'recentDataIps' => $recentDataIps,
            ]
        );
    }

    public function store(
        StoreDataIpsRequest $request
    ): RedirectResponse {
        try {
            $dataIps = DB::transaction(
                function () use ($request): DataIps {
                    $validated = $request->validated();

                    $validated['is_complete'] = true;
                    $validated['validated_at'] = now();
                    $validated['created_by'] = $request->user()->id;

                    return DataIps::query()->create($validated);
                }
            );

            $dataIps->load('mahasiswa');

            return redirect()
                ->route('operator.data-ips.create')
                ->with(
                    'success',
                    'Data IPS '
                    . ($dataIps->mahasiswa?->nama ?? 'mahasiswa')
                    . ' berhasil disimpan.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data IPS gagal disimpan. Periksa kembali data yang dimasukkan.'
                );
        }
    }
}