<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePrediksiIpkRequest;
use App\Models\DataIps;
use App\Models\Mahasiswa;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use App\Services\AnnPredictionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class PrediksiIpkController extends Controller
{
    public function index(Request $request): View
    {
        $activeModel = ModelAnn::query()
            ->with('trainedBy')
            ->active()
            ->trained()
            ->latest('id')
            ->first();

        $candidates = Mahasiswa::query()
            ->with('dataIps')
            ->where('status', 'aktif')
            ->whereHas('dataIps', function ($query): void {
                $query->readyForPrediction();
            })
            ->orderBy('nama')
            ->get([
                'id',
                'nim',
                'nama',
                'angkatan',
                'program_studi',
                'status',
            ]);

        $candidateIds = $candidates->pluck('id');
        $totalCandidates = $candidates->count();
        $totalPredictions = PrediksiIpk::query()->count();
        $predictedStudents = $candidateIds->isEmpty()
            ? 0
            : PrediksiIpk::query()
            ->whereIn('mahasiswa_id', $candidateIds)
            ->distinct()
            ->count('mahasiswa_id');
        $pendingStudents = max(0, $totalCandidates - $predictedStudents);

        $recentPredictions = PrediksiIpk::query()
            ->with(['mahasiswa', 'modelAnn', 'predictedBy'])
            ->latestPrediction()
            ->limit(8)
            ->get();

        $latestResult = null;
        $predictionId = session('prediction_id');

        if ($predictionId) {
            $latestResult = PrediksiIpk::query()
                ->with(['mahasiswa', 'modelAnn', 'predictedBy'])
                ->find($predictionId);
        }

        return view('admin.prediksi-ipk.index', [
            'activeModel' => $activeModel,
            'candidates' => $candidates,
            'totalCandidates' => $totalCandidates,
            'totalPredictions' => $totalPredictions,
            'predictedStudents' => $predictedStudents,
            'pendingStudents' => $pendingStudents,
            'recentPredictions' => $recentPredictions,
            'latestResult' => $latestResult,
        ]);
    }

    public function store(
        StorePrediksiIpkRequest $request,
        AnnPredictionService $predictionService
    ): RedirectResponse {
        $activeModel = ModelAnn::query()
            ->active()
            ->trained()
            ->latest('id')
            ->first();

        if (! $activeModel || ! $activeModel->isReadyForPrediction()) {
            return redirect()
                ->route('admin.prediksi-ipk.index')
                ->withInput()
                ->with(
                    'error',
                    'Belum ada model ANN aktif yang siap digunakan. Jalankan training model terlebih dahulu.'
                );
        }

        $dataIps = DataIps::query()
            ->with('mahasiswa')
            ->readyForPrediction()
            ->where('mahasiswa_id', $request->integer('mahasiswa_id'))
            ->first();

        if (! $dataIps) {
            return redirect()
                ->route('admin.prediksi-ipk.index')
                ->withInput()
                ->withErrors([
                    'mahasiswa_id' => 'Data IPS Semester 1–5 mahasiswa tidak ditemukan atau belum lengkap.',
                ]);
        }

        try {
            $prediction = $predictionService->predict(
                dataIps: $dataIps,
                model: $activeModel,
                userId: $request->user()->id
            );

            return redirect()
                ->route('admin.prediksi-ipk.index')
                ->with(
                    'success',
                    'Prediksi IPK '
                        . $prediction->mahasiswa->nama
                        . ' berhasil diproses.'
                )
                ->with('prediction_id', $prediction->id);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('admin.prediksi-ipk.index')
                ->withInput()
                ->with('error', 'Prediksi IPK gagal: ' . $exception->getMessage());
        }
    }
}
