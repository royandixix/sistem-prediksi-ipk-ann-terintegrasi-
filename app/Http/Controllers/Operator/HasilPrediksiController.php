<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HasilPrediksiController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'angkatan' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
            ],
            'model_id' => [
                'nullable',
                'integer',
                'exists:model_anns,id',
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

        $userId = (int) $request->user()->id;

        $baseQuery = PrediksiIpk::query()
            ->where('predicted_by', $userId);

        $studentIds = (clone $baseQuery)
            ->whereNotNull('mahasiswa_id')
            ->distinct()
            ->pluck('mahasiswa_id');

        $modelIds = (clone $baseQuery)
            ->whereNotNull('model_ann_id')
            ->distinct()
            ->pluck('model_ann_id');

        $query = (clone $baseQuery)
            ->with([
                'mahasiswa',
                'dataIps',
                'modelAnn',
                'predictedBy',
            ]);

        $query->when(
            filled($filters['search'] ?? null),
            function (Builder $query) use ($filters): void {
                $search = trim((string) $filters['search']);

                $query->where(
                    function (Builder $subQuery) use ($search): void {
                        $subQuery
                            ->where(
                                'nomor_prediksi',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'mahasiswa',
                                function (Builder $studentQuery) use ($search): void {
                                    $studentQuery
                                        ->where(
                                            'nim',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'nama',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );
                    }
                );
            }
        );

        $query->when(
            filled($filters['angkatan'] ?? null),
            function (Builder $query) use ($filters): void {
                $query->whereHas(
                    'mahasiswa',
                    function (Builder $studentQuery) use ($filters): void {
                        $studentQuery->where(
                            'angkatan',
                            (int) $filters['angkatan']
                        );
                    }
                );
            }
        );

        $query->when(
            filled($filters['model_id'] ?? null),
            fn (Builder $query): Builder => $query->where(
                'model_ann_id',
                (int) $filters['model_id']
            )
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

        $totalResults = (clone $query)->count();

        $totalStudents = (clone $query)
            ->distinct()
            ->count('mahasiswa_id');

        $evaluatedResults = (clone $query)
            ->whereNotNull('ipk_aktual')
            ->count();

        $averagePrediction = (clone $query)
            ->avg('ipk_prediksi');

        $averageAbsoluteError = (clone $query)
            ->whereNotNull('absolute_error')
            ->avg('absolute_error');

        $averageSquaredError = (clone $query)
            ->whereNotNull('squared_error')
            ->avg('squared_error');

        $results = $query
            ->latest('predicted_at')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $models = ModelAnn::query()
            ->whereIn('id', $modelIds)
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
            ]);

        $cohorts = Mahasiswa::query()
            ->whereIn('id', $studentIds)
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        $hasActiveFilter = collect([
            $filters['search'] ?? null,
            $filters['angkatan'] ?? null,
            $filters['model_id'] ?? null,
            ($filters['evaluation'] ?? 'all') !== 'all'
                ? $filters['evaluation']
                : null,
            $filters['date_from'] ?? null,
            $filters['date_to'] ?? null,
        ])
            ->filter(
                fn ($value): bool => filled($value)
            )
            ->isNotEmpty();

        return view(
            'operator.hasil-prediksi.index',
            [
                'results' => $results,
                'models' => $models,
                'cohorts' => $cohorts,
                'filters' => $filters,
                'hasActiveFilter' => $hasActiveFilter,
                'totalResults' => $totalResults,
                'totalStudents' => $totalStudents,
                'evaluatedResults' => $evaluatedResults,
                'averagePrediction' => $averagePrediction,
                'averageAbsoluteError' => $averageAbsoluteError,
                'averageSquaredError' => $averageSquaredError,
            ]
        );
    }

    public function show(
        Request $request,
        PrediksiIpk $prediksiIpk
    ): View {
        abort_unless(
            (int) $prediksiIpk->predicted_by
                === (int) $request->user()->id,
            404
        );

        $prediksiIpk->loadMissing([
            'mahasiswa',
            'dataIps',
            'modelAnn',
            'predictedBy',
        ]);

        $normalizedInputs = collect(
            $prediksiIpk->input_normalized ?? []
        )
            ->map(
                fn ($value): float => (float) $value
            )
            ->values()
            ->all();

        return view(
            'operator.hasil-prediksi.show',
            [
                'prediction' => $prediksiIpk,
                'normalizedInputs' => $normalizedInputs,
            ]
        );
    }
}