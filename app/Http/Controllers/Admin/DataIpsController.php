<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDataIpsRequest;
use App\Http\Requests\Admin\UpdateDataIpsRequest;
use App\Models\DataIps;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DataIpsController extends Controller
{
    public function index(Request $request): View
    {
        $requestedPerPage = (int) $request->input(
            'per_page',
            10
        );

        $perPage = in_array(
            $requestedPerPage,
            [10, 25, 50],
            true
        )
            ? $requestedPerPage
            : 10;

        $status = $request
            ->string('status')
            ->toString();

        $query = DataIps::query()
            ->with([
                'mahasiswa',
                'createdBy',
            ])
            ->withCount([
                'modelDatasets',
                'prediksiIpks',
            ])
            ->search(
                $request
                    ->string('search')
                    ->toString()
            );

        if ($status === 'complete') {
            $query->complete();
        }

        if ($status === 'with_actual') {
            $query->hasActualIpk();
        }

        if ($status === 'without_actual') {
            $query->whereNull(
                'ipk_akhir_aktual'
            );
        }

        if ($status === 'estimated') {
            $query->where('is_estimated', true);
        }

        if ($status === 'original') {
            $query->where('is_estimated', false);
        }

        if ($status === 'locked') {
            $query->where(
                function ($lockedQuery): void {
                    $lockedQuery
                        ->whereHas('modelDatasets')
                        ->orWhereHas('prediksiIpks');
                }
            );
        }

        if ($status === 'unlocked') {
            $query
                ->whereDoesntHave('modelDatasets')
                ->whereDoesntHave('prediksiIpks');
        }

        $dataIpsCollection = $query
            ->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        $averageIps = DataIps::query()
            ->readyForPrediction()
            ->selectRaw(
                'AVG((ips_1 + ips_2 + ips_3 + ips_4 + ips_5) / 5) AS average_ips'
            )
            ->value('average_ips');

        $statistics = [
            'total' => DataIps::query()
                ->count(),

            'complete' => DataIps::query()
                ->readyForPrediction()
                ->count(),

            'with_actual' => DataIps::query()
                ->hasActualIpk()
                ->count(),

            'estimated' => DataIps::query()
                ->where('is_estimated', true)
                ->count(),

            'locked' => DataIps::query()
                ->where(
                    function ($lockedQuery): void {
                        $lockedQuery
                            ->whereHas('modelDatasets')
                            ->orWhereHas('prediksiIpks');
                    }
                )
                ->count(),

            'average_ips' => round(
                (float) ($averageIps ?? 0),
                3
            ),
        ];

        return view(
            'admin.data-ips.index',
            [
                'dataIpsCollection' => $dataIpsCollection,
                'statistics' => $statistics,
                'perPage' => $perPage,
            ]
        );
    }

    public function create(
        Request $request
    ): View|RedirectResponse {
        $selectedMahasiswa = null;

        $nim = trim(
            $request
                ->string('mahasiswa')
                ->toString()
        );

        if ($nim !== '') {
            $selectedMahasiswa = Mahasiswa::query()
                ->with('dataIps')
                ->where(
                    'nim',
                    $nim
                )
                ->first();

            if ($selectedMahasiswa?->dataIps) {
                $dataIps = $selectedMahasiswa->dataIps;

                if ($this->isLocked($dataIps)) {
                    return redirect()
                        ->route(
                            'admin.data-ips.show',
                            $dataIps
                        )
                        ->with(
                            'error',
                            $this->lockedMessage($dataIps)
                        );
                }

                return redirect()
                    ->route(
                        'admin.data-ips.edit',
                        $dataIps
                    )
                    ->with(
                        'info',
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

        return view(
            'admin.data-ips.create',
            [
                'mahasiswas' => $mahasiswas,
                'selectedMahasiswa' => $selectedMahasiswa,
            ]
        );
    }

    public function store(
        StoreDataIpsRequest $request
    ): RedirectResponse {
        $dataIps = DB::transaction(
            function () use ($request): DataIps {
                $validated = $request->validated();

                $validated['is_complete'] = true;
                $validated['validated_at'] = now();
                $validated['created_by'] = $request->user()->id;

                return DataIps::query()
                    ->create($validated);
            }
        );

        $dataIps->load('mahasiswa');

        return redirect()
            ->route(
                'admin.data-ips.show',
                $dataIps
            )
            ->with(
                'success',
                "Data IPS {$dataIps->mahasiswa?->nama} berhasil disimpan."
            );
    }

    public function show(
        DataIps $dataIps
    ): View {
        $dataIps->load([
            'mahasiswa',
            'createdBy',
            'modelDatasets.modelAnn',
            'prediksiIpks.modelAnn',
        ]);

        $isLocked = $this->isLocked(
            $dataIps
        );

        $lockedMessage = $isLocked
            ? $this->lockedMessage($dataIps)
            : null;

        return view(
            'admin.data-ips.show',
            [
                'dataIps' => $dataIps,
                'isLocked' => $isLocked,
                'lockedMessage' => $lockedMessage,
            ]
        );
    }

    public function edit(
        DataIps $dataIps
    ): View|RedirectResponse {
        if ($this->isLocked($dataIps)) {
            return redirect()
                ->route(
                    'admin.data-ips.show',
                    $dataIps
                )
                ->with(
                    'error',
                    $this->lockedMessage($dataIps)
                );
        }

        $dataIps->load('mahasiswa');

        return view(
            'admin.data-ips.edit',
            [
                'dataIps' => $dataIps,
            ]
        );
    }

    public function update(
        UpdateDataIpsRequest $request,
        DataIps $dataIps
    ): RedirectResponse {
        if ($this->isLocked($dataIps)) {
            return redirect()
                ->route(
                    'admin.data-ips.show',
                    $dataIps
                )
                ->with(
                    'error',
                    $this->lockedMessage($dataIps)
                );
        }

        DB::transaction(
            function () use (
                $request,
                $dataIps
            ): void {
                $validated = $request->validated();

                $validated['is_complete'] = true;
                $validated['validated_at'] = now();

                $dataIps->update(
                    $validated
                );
            }
        );

        return redirect()
            ->route(
                'admin.data-ips.show',
                $dataIps
            )
            ->with(
                'success',
                'Data IPS mahasiswa berhasil diperbarui.'
            );
    }

    public function destroy(
        DataIps $dataIps
    ): RedirectResponse {
        $dataIps->load('mahasiswa');

        if ($this->isLocked($dataIps)) {
            return back()->with(
                'error',
                $this->lockedMessage($dataIps)
            );
        }

        $nama = $dataIps->mahasiswa?->nama
            ?? 'mahasiswa';

        try {
            DB::transaction(
                function () use ($dataIps): void {
                    $dataIps->delete();
                }
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Data IPS gagal dihapus karena masih digunakan oleh data lain.'
            );
        }

        return redirect()
            ->route('admin.data-ips.index')
            ->with(
                'success',
                "Data IPS {$nama} berhasil dihapus."
            );
    }

    private function isLocked(
        DataIps $dataIps
    ): bool {
        return $dataIps
            ->modelDatasets()
            ->exists()
            || $dataIps
                ->prediksiIpks()
                ->exists();
    }

    private function lockedMessage(
        DataIps $dataIps
    ): string {
        $usedForTraining = $dataIps
            ->modelDatasets()
            ->exists();

        $usedForPrediction = $dataIps
            ->prediksiIpks()
            ->exists();

        if (
            $usedForTraining
            && $usedForPrediction
        ) {
            return 'Data IPS tidak dapat diubah atau dihapus karena sudah digunakan dalam training ANN dan proses prediksi.';
        }

        if ($usedForTraining) {
            return 'Data IPS tidak dapat diubah atau dihapus karena sudah digunakan dalam dataset training atau testing ANN.';
        }

        return 'Data IPS tidak dapat diubah atau dihapus karena sudah digunakan dalam proses prediksi IPK.';
    }
}