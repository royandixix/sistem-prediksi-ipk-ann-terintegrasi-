<?php

namespace App\Services;

use App\Models\DataIps;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AnnPredictionService
{
    private const INPUT_NAMES = [
        'ips_1',
        'ips_2',
        'ips_3',
        'ips_4',
        'ips_5',
    ];

    public function predict(
        DataIps $dataIps,
        ModelAnn $model,
        int $userId
    ): PrediksiIpk {
        if (! $model->isReadyForPrediction()) {
            throw ValidationException::withMessages([
                'model' => 'Model ANN aktif belum siap digunakan untuk prediksi.',
            ]);
        }

        if (! $dataIps->isCompleteForPrediction()) {
            throw ValidationException::withMessages([
                'mahasiswa_id' => 'Data IPS Semester 1–5 belum lengkap atau tidak valid.',
            ]);
        }

        $rawInputs = $dataIps->rawInputs();
        $normalization = $model->normalization_params ?? [];
        $normalizedInputs = $this->normalizeInputs($rawInputs, $normalization);
        $normalizedOutput = $this->forward(
            $normalizedInputs,
            $model->weights ?? [],
            $model->biases ?? []
        );

        $targetParameters = $normalization['target']
            ?? $normalization['output']
            ?? null;

        if (
            ! is_array($targetParameters)
            || ! array_key_exists('min', $targetParameters)
            || ! array_key_exists('max', $targetParameters)
        ) {
            throw ValidationException::withMessages([
                'model' => 'Parameter normalisasi target model ANN tidak valid.',
            ]);
        }

        $predictedIpk = $this->denormalize(
            $normalizedOutput,
            (float) $targetParameters['min'],
            (float) $targetParameters['max']
        );

        $predictedIpk = round(max(0.0, min(4.0, $predictedIpk)), 3);
        $actualIpk = $dataIps->actualIpkValue();
        $actualIpk = $actualIpk !== null ? round($actualIpk, 3) : null;
        $absoluteError = $actualIpk !== null
            ? round(abs($actualIpk - $predictedIpk), 6)
            : null;
        $squaredError = $actualIpk !== null
            ? round(($actualIpk - $predictedIpk) ** 2, 6)
            : null;

        return DB::transaction(function () use (
            $dataIps,
            $model,
            $userId,
            $rawInputs,
            $normalizedInputs,
            $predictedIpk,
            $actualIpk,
            $absoluteError,
            $squaredError
        ): PrediksiIpk {
            $prediction = PrediksiIpk::query()->firstOrNew([
                'mahasiswa_id' => $dataIps->mahasiswa_id,
                'model_ann_id' => $model->id,
            ]);

            if (! $prediction->exists) {
                $prediction->nomor_prediksi = $this->generatePredictionNumber();
            }

            $prediction->fill([
                'data_ips_id' => $dataIps->id,
                'ips_1' => $rawInputs[0],
                'ips_2' => $rawInputs[1],
                'ips_3' => $rawInputs[2],
                'ips_4' => $rawInputs[3],
                'ips_5' => $rawInputs[4],
                'ipk_prediksi' => $predictedIpk,
                'ipk_aktual' => $actualIpk,
                'absolute_error' => $absoluteError,
                'squared_error' => $squaredError,
                'input_normalized' => $normalizedInputs,
                'keterangan' => $actualIpk !== null
                    ? 'Prediksi dan evaluasi IPK menggunakan model ANN aktif #' . $model->id
                    : 'Prediksi IPK akhir menggunakan model ANN aktif #' . $model->id,
                'predicted_by' => $userId,
                'predicted_at' => now(),
            ]);

            $prediction->save();

            return $prediction->fresh([
                'mahasiswa',
                'dataIps',
                'modelAnn',
                'predictedBy',
            ]);
        }, attempts: 3);
    }

    private function normalizeInputs(
        array $rawInputs,
        array $normalization
    ): array {
        $normalized = [];

        foreach (self::INPUT_NAMES as $index => $inputName) {
            $parameters = $normalization['input'][$inputName]
                ?? $normalization['inputs'][$index]
                ?? null;

            if (
                ! is_array($parameters)
                || ! array_key_exists('min', $parameters)
                || ! array_key_exists('max', $parameters)
            ) {
                throw ValidationException::withMessages([
                    'model' => 'Parameter normalisasi ' . $inputName . ' tidak valid.',
                ]);
            }

            $normalized[] = $this->normalize(
                (float) $rawInputs[$index],
                (float) $parameters['min'],
                (float) $parameters['max']
            );
        }

        return $normalized;
    }

    private function forward(
        array $inputs,
        array $weights,
        array $biases
    ): float {
        $inputHiddenWeights = $weights['input_hidden'] ?? null;
        $hiddenOutputWeights = $weights['hidden_output'] ?? null;
        $hiddenBiases = $biases['hidden'] ?? null;
        $outputBias = $biases['output'] ?? null;

        if (
            ! is_array($inputHiddenWeights)
            || count($inputHiddenWeights) !== count($inputs)
            || ! is_array($hiddenOutputWeights)
            || ! is_array($hiddenBiases)
            || count($hiddenOutputWeights) !== count($hiddenBiases)
            || $outputBias === null
        ) {
            throw ValidationException::withMessages([
                'model' => 'Struktur bobot atau bias model ANN tidak valid.',
            ]);
        }

        $hiddenOutputs = [];
        $hiddenCount = count($hiddenOutputWeights);

        for ($hiddenIndex = 0; $hiddenIndex < $hiddenCount; $hiddenIndex++) {
            $sum = (float) $hiddenBiases[$hiddenIndex];

            foreach ($inputs as $inputIndex => $inputValue) {
                if (! array_key_exists($hiddenIndex, $inputHiddenWeights[$inputIndex])) {
                    throw ValidationException::withMessages([
                        'model' => 'Matriks bobot input-hidden model ANN tidak valid.',
                    ]);
                }

                $sum +=
                    (float) $inputValue
                    * (float) $inputHiddenWeights[$inputIndex][$hiddenIndex];
            }

            $hiddenOutputs[$hiddenIndex] = $this->sigmoid($sum);
        }

        $outputSum = (float) $outputBias;

        foreach ($hiddenOutputs as $hiddenIndex => $hiddenOutput) {
            $outputSum +=
                (float) $hiddenOutput
                * (float) $hiddenOutputWeights[$hiddenIndex];
        }

        return $this->sigmoid($outputSum);
    }

    private function normalize(
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

    private function denormalize(
        float $value,
        float $minimum,
        float $maximum
    ): float {
        $range = $maximum - $minimum;

        if (abs($range) < PHP_FLOAT_EPSILON) {
            return $minimum;
        }

        return $minimum + ($value * $range);
    }

    private function sigmoid(float $value): float
    {
        $value = max(-60.0, min(60.0, $value));

        return 1.0 / (1.0 + exp(-$value));
    }

    private function generatePredictionNumber(): string
    {
        do {
            $number = 'PRD-'
                . now()->format('YmdHis')
                . '-'
                . Str::upper(Str::random(6));
        } while (PrediksiIpk::query()->where('nomor_prediksi', $number)->exists());

        return $number;
    }
}
