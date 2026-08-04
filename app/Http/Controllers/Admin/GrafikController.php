<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GrafikController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'model_id' => [
                'nullable',
                'integer',
                'exists:model_anns,id',
            ],
            'angkatan' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
            ],
            'evaluation' => [
                'nullable',
                'in:all,evaluated,not_evaluated',
            ],
            'date_from' => [
                'nullable',
                'date',
            ],
            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
            ],
        ]);

        $query = PrediksiIpk::query()
            ->with([
                'mahasiswa:id,nim,nama,angkatan,program_studi',
                'modelAnn:id,kode_model,nama_model,versi,input_neurons,hidden_layers,output_neurons,is_active,status',
                'predictedBy:id,name,username',
            ]);

     

        $query->when(
            filled($filters['model_id'] ?? null),
            fn (Builder $query): Builder => $query->where(
                'model_ann_id',
                (int) $filters['model_id']
            )
        );

      

        $query->when(
            filled($filters['angkatan'] ?? null),
            function (Builder $query) use ($filters): void {
                $query->whereHas(
                    'mahasiswa',
                    fn (Builder $studentQuery): Builder => $studentQuery->where(
                        'angkatan',
                        (int) $filters['angkatan']
                    )
                );
            }
        );


        $evaluation = $filters['evaluation'] ?? 'all';

        if ($evaluation === 'evaluated') {
            $query->whereNotNull('ipk_aktual');
        }

        if ($evaluation === 'not_evaluated') {
            $query->whereNull('ipk_aktual');
        }

        

        $query->when(
            filled($filters['date_from'] ?? null),
            fn (Builder $query): Builder => $query->whereDate(
                'predicted_at',
                '>=',
                $filters['date_from']
            )
        );

        $query->when(
            filled($filters['date_to'] ?? null),
            fn (Builder $query): Builder => $query->whereDate(
                'predicted_at',
                '<=',
                $filters['date_to']
            )
        );       

        $predictions = $query
            ->orderBy('predicted_at')
            ->orderBy('id')
            ->get();

        $evaluatedPredictions = $predictions
            ->filter(
                fn (PrediksiIpk $prediction): bool =>
                    $prediction->ipk_aktual !== null
                    && $prediction->absolute_error !== null
                    && $prediction->squared_error !== null
            )
            ->values();

        $totalPredictions = $predictions->count();

        $totalStudents = $predictions
            ->unique('mahasiswa_id')
            ->count();

        $evaluatedCount = $evaluatedPredictions->count();

        $averagePrediction = $totalPredictions > 0
            ? round(
                (float) $predictions->avg(
                    fn (PrediksiIpk $prediction): float =>
                        (float) $prediction->ipk_prediksi
                ),
                6
            )
            : null;

        $averageActual = $evaluatedCount > 0
            ? round(
                (float) $evaluatedPredictions->avg(
                    fn (PrediksiIpk $prediction): float =>
                        (float) $prediction->ipk_aktual
                ),
                6
            )
            : null;

        $meanAbsoluteError = $evaluatedCount > 0
            ? round(
                (float) $evaluatedPredictions->avg(
                    fn (PrediksiIpk $prediction): float =>
                        (float) $prediction->absolute_error
                ),
                6
            )
            : null;

        $meanSquaredError = $evaluatedCount > 0
            ? round(
                (float) $evaluatedPredictions->avg(
                    fn (PrediksiIpk $prediction): float =>
                        (float) $prediction->squared_error
                ),
                6
            )
            : null;

        $bestAbsoluteError = $evaluatedCount > 0
            ? round(
                (float) $evaluatedPredictions->min(
                    fn (PrediksiIpk $prediction): float =>
                        (float) $prediction->absolute_error
                ),
                6
            )
            : null;

        $latestPredictionAt = $predictions
            ->sortByDesc(
                fn (PrediksiIpk $prediction): int =>
                    $prediction->predicted_at?->timestamp ?? 0
            )
            ->first()
            ?->predicted_at;


        $comparisonItems = $predictions
            ->sortByDesc(
                fn (PrediksiIpk $prediction): int =>
                    $prediction->predicted_at?->timestamp ?? 0
            )
            ->take(12)
            ->reverse()
            ->values();

        $comparisonLabels = $comparisonItems
            ->map(
                fn (PrediksiIpk $prediction): string =>
                    $prediction->mahasiswa?->nama
                    ?? 'Mahasiswa #' . $prediction->mahasiswa_id
            )
            ->all();

        $comparisonPredicted = $comparisonItems
            ->map(
                fn (PrediksiIpk $prediction): float =>
                    round((float) $prediction->ipk_prediksi, 3)
            )
            ->all();

        $comparisonActual = $comparisonItems
            ->map(
                fn (PrediksiIpk $prediction): ?float =>
                    $prediction->ipk_aktual !== null
                        ? round((float) $prediction->ipk_aktual, 3)
                        : null
            )
            ->all();


        $distribution = [
            '≤ 2.75' => 0,
            '2.76–3.00' => 0,
            '3.01–3.25' => 0,
            '3.26–3.50' => 0,
            '3.51–3.75' => 0,
            '> 3.75' => 0,
        ];

        foreach ($predictions as $prediction) {
            $value = (float) $prediction->ipk_prediksi;

            if ($value <= 2.75) {
                $distribution['≤ 2.75']++;
            } elseif ($value <= 3.00) {
                $distribution['2.76–3.00']++;
            } elseif ($value <= 3.25) {
                $distribution['3.01–3.25']++;
            } elseif ($value <= 3.50) {
                $distribution['3.26–3.50']++;
            } elseif ($value <= 3.75) {
                $distribution['3.51–3.75']++;
            } else {
                $distribution['> 3.75']++;
            }
        }

        $distributionLabels = array_keys($distribution);
        $distributionValues = array_values($distribution);

        $errorDistribution = [
            '≤ 0.05' => 0,
            '0.051–0.10' => 0,
            '0.101–0.20' => 0,
            '> 0.20' => 0,
        ];

        foreach ($evaluatedPredictions as $prediction) {
            $error = (float) $prediction->absolute_error;

            if ($error <= 0.05) {
                $errorDistribution['≤ 0.05']++;
            } elseif ($error <= 0.10) {
                $errorDistribution['0.051–0.10']++;
            } elseif ($error <= 0.20) {
                $errorDistribution['0.101–0.20']++;
            } else {
                $errorDistribution['> 0.20']++;
            }
        }

        $errorLabels = array_keys($errorDistribution);
        $errorValues = array_values($errorDistribution);


        $trendGroups = $predictions
            ->filter(
                fn (PrediksiIpk $prediction): bool =>
                    $prediction->predicted_at !== null
            )
            ->groupBy(
                fn (PrediksiIpk $prediction): string =>
                    $prediction->predicted_at->format('Y-m')
            )
            ->sortKeys();

        $trendLabels = [];
        $trendPredicted = [];
        $trendActual = [];
        $trendCounts = [];

        foreach ($trendGroups as $month => $items) {
            /** @var Collection<int, PrediksiIpk> $items */

            $trendLabels[] = Carbon::createFromFormat(
                'Y-m-d',
                $month . '-01'
            )->translatedFormat('M Y');

            $trendPredicted[] = round(
                (float) $items->avg(
                    fn (PrediksiIpk $prediction): float =>
                        (float) $prediction->ipk_prediksi
                ),
                3
            );

            $actualItems = $items
                ->filter(
                    fn (PrediksiIpk $prediction): bool =>
                        $prediction->ipk_aktual !== null
                )
                ->values();

            $trendActual[] = $actualItems->isNotEmpty()
                ? round(
                    (float) $actualItems->avg(
                        fn (PrediksiIpk $prediction): float =>
                            (float) $prediction->ipk_aktual
                    ),
                    3
                )
                : null;

            $trendCounts[] = $items->count();
        }

        $cohortSummary = $predictions
            ->filter(
                fn (PrediksiIpk $prediction): bool =>
                    $prediction->mahasiswa?->angkatan !== null
            )
            ->groupBy(
                fn (PrediksiIpk $prediction): string =>
                    (string) $prediction->mahasiswa->angkatan
            )
            ->map(
                function (
                    Collection $items,
                    string $cohort
                ): array {
                    $evaluatedItems = $items
                        ->filter(
                            fn (PrediksiIpk $prediction): bool =>
                                $prediction->ipk_aktual !== null
                        )
                        ->values();

                    return [
                        'angkatan' => (int) $cohort,
                        'jumlah' => $items->count(),
                        'rata_prediksi' => round(
                            (float) $items->avg(
                                fn (PrediksiIpk $prediction): float =>
                                    (float) $prediction->ipk_prediksi
                            ),
                            3
                        ),
                        'rata_aktual' => $evaluatedItems->isNotEmpty()
                            ? round(
                                (float) $evaluatedItems->avg(
                                    fn (PrediksiIpk $prediction): float =>
                                        (float) $prediction->ipk_aktual
                                ),
                                3
                            )
                            : null,
                        'mae' => $evaluatedItems->isNotEmpty()
                            ? round(
                                (float) $evaluatedItems->avg(
                                    fn (PrediksiIpk $prediction): float =>
                                        (float) $prediction->absolute_error
                                ),
                                4
                            )
                            : null,
                    ];
                }
            )
            ->sortByDesc('angkatan')
            ->values();

        $recentPredictions = $predictions
            ->sortByDesc(
                fn (PrediksiIpk $prediction): int =>
                    $prediction->predicted_at?->timestamp ?? 0
            )
            ->take(10)
            ->values();

        $models = ModelAnn::query()
            ->latest('id')
            ->get([
                'id',
                'kode_model',
                'nama_model',
                'versi',
                'input_neurons',
                'hidden_layers',
                'output_neurons',
                'is_active',
                'status',
                'training_finished_at',
            ]);

        $cohorts = Mahasiswa::query()
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        $hasActiveFilter = collect([
            $filters['model_id'] ?? null,
            $filters['angkatan'] ?? null,
            ($filters['evaluation'] ?? 'all') !== 'all'
                ? $filters['evaluation']
                : null,
            $filters['date_from'] ?? null,
            $filters['date_to'] ?? null,
        ])->filter(
            fn (mixed $value): bool => filled($value)
        )->isNotEmpty();

        return view('admin.grafik.index', [
            'filters' => $filters,
            'models' => $models,
            'cohorts' => $cohorts,
            'hasActiveFilter' => $hasActiveFilter,

            'totalPredictions' => $totalPredictions,
            'totalStudents' => $totalStudents,
            'evaluatedCount' => $evaluatedCount,
            'averagePrediction' => $averagePrediction,
            'averageActual' => $averageActual,
            'meanAbsoluteError' => $meanAbsoluteError,
            'meanSquaredError' => $meanSquaredError,
            'bestAbsoluteError' => $bestAbsoluteError,
            'latestPredictionAt' => $latestPredictionAt,
            'hasData' => $totalPredictions > 0,

            'comparisonLabels' => $comparisonLabels,
            'comparisonPredicted' => $comparisonPredicted,
            'comparisonActual' => $comparisonActual,

            'distributionLabels' => $distributionLabels,
            'distributionValues' => $distributionValues,

            'errorLabels' => $errorLabels,
            'errorValues' => $errorValues,

            'trendLabels' => $trendLabels,
            'trendPredicted' => $trendPredicted,
            'trendActual' => $trendActual,
            'trendCounts' => $trendCounts,

            'cohortSummary' => $cohortSummary,
            'recentPredictions' => $recentPredictions,
        ]);
    }
}