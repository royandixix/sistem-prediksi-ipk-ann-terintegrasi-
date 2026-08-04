<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMahasiswaRequest;
use App\Http\Requests\Admin\UpdateMahasiswaRequest;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MahasiswaController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = in_array(
            (int) $request->input('per_page', 10),
            [10, 25, 50],
            true
        )
            ? (int) $request->input('per_page', 10)
            : 10;

        $mahasiswas = Mahasiswa::query()
            ->with([
                'dataIps',
                'prediksiTerbaru.modelAnn',
            ])
            ->withCount('prediksiIpks')
            ->search($request->string('search')->toString())
            ->angkatan($request->input('angkatan'))
            ->status($request->string('status')->toString())
            ->latest('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $angkatans = Mahasiswa::query()
            ->select('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        $statistics = [
            'total' => Mahasiswa::query()->count(),
            'aktif' => Mahasiswa::query()
                ->where('status', 'aktif')
                ->count(),
            'ips_lengkap' => Mahasiswa::query()
                ->whereHas(
                    'dataIps',
                    fn ($query) => $query->where(
                        'is_complete',
                        true
                    )
                )
                ->count(),
            'sudah_diprediksi' => Mahasiswa::query()
                ->has('prediksiIpks')
                ->count(),
        ];

        return view(
            'admin.mahasiswa.index',
            compact(
                'mahasiswas',
                'angkatans',
                'statistics',
                'perPage'
            )
        );
    }

    public function create(): View
    {
        return view('admin.mahasiswa.create');
    }

    public function store(
        StoreMahasiswaRequest $request
    ): RedirectResponse {
        $mahasiswa = DB::transaction(
            function () use ($request): Mahasiswa {
                return Mahasiswa::create([
                    ...$request->validated(),
                    'created_by' => $request->user()->id,
                ]);
            }
        );

        return redirect()
            ->route('admin.mahasiswa.show', $mahasiswa)
            ->with(
                'success',
                "Mahasiswa {$mahasiswa->nama} berhasil ditambahkan."
            );
    }

    public function show(Mahasiswa $mahasiswa): View
    {
        $mahasiswa->load([
            'createdBy',
            'dataIps.createdBy',
            'prediksiIpks' => fn ($query) => $query
                ->with([
                    'modelAnn',
                    'predictedBy',
                ])
                ->latest('predicted_at'),
        ]);

        return view(
            'admin.mahasiswa.show',
            compact('mahasiswa')
        );
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        return view(
            'admin.mahasiswa.edit',
            compact('mahasiswa')
        );
    }

    public function update(
        UpdateMahasiswaRequest $request,
        Mahasiswa $mahasiswa
    ): RedirectResponse {
        DB::transaction(
            function () use ($request, $mahasiswa): void {
                $mahasiswa->update($request->validated());
            }
        );

        return redirect()
            ->route('admin.mahasiswa.show', $mahasiswa)
            ->with(
                'success',
                "Data mahasiswa {$mahasiswa->nama} berhasil diperbarui."
            );
    }

    public function destroy(
        Mahasiswa $mahasiswa
    ): RedirectResponse {
        $mahasiswa->load('dataIps');

        if ($mahasiswa->prediksiIpks()->exists()) {
            return back()->with(
                'error',
                'Mahasiswa tidak dapat dihapus karena sudah memiliki hasil prediksi.'
            );
        }

        if (
            $mahasiswa->dataIps !== null
            && $mahasiswa->dataIps
                ->modelDatasets()
                ->exists()
        ) {
            return back()->with(
                'error',
                'Mahasiswa tidak dapat dihapus karena data IPS sudah digunakan dalam training atau testing ANN.'
            );
        }

        $nama = $mahasiswa->nama;

        try {
            DB::transaction(
                function () use ($mahasiswa): void {
                    $mahasiswa->delete();
                }
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Data mahasiswa gagal dihapus karena masih digunakan oleh data lain.'
            );
        }

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with(
                'success',
                "Mahasiswa {$nama} berhasil dihapus."
            );
    }
}