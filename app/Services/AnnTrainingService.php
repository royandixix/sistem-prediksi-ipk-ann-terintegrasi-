<?php

namespace App\Services;

use App\Models\DataIps;
use App\Models\ModelAnn;
use App\Models\ModelAnnDataset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class AnnTrainingService
{
    public const MINIMUM_DATASET = 5;

    private const INPUT_COUNT = 5;

    public function train(
        array $configuration = [],
        ?int $trainedBy = null
    ): ModelAnn {
        $hiddenNeurons = $this->integerValue($configuration, 'hidden_neurons', 8, 3, 32);
        $learningRate = $this->floatValue($configuration, 'learning_rate', 0.1, 0.001, 1.0);
        $maxEpoch = $this->integerValue($configuration, 'epochs', 1000, 100, 10000);
        $targetError = $this->floatValue($configuration, 'target_error', 0.001, 0.000001, 1.0);
        $testingRatio = $this->integerValue($configuration, 'test_percentage', 20, 10, 40);
        $trainingRatio = 100 - $testingRatio;
        $randomSeed = $this->integerValue($configuration, 'random_seed', 42, 1, 999999);
        $datasets = $this->getEligibleDatasets();
        $totalDataset = count($datasets);

        if ($totalDataset < self::MINIMUM_DATASET) {
            throw new RuntimeException(
                'Training ANN membutuhkan minimal '
                . self::MINIMUM_DATASET
                . ' dataset lengkap. Dataset tersedia: '
                . $totalDataset
                . '.'
            );
        }

        [$trainingRaw, $testingRaw] = $this->splitDatasets(
            $datasets,
            $testingRatio,
            $randomSeed
        );

        $normalizationParams = $this->buildNormalizationParameters($trainingRaw);
        $trainingDatasets = $this->normalizeDatasets($trainingRaw, $normalizationParams);
        $testingDatasets = $this->normalizeDatasets($testingRaw, $normalizationParams);
        $model = $this->createTrainingModel(
            $configuration,
            $hiddenNeurons,
            $learningRate,
            $maxEpoch,
            $targetError,
            $trainingRatio,
            $testingRatio,
            $randomSeed,
            $totalDataset,
            count($trainingDatasets),
            count($testingDatasets),
            $trainedBy
        );

        try {
            $network = $this->initializeNetwork(
                self::INPUT_COUNT,
                $hiddenNeurons,
                $randomSeed
            );

            $trainingResult = $this->runTraining(
                $trainingDatasets,
                $network['weights'],
                $network['biases'],
                $learningRate,
                $maxEpoch,
                $targetError,
                $randomSeed
            );

            $testingEvaluation = $this->evaluateDatasets(
                $testingDatasets,
                $trainingResult['weights'],
                $trainingResult['biases'],
                $normalizationParams
            );

            $trainingEvaluation = $this->evaluateDatasets(
                $trainingDatasets,
                $trainingResult['weights'],
                $trainingResult['biases'],
                $normalizationParams
            );

            DB::transaction(function () use (
                $model,
                $trainingResult,
                $normalizationParams,
                $totalDataset,
                $trainingDatasets,
                $testingDatasets,
                $testingEvaluation,
                $trainingEvaluation,
                $configuration
            ): void {
                ModelAnn::query()
                    ->where('id', '!=', $model->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                $model->update([
                    'weights' => $trainingResult['weights'],
                    'biases' => $trainingResult['biases'],
                    'normalization_params' => $normalizationParams,
                    'total_data' => $totalDataset,
                    'training_data_count' => count($trainingDatasets),
                    'testing_data_count' => count($testingDatasets),
                    'mae' => $testingEvaluation['mae'],
                    'mse' => $testingEvaluation['mse'],
                    'status' => 'trained',
                    'is_active' => true,
                    'training_finished_at' => now(),
                    'catatan' => $this->successNote(
                        $configuration['catatan'] ?? null,
                        $trainingResult['epochs_completed'],
                        $trainingResult['final_error'],
                        $trainingEvaluation['mae'],
                        $trainingEvaluation['mse']
                    ),
                ]);

                $this->storeDatasetResults(
                    $model,
                    'training',
                    $trainingEvaluation['items']
                );

                $this->storeDatasetResults(
                    $model,
                    'testing',
                    $testingEvaluation['items']
                );
            }, attempts: 3);

            return $model->fresh(['trainedBy', 'datasets']);
        } catch (Throwable $exception) {
            $model->update([
                'status' => 'failed',
                'is_active' => false,
                'training_finished_at' => now(),
                'catatan' => 'Training gagal: ' . $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function getEligibleDatasets(): array
    {
        return DataIps::query()
            ->with('mahasiswa:id,nim,nama')
            ->eligibleForTraining()
            ->orderBy('id')
            ->get()
            ->map(static fn (DataIps $dataIps): array => [
                'data_ips_id' => $dataIps->id,
                'mahasiswa_id' => $dataIps->mahasiswa_id,
                'inputs' => $dataIps->rawInputs(),
                'target' => (float) $dataIps->actualIpkValue(),
            ])
            ->all();
    }

    private function splitDatasets(
        array $datasets,
        int $testingRatio,
        int $randomSeed
    ): array {
        $shuffled = array_values($datasets);
        mt_srand($randomSeed);
        shuffle($shuffled);

        $testingCount = (int) round(count($shuffled) * ($testingRatio / 100));
        $testingCount = max(1, min(count($shuffled) - 1, $testingCount));

        $testing = array_slice($shuffled, 0, $testingCount);
        $training = array_slice($shuffled, $testingCount);

        if ($training === [] || $testing === []) {
            throw new RuntimeException('Pembagian dataset training dan testing tidak valid.');
        }

        return [$training, $testing];
    }

    private function buildNormalizationParameters(array $trainingDatasets): array
    {
        $inputNames = ['ips_1', 'ips_2', 'ips_3', 'ips_4', 'ips_5'];
        $inputParameters = [];
        $inputList = [];

        foreach ($inputNames as $inputIndex => $inputName) {
            $values = array_map(
                static fn (array $dataset): float => (float) $dataset['inputs'][$inputIndex],
                $trainingDatasets
            );

            $parameters = [
                'min' => min($values),
                'max' => max($values),
            ];

            $inputParameters[$inputName] = $parameters;
            $inputList[] = [
                'name' => $inputName,
                'min' => $parameters['min'],
                'max' => $parameters['max'],
            ];
        }

        $targetValues = array_map(
            static fn (array $dataset): float => (float) $dataset['target'],
            $trainingDatasets
        );

        $targetParameters = [
            'min' => min($targetValues),
            'max' => max($targetValues),
        ];

        return [
            'method' => 'min_max',
            'range' => ['min' => 0, 'max' => 1],
            'input' => $inputParameters,
            'inputs' => $inputList,
            'target' => $targetParameters,
            'output' => $targetParameters,
        ];
    }

    private function normalizeDatasets(
        array $datasets,
        array $normalizationParams
    ): array {
        $inputNames = ['ips_1', 'ips_2', 'ips_3', 'ips_4', 'ips_5'];
        $normalized = [];

        foreach ($datasets as $dataset) {
            $normalizedInputs = [];

            foreach ($inputNames as $inputIndex => $inputName) {
                $parameters = $normalizationParams['input'][$inputName];
                $normalizedInputs[] = $this->normalizeValue(
                    (float) $dataset['inputs'][$inputIndex],
                    (float) $parameters['min'],
                    (float) $parameters['max']
                );
            }

            $normalized[] = [
                'data_ips_id' => $dataset['data_ips_id'],
                'mahasiswa_id' => $dataset['mahasiswa_id'],
                'raw_inputs' => array_map('floatval', $dataset['inputs']),
                'normalized_inputs' => $normalizedInputs,
                'target' => (float) $dataset['target'],
                'normalized_target' => $this->normalizeValue(
                    (float) $dataset['target'],
                    (float) $normalizationParams['target']['min'],
                    (float) $normalizationParams['target']['max']
                ),
            ];
        }

        return $normalized;
    }

    private function createTrainingModel(
        array $configuration,
        int $hiddenNeurons,
        float $learningRate,
        int $maxEpoch,
        float $targetError,
        int $trainingRatio,
        int $testingRatio,
        int $randomSeed,
        int $totalDataset,
        int $trainingCount,
        int $testingCount,
        ?int $trainedBy
    ): ModelAnn {
        $version = ((int) ModelAnn::query()->max('versi')) + 1;

        return ModelAnn::query()->create([
            'kode_model' => $this->generateModelCode(),
            'nama_model' => trim((string) ($configuration['nama_model'] ?? 'ANN Prediksi IPK')),
            'versi' => max(1, $version),
            'input_neurons' => self::INPUT_COUNT,
            'hidden_layers' => [$hiddenNeurons],
            'output_neurons' => 1,
            'hidden_activation' => 'sigmoid',
            'output_activation' => 'sigmoid',
            'learning_rate' => $learningRate,
            'max_epoch' => $maxEpoch,
            'target_error' => $targetError,
            'training_ratio' => $trainingRatio,
            'testing_ratio' => $testingRatio,
            'random_seed' => $randomSeed,
            'total_data' => $totalDataset,
            'training_data_count' => $trainingCount,
            'testing_data_count' => $testingCount,
            'status' => 'training',
            'is_active' => false,
            'training_started_at' => now(),
            'trained_by' => $trainedBy,
            'catatan' => $configuration['catatan'] ?? null,
        ]);
    }

    private function initializeNetwork(
        int $inputNeurons,
        int $hiddenNeurons,
        int $randomSeed
    ): array {
        mt_srand($randomSeed);
        $inputHiddenWeights = [];

        for ($input = 0; $input < $inputNeurons; $input++) {
            $inputHiddenWeights[$input] = [];

            for ($hidden = 0; $hidden < $hiddenNeurons; $hidden++) {
                $inputHiddenWeights[$input][$hidden] = $this->randomWeight();
            }
        }

        $hiddenOutputWeights = [];
        $hiddenBiases = [];

        for ($hidden = 0; $hidden < $hiddenNeurons; $hidden++) {
            $hiddenOutputWeights[$hidden] = $this->randomWeight();
            $hiddenBiases[$hidden] = $this->randomWeight();
        }

        return [
            'weights' => [
                'input_hidden' => $inputHiddenWeights,
                'hidden_output' => $hiddenOutputWeights,
            ],
            'biases' => [
                'hidden' => $hiddenBiases,
                'output' => $this->randomWeight(),
            ],
        ];
    }

    private function runTraining(
        array $trainingDatasets,
        array $weights,
        array $biases,
        float $learningRate,
        int $maxEpoch,
        float $targetError,
        int $randomSeed
    ): array {
        $epochsCompleted = 0;
        $finalError = INF;

        for ($epoch = 1; $epoch <= $maxEpoch; $epoch++) {
            $order = array_keys($trainingDatasets);
            mt_srand($randomSeed + $epoch);
            shuffle($order);
            $sumSquaredError = 0.0;

            foreach ($order as $datasetIndex) {
                $dataset = $trainingDatasets[$datasetIndex];
                $inputs = $dataset['normalized_inputs'];
                $target = (float) $dataset['normalized_target'];
                $forward = $this->forwardPropagation($inputs, $weights, $biases);
                $output = $forward['output'];
                $hiddenOutputs = $forward['hidden_outputs'];
                $error = $target - $output;
                $sumSquaredError += $error ** 2;
                $outputDelta = $error * $this->sigmoidDerivativeFromOutput($output);
                $hiddenDeltas = [];

                foreach ($hiddenOutputs as $hiddenIndex => $hiddenOutput) {
                    $hiddenDeltas[$hiddenIndex] =
                        $this->sigmoidDerivativeFromOutput($hiddenOutput)
                        * (float) $weights['hidden_output'][$hiddenIndex]
                        * $outputDelta;
                }

                foreach ($weights['hidden_output'] as $hiddenIndex => $weight) {
                    $weights['hidden_output'][$hiddenIndex] =
                        (float) $weight
                        + ($learningRate * $outputDelta * $hiddenOutputs[$hiddenIndex]);
                }

                $biases['output'] = (float) $biases['output'] + ($learningRate * $outputDelta);

                foreach ($inputs as $inputIndex => $inputValue) {
                    foreach ($hiddenDeltas as $hiddenIndex => $hiddenDelta) {
                        $weights['input_hidden'][$inputIndex][$hiddenIndex] =
                            (float) $weights['input_hidden'][$inputIndex][$hiddenIndex]
                            + ($learningRate * $hiddenDelta * $inputValue);
                    }
                }

                foreach ($hiddenDeltas as $hiddenIndex => $hiddenDelta) {
                    $biases['hidden'][$hiddenIndex] =
                        (float) $biases['hidden'][$hiddenIndex]
                        + ($learningRate * $hiddenDelta);
                }
            }

            $finalError = $sumSquaredError / count($trainingDatasets);
            $epochsCompleted = $epoch;

            if ($finalError <= $targetError) {
                break;
            }
        }

        return [
            'weights' => $weights,
            'biases' => $biases,
            'epochs_completed' => $epochsCompleted,
            'final_error' => round($finalError, 10),
        ];
    }

    private function evaluateDatasets(
        array $datasets,
        array $weights,
        array $biases,
        array $normalizationParams
    ): array {
        $items = [];
        $absoluteErrors = [];
        $squaredErrors = [];
        $targetMin = (float) $normalizationParams['target']['min'];
        $targetMax = (float) $normalizationParams['target']['max'];

        foreach ($datasets as $dataset) {
            $forward = $this->forwardPropagation(
                $dataset['normalized_inputs'],
                $weights,
                $biases
            );

            $predicted = $this->denormalizeValue(
                $forward['output'],
                $targetMin,
                $targetMax
            );

            $predicted = max(0.0, min(4.0, $predicted));
            $actual = (float) $dataset['target'];
            $absoluteError = abs($actual - $predicted);
            $squaredError = ($actual - $predicted) ** 2;
            $absoluteErrors[] = $absoluteError;
            $squaredErrors[] = $squaredError;

            $items[] = [
                'data_ips_id' => $dataset['data_ips_id'],
                'input_raw' => $dataset['raw_inputs'],
                'input_normalized' => $dataset['normalized_inputs'],
                'target_actual' => round($actual, 3),
                'output_predicted' => round($predicted, 3),
                'absolute_error' => round($absoluteError, 12),
                'squared_error' => round($squaredError, 12),
            ];
        }

        return [
            'mae' => round(array_sum($absoluteErrors) / count($absoluteErrors), 6),
            'mse' => round(array_sum($squaredErrors) / count($squaredErrors), 6),
            'items' => $items,
        ];
    }

    private function storeDatasetResults(
        ModelAnn $model,
        string $subset,
        array $items
    ): void {
        foreach ($items as $item) {
            ModelAnnDataset::query()->updateOrCreate(
                [
                    'model_ann_id' => $model->id,
                    'data_ips_id' => $item['data_ips_id'],
                ],
                [
                    'subset' => $subset,
                    'input_raw' => $item['input_raw'],
                    'input_normalized' => $item['input_normalized'],
                    'target_actual' => $item['target_actual'],
                    'output_predicted' => $item['output_predicted'],
                    'absolute_error' => $item['absolute_error'],
                    'squared_error' => $item['squared_error'],
                    'processed_at' => now(),
                ]
            );
        }
    }

    private function forwardPropagation(
        array $inputs,
        array $weights,
        array $biases
    ): array {
        $inputHidden = $weights['input_hidden'] ?? null;
        $hiddenOutput = $weights['hidden_output'] ?? null;
        $hiddenBiases = $biases['hidden'] ?? null;
        $outputBias = $biases['output'] ?? null;

        if (
            ! is_array($inputHidden)
            || count($inputHidden) !== count($inputs)
            || ! is_array($hiddenOutput)
            || ! is_array($hiddenBiases)
            || count($hiddenOutput) !== count($hiddenBiases)
            || $outputBias === null
        ) {
            throw new RuntimeException('Struktur bobot atau bias ANN tidak valid.');
        }

        $hiddenOutputs = [];
        $hiddenCount = count($hiddenOutput);

        for ($hidden = 0; $hidden < $hiddenCount; $hidden++) {
            $sum = (float) $hiddenBiases[$hidden];

            foreach ($inputs as $inputIndex => $inputValue) {
                if (! array_key_exists($hidden, $inputHidden[$inputIndex])) {
                    throw new RuntimeException('Matriks bobot input-hidden ANN tidak valid.');
                }

                $sum += (float) $inputValue * (float) $inputHidden[$inputIndex][$hidden];
            }

            $hiddenOutputs[$hidden] = $this->sigmoid($sum);
        }

        $outputSum = (float) $outputBias;

        foreach ($hiddenOutputs as $hiddenIndex => $hiddenOutputValue) {
            $outputSum += $hiddenOutputValue * (float) $hiddenOutput[$hiddenIndex];
        }

        return [
            'hidden_outputs' => $hiddenOutputs,
            'output' => $this->sigmoid($outputSum),
        ];
    }

    private function normalizeValue(
        float $value,
        float $minimum,
        float $maximum
    ): float {
        $range = $maximum - $minimum;

        if (abs($range) < PHP_FLOAT_EPSILON) {
            return 0.0;
        }

        return ($value - $minimum) / $range;
    }

    private function denormalizeValue(
        float $normalizedValue,
        float $minimum,
        float $maximum
    ): float {
        $range = $maximum - $minimum;

        if (abs($range) < PHP_FLOAT_EPSILON) {
            return $minimum;
        }

        return $minimum + ($normalizedValue * $range);
    }

    private function sigmoid(float $value): float
    {
        $value = max(-60.0, min(60.0, $value));

        return 1.0 / (1.0 + exp(-$value));
    }

    private function sigmoidDerivativeFromOutput(float $output): float
    {
        return $output * (1.0 - $output);
    }

    private function randomWeight(): float
    {
        return ((mt_rand() / mt_getrandmax()) * 1.0) - 0.5;
    }

    private function integerValue(
        array $configuration,
        string $key,
        int $default,
        int $minimum,
        int $maximum
    ): int {
        $value = (int) ($configuration[$key] ?? $default);

        return max($minimum, min($maximum, $value));
    }

    private function floatValue(
        array $configuration,
        string $key,
        float $default,
        float $minimum,
        float $maximum
    ): float {
        $value = (float) ($configuration[$key] ?? $default);

        return max($minimum, min($maximum, $value));
    }

    private function generateModelCode(): string
    {
        do {
            $code = 'ANN-'
                . now()->format('YmdHis')
                . '-'
                . Str::upper(Str::random(4));
        } while (ModelAnn::query()->where('kode_model', $code)->exists());

        return $code;
    }

    private function successNote(
        ?string $originalNote,
        int $epochsCompleted,
        float $finalError,
        float $trainingMae,
        float $trainingMse
    ): string {
        $parts = array_filter([
            filled($originalNote) ? trim((string) $originalNote) : null,
            'Epoch selesai: ' . $epochsCompleted,
            'Error training akhir: ' . number_format($finalError, 10, '.', ''),
            'MAE training: ' . number_format($trainingMae, 6, '.', ''),
            'MSE training: ' . number_format($trainingMse, 6, '.', ''),
        ]);

        return implode(' | ', $parts);
    }
}
