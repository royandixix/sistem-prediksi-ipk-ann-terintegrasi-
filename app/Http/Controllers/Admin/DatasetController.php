<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use App\Services\Research\ResearchDatasetService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class DatasetController extends Controller
{
    public function index(ResearchDatasetService $datasetService): View
    {
        return view('admin.dataset.index', [
            'summary' => $datasetService->summary(),
            'hasModel' => ModelAnn::query()->exists(),
            'hasPrediction' => PrediksiIpk::query()->exists(),
        ]);
    }

    public function sync(
        Request $request,
        ResearchDatasetService $datasetService
    ): RedirectResponse {
        if (ModelAnn::query()->exists() || PrediksiIpk::query()->exists()) {
            return back()->with(
                'error',
                'Dataset tidak dapat disinkronkan setelah model atau hasil prediksi dibuat. Jalankan php artisan thesis:setup --fresh untuk membangun ulang secara konsisten.'
            );
        }

        try {
            $result = $datasetService->import(
                $request->user()?->id
            );

            return back()->with(
                'success',
                'Sinkronisasi selesai: '
                .$result['processed_rows']
                .' sampel penelitian berhasil diproses.'
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Sinkronisasi dataset gagal: '.$exception->getMessage()
            );
        }
    }

    public function download(
        string $type,
        ResearchDatasetService $datasetService
    ): BinaryFileResponse {
        $files = [
            'raw' => [
                $datasetService->rawCsvPath(),
                'data_mhs_raw.csv',
            ],
            'processed' => [
                $datasetService->processedCsvPath(),
                'data_mhs_penelitian_2023.csv',
            ],
            'excluded' => [
                $datasetService->excludedCsvPath(),
                'data_mhs_excluded_2023.csv',
            ],
            'summary' => [
                $datasetService->summaryPath(),
                'dataset_summary.json',
            ],
        ];

        abort_unless(array_key_exists($type, $files), 404);

        [$path, $name] = $files[$type];

        abort_unless(is_file($path), 404);

        return response()->download($path, $name);
    }
}
