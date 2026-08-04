<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TrainModelAnnRequest;
use App\Models\DataIps;
use App\Models\ModelAnn;
use App\Services\AnnTrainingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Throwable;

class ModelAnnController extends Controller
{
    public function index(): View
    {
        $totalDataIps = DataIps::query()
            ->count();

        $completeIpsDataset = DataIps::query()
            ->readyForPrediction()
            ->count();

        $eligibleDataset = DataIps::query()
            ->eligibleForTraining()
            ->count();

        $withoutActualIpk = DataIps::query()
            ->readyForPrediction()
            ->whereNull(
                'ipk_akhir_aktual'
            )
            ->count();

        $incompleteIpsDataset = max(
            0,
            $totalDataIps - $completeIpsDataset
        );

        $activeModel = ModelAnn::query()
            ->with('trainedBy')
            ->withCount('datasets')
            ->active()
            ->trained()
            ->latest('id')
            ->get()
            ->first(
                fn (ModelAnn $model): bool =>
                    $this->isAuditableModel($model)
                    && $model->isReadyForPrediction()
            );

        $models = ModelAnn::query()
            ->with('trainedBy')
            ->withCount('datasets')
            ->latest('id')
            ->paginate(10);

        $validModels = ModelAnn::query()
            ->withCount('datasets')
            ->successful()
            ->get()
            ->filter(
                fn (ModelAnn $model): bool =>
                    $this->isAuditableModel($model)
            )
            ->values();

        $bestMae = $validModels->isNotEmpty()
            ? $validModels->min(
                fn (ModelAnn $model): float =>
                    (float) $model->mae
            )
            : null;

        $validModelCount = $validModels->count();

        $minimumDataset =
            AnnTrainingService::MINIMUM_DATASET;

        $trainingReady =
            $eligibleDataset >= $minimumDataset;

        $requiredAdditional = max(
            0,
            $minimumDataset - $eligibleDataset
        );

        $readinessPercentage =
            $minimumDataset > 0
                ? min(
                    100,
                    (int) round(
                        (
                            $eligibleDataset
                            / $minimumDataset
                        ) * 100
                    )
                )
                : 0;

        return view(
            'admin.model-ann.index',
            [
                'totalDataIps' => $totalDataIps,
                'completeIpsDataset' => $completeIpsDataset,
                'eligibleDataset' => $eligibleDataset,
                'withoutActualIpk' => $withoutActualIpk,
                'incompleteIpsDataset' => $incompleteIpsDataset,
                'activeModel' => $activeModel,
                'models' => $models,
                'bestMae' => $bestMae,
                'validModelCount' => $validModelCount,
                'minimumDataset' => $minimumDataset,
                'trainingReady' => $trainingReady,
                'requiredAdditional' => $requiredAdditional,
                'readinessPercentage' => $readinessPercentage,
            ]
        );
    }

    public function train(
        TrainModelAnnRequest $request,
        AnnTrainingService $trainingService
    ): RedirectResponse {
        try {
            $model = $trainingService->train(
                configuration: $request->validated(),
                trainedBy: $request->user()->id
            );

            return redirect()
                ->route(
                    'admin.model-ann.index'
                )
                ->with(
                    'success',
                    'Training ANN berhasil. Model #'
                    . $model->id
                    . ' sekarang menjadi model aktif.'
                );
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route(
                    'admin.model-ann.index'
                )
                ->withInput()
                ->with(
                    'error',
                    'Training ANN gagal: '
                    . $exception->getMessage()
                );
        }
    }

    private function isAuditableModel(
        ModelAnn $model
    ): bool {
        $datasetCount = (int) (
            $model->datasets_count
            ?? $model->datasets()->count()
        );

        return $model->isTrained()
            && is_array($model->weights)
            && $model->weights !== []
            && is_array($model->biases)
            && $model->biases !== []
            && is_array(
                $model->normalization_params
            )
            && $model->normalization_params !== []
            && (int) $model->total_data > 0
            && $datasetCount
                === (int) $model->total_data
            && $model->mae !== null
            && $model->mse !== null;
    }
}