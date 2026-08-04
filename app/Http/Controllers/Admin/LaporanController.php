<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validateFilters($request);

        $query = $this->filteredQuery($filters);

        

        $totalPredictions = (clone $query)->count();

        $totalStudents = (clone $query)
            ->distinct()
            ->count('mahasiswa_id');

        $evaluatedCount = (clone $query)
            ->whereNotNull('ipk_aktual')
            ->count();

        $averagePrediction = (clone $query)
            ->avg('ipk_prediksi');

        $averageActual = (clone $query)
            ->whereNotNull('ipk_aktual')
            ->avg('ipk_aktual');

        $meanAbsoluteError = (clone $query)
            ->whereNotNull('absolute_error')
            ->avg('absolute_error');

        $meanSquaredError = (clone $query)
            ->whereNotNull('squared_error')
            ->avg('squared_error');

        $bestAbsoluteError = (clone $query)
            ->whereNotNull('absolute_error')
            ->min('absolute_error');

        $evaluationRate = $totalPredictions > 0
            ? round(
                ($evaluatedCount / $totalPredictions) * 100,
                1
            )
            : 0;

 

        $cohortSummaries = (clone $query)
            ->join(
                'mahasiswas',
                'prediksi_ipks.mahasiswa_id',
                '=',
                'mahasiswas.id'
            )
            ->selectRaw('
                mahasiswas.angkatan AS angkatan,
                COUNT(prediksi_ipks.id) AS total_prediksi,
                COUNT(DISTINCT prediksi_ipks.mahasiswa_id) AS total_mahasiswa,
                AVG(prediksi_ipks.ipk_prediksi) AS rata_prediksi,
                AVG(prediksi_ipks.ipk_aktual) AS rata_aktual,
                AVG(prediksi_ipks.absolute_error) AS mae,
                AVG(prediksi_ipks.squared_error) AS mse
            ')
            ->groupBy('mahasiswas.angkatan')
            ->orderByDesc('mahasiswas.angkatan')
            ->get();

       

        $results = (clone $query)
            ->with([
                'mahasiswa',
                'dataIps',
                'modelAnn',
                'predictedBy',
            ])
            ->latest('predicted_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Data filter
        |--------------------------------------------------------------------------
        */

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
                'status',
                'is_active',
                'mae',
                'mse',
                'training_finished_at',
            ]);

        $activeModel = ModelAnn::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        $cohorts = Mahasiswa::query()
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
        ])->filter(
            fn (mixed $value): bool => filled($value)
        )->isNotEmpty();

        return view('admin.laporan.index', [
            'filters' => $filters,
            'models' => $models,
            'activeModel' => $activeModel,
            'cohorts' => $cohorts,
            'hasActiveFilter' => $hasActiveFilter,

            'results' => $results,
            'cohortSummaries' => $cohortSummaries,

            'totalPredictions' => $totalPredictions,
            'totalStudents' => $totalStudents,
            'evaluatedCount' => $evaluatedCount,
            'evaluationRate' => $evaluationRate,

            'averagePrediction' => $averagePrediction,
            'averageActual' => $averageActual,
            'meanAbsoluteError' => $meanAbsoluteError,
            'meanSquaredError' => $meanSquaredError,
            'bestAbsoluteError' => $bestAbsoluteError,
        ]);
    }

    public function exportCsv(
        Request $request
    ): StreamedResponse {
        $filters = $this->validateFilters($request);

        $results = $this->filteredQuery($filters)
            ->with([
                'mahasiswa',
                'dataIps',
                'modelAnn',
                'predictedBy',
            ])
            ->latest('predicted_at')
            ->latest('id')
            ->get();

        $filename = sprintf(
            'laporan-prediksi-ipk-%s.csv',
            now()->format('Y-m-d-His')
        );

        return response()->streamDownload(
            function () use ($results): void {
                $handle = fopen(
                    'php://output',
                    'wb'
                );

                if ($handle === false) {
                    return;
                }

                /*
                 * BOM UTF-8 agar nama mahasiswa terbaca dengan benar
                 * ketika file dibuka melalui Microsoft Excel.
                 */
                fwrite(
                    $handle,
                    "\xEF\xBB\xBF"
                );

                fputcsv(
                    $handle,
                    [
                        'No',
                        'Nomor Prediksi',
                        'NIM',
                        'Nama Mahasiswa',
                        'Angkatan',
                        'Program Studi',
                        'IPS 1',
                        'IPS 2',
                        'IPS 3',
                        'IPS 4',
                        'IPS 5',
                        'Rata-rata IPS',
                        'IPK Prediksi',
                        'IPK Aktual',
                        'Absolute Error',
                        'Squared Error',
                        'Kode Model',
                        'Nama Model',
                        'Diproses Oleh',
                        'Tanggal Prediksi',
                        'Keterangan',
                    ],
                    ';'
                );

                foreach ($results as $index => $prediction) {
                    fputcsv(
                        $handle,
                        [
                            $index + 1,
                            $prediction->nomor_prediksi,
                            $prediction->mahasiswa?->nim,
                            $prediction->mahasiswa?->nama,
                            $prediction->mahasiswa?->angkatan,
                            $prediction->mahasiswa?->program_studi,

                            number_format(
                                (float) $prediction->ips_1,
                                2,
                                '.',
                                ''
                            ),

                            number_format(
                                (float) $prediction->ips_2,
                                2,
                                '.',
                                ''
                            ),

                            number_format(
                                (float) $prediction->ips_3,
                                2,
                                '.',
                                ''
                            ),

                            number_format(
                                (float) $prediction->ips_4,
                                2,
                                '.',
                                ''
                            ),

                            number_format(
                                (float) $prediction->ips_5,
                                2,
                                '.',
                                ''
                            ),

                            number_format(
                                $prediction->averageIps(),
                                3,
                                '.',
                                ''
                            ),

                            number_format(
                                (float) $prediction->ipk_prediksi,
                                3,
                                '.',
                                ''
                            ),

                            $prediction->ipk_aktual !== null
                                ? number_format(
                                    (float) $prediction->ipk_aktual,
                                    3,
                                    '.',
                                    ''
                                )
                                : '',

                            $prediction->absolute_error !== null
                                ? number_format(
                                    (float) $prediction->absolute_error,
                                    6,
                                    '.',
                                    ''
                                )
                                : '',

                            $prediction->squared_error !== null
                                ? number_format(
                                    (float) $prediction->squared_error,
                                    6,
                                    '.',
                                    ''
                                )
                                : '',

                            $prediction->modelAnn?->kode_model,
                            $prediction->modelAnn?->nama_model,

                            $prediction->predictedBy?->name
                                ?? $prediction->predictedBy?->username
                                ?? '-',

                            $prediction->predicted_at?->format(
                                'Y-m-d H:i:s'
                            ),

                            $prediction->keterangan,
                        ],
                        ';'
                    );
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    private function validateFilters(
        Request $request
    ): array {
        return $request->validate([
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
    }

    private function filteredQuery(
        array $filters
    ): Builder {
        $query = PrediksiIpk::query();

        $query->when(
            filled($filters['search'] ?? null),
            function (
                Builder $query
            ) use ($filters): void {
                $search = trim(
                    (string) $filters['search']
                );

                $query->where(
                    function (
                        Builder $searchQuery
                    ) use ($search): void {
                        $searchQuery
                            ->where(
                                'nomor_prediksi',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'mahasiswa',
                                function (
                                    Builder $studentQuery
                                ) use ($search): void {
                                    $studentQuery->where(
                                        function (
                                            Builder $studentSearch
                                        ) use ($search): void {
                                            $studentSearch
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
            }
        );

        $query->when(
            filled($filters['angkatan'] ?? null),
            function (
                Builder $query
            ) use ($filters): void {
                $query->whereHas(
                    'mahasiswa',
                    fn (
                        Builder $studentQuery
                    ): Builder => $studentQuery->where(
                        'angkatan',
                        (int) $filters['angkatan']
                    )
                );
            }
        );

        $query->when(
            filled($filters['model_id'] ?? null),
            fn (
                Builder $query
            ): Builder => $query->where(
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
            fn (
                Builder $query
            ): Builder => $query->whereDate(
                'predicted_at',
                '>=',
                $filters['date_from']
            )
        );

        $query->when(
            filled($filters['date_to'] ?? null),
            fn (
                Builder $query
            ): Builder => $query->whereDate(
                'predicted_at',
                '<=',
                $filters['date_to']
            )
        );

        return $query;
    }
}