<?php

namespace App\Http\Controllers\Operator;

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
    public function create(Request $request): View
    {
        $activeModel = $this->activeModel();

        $candidates = Mahasiswa::query()
            ->with('dataIps')
            ->aktif()
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

        $predictedStudents = $candidateIds->isEmpty()
            ? 0
            : PrediksiIpk::query()
                ->whereIn('mahasiswa_id', $candidateIds)
                ->distinct()
                ->count('mahasiswa_id');

        $pendingStudents = max(
            0,
            $totalCandidates - $predictedStudents
        );

        $myPredictionCount = PrediksiIpk::query()
            ->where('predicted_by', $request->user()->id)
            ->count();

        $recentPredictions = PrediksiIpk::query()
            ->with([
                'mahasiswa',
                'modelAnn',
                'predictedBy',
            ])
            ->where('predicted_by', $request->user()->id)
            ->latestPrediction()
            ->limit(8)
            ->get();

        $latestResult = null;
        $predictionId = session('prediction_id');

        if ($predictionId) {
            $latestResult = PrediksiIpk::query()
                ->with([
                    'mahasiswa',
                    'dataIps',
                    'modelAnn',
                    'predictedBy',
                ])
                ->where('predicted_by', $request->user()->id)
                ->find($predictionId);
        }



        return view(
            'operator.prediksi-ipk.create',
            [
                'activeModel' => $activeModel,
                'candidates' => $candidates,
                'totalCandidates' => $totalCandidates,
                'predictedStudents' => $predictedStudents,
                'pendingStudents' => $pendingStudents,
                'myPredictionCount' => $myPredictionCount,
                'recentPredictions' => $recentPredictions,
                'latestResult' => $latestResult,
            ]
        );
    }

    public function store(
        StorePrediksiIpkRequest $request,
        AnnPredictionService $predictionService
    ): RedirectResponse {
        $activeModel = $this->activeModel();

        if (! $activeModel) {
            return redirect()
                ->route('operator.prediksi-ipk.create')
                ->withInput()
                ->with(
                    'error',
                    'Belum ada model ANN aktif yang siap digunakan. Hubungi administrator untuk menjalankan training model.'
                );
        }

        $dataIps = DataIps::query()
            ->with('mahasiswa')
            ->readyForPrediction()
            ->where(
                'mahasiswa_id',
                $request->integer('mahasiswa_id')
            )
            ->first();

        if (! $dataIps) {
            return redirect()
                ->route('operator.prediksi-ipk.create')
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
                ->route('operator.prediksi-ipk.create')
                ->with(
                    'success',
                    'Prediksi IPK '
                    . ($prediction->mahasiswa?->nama ?? 'mahasiswa')
                    . ' berhasil diproses.'
                )
                ->with(
                    'prediction_id',
                    $prediction->id
                );
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('operator.prediksi-ipk.create')
                ->withInput()
                ->with(
                    'error',
                    'Prediksi IPK gagal: '
                    . $exception->getMessage()
                );
        }
    }

    private function activeModel(): ?ModelAnn
    {
        return ModelAnn::query()
            ->with([
                'trainedBy',
            ])
            ->withCount('datasets')
            ->active()
            ->trained()
            ->latest('id')
            ->get()
            ->first(
                fn (ModelAnn $model): bool =>
                    $model->isReadyForPrediction()
                    && (int) $model->datasets_count > 0
                    && (int) $model->datasets_count
                        === (int) $model->total_data
            );
    }
}
