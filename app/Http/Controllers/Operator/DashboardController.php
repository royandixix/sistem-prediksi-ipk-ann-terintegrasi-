<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DataIps;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $candidateCount = DataIps::query()
            ->readyForPrediction()
            ->distinct()
            ->count('mahasiswa_id');

        $predictedStudentCount = PrediksiIpk::query()
            ->whereIn(
                'mahasiswa_id',
                DataIps::query()
                    ->readyForPrediction()
                    ->select('mahasiswa_id')
            )
            ->distinct()
            ->count('mahasiswa_id');

        $myPredictionCount = PrediksiIpk::query()
            ->where('predicted_by', $userId)
            ->count();

        $pendingCount = max(
            0,
            $candidateCount - $predictedStudentCount
        );

        $predictionRate = $candidateCount > 0
            ? min(
                100,
                (int) round(
                    ($predictedStudentCount / $candidateCount) * 100
                )
            )
            : 0;

        $activeModel = ModelAnn::query()
            ->withCount('datasets')
            ->active()
            ->trained()
            ->latest('id')
            ->get()
            ->first(
                fn (ModelAnn $model): bool =>
                    $model->isReadyForPrediction()
                    && (int) $model->datasets_count > 0
                    && (int) $model->datasets_count === (int) $model->total_data
            );

        $myPredictionQuery = PrediksiIpk::query()
            ->where('predicted_by', $userId);

        $averagePrediction = (float) (
            (clone $myPredictionQuery)
                ->avg('ipk_prediksi') ?? 0
        );

        $averageAbsoluteError = (float) (
            (clone $myPredictionQuery)
                ->whereNotNull('absolute_error')
                ->avg('absolute_error') ?? 0
        );

        $recentPredictions = PrediksiIpk::query()
            ->with([
                'mahasiswa',
                'modelAnn',
            ])
            ->where('predicted_by', $userId)
            ->latest('predicted_at')
            ->latest('id')
            ->limit(5)
            ->get();

        return view(
            'operator.dashboard.index',
            [
                'candidateCount' => $candidateCount,
                'predictedStudentCount' => $predictedStudentCount,
                'myPredictionCount' => $myPredictionCount,
                'pendingCount' => $pendingCount,
                'predictionRate' => $predictionRate,
                'activeModel' => $activeModel,
                'modelReady' => $activeModel !== null,
                'averagePrediction' => $averagePrediction,
                'averageAbsoluteError' => $averageAbsoluteError,
                'recentPredictions' => $recentPredictions,
            ]
        );
    }
}