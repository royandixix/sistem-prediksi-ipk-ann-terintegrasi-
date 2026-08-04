<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\DataIps;
use App\Models\Mahasiswa;
use App\Models\ModelAnn;
use App\Models\PrediksiIpk;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Throwable;

class DashboardController extends Controller
{
    public function index(): View
    {
        $databaseConnected = true;
        $recentUsers = collect();

        $totalMahasiswa = 0;
        $totalDataIps = 0;
        $completeDataIps = 0;
        $totalPrediksi = 0;
        $predictedStudents = 0;
        $totalModel = 0;
        $totalUsers = 0;
        $activeOperators = 0;
        $activeModel = null;

        try {
            DB::connection()->getPdo();

            $totalMahasiswa = Mahasiswa::query()
                ->aktif()
                ->count();

            $totalDataIps = DataIps::query()
                ->count();

            $completeDataIps = DataIps::query()
                ->readyForPrediction()
                ->distinct()
                ->count('mahasiswa_id');

            $totalPrediksi = PrediksiIpk::query()
                ->count();

            $predictedStudents = PrediksiIpk::query()
                ->whereIn(
                    'mahasiswa_id',
                    DataIps::query()
                        ->readyForPrediction()
                        ->select('mahasiswa_id')
                )
                ->distinct()
                ->count('mahasiswa_id');

            $totalModel = ModelAnn::query()
                ->count();

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
                        && (int) $model->datasets_count
                            === $model->totalDataset()
                );

            $totalUsers = User::query()
                ->count();

            $activeOperators = User::query()
                ->where(
                    'role',
                    UserRole::OPERATOR->value
                )
                ->where(
                    'is_active',
                    true
                )
                ->count();

            $recentUsers = User::query()
                ->latest('created_at')
                ->limit(5)
                ->get([
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at',
                ])
                ->map(
                    function (User $user): object {
                        $role = $user->role instanceof \BackedEnum
                            ? $user->role->value
                            : (
                                $user->role instanceof \UnitEnum
                                    ? $user->role->name
                                    : (string) $user->role
                            );

                        return (object) [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $role,
                            'created_at' => $user->created_at,
                        ];
                    }
                );
        } catch (Throwable $exception) {
            report($exception);

            $databaseConnected = false;
        }

        $ipsCompletionRate = $totalMahasiswa > 0
            ? min(
                100,
                (int) round(
                    ($completeDataIps / $totalMahasiswa) * 100
                )
            )
            : 0;

        $predictionRate = $completeDataIps > 0
            ? min(
                100,
                (int) round(
                    ($predictedStudents / $completeDataIps) * 100
                )
            )
            : 0;

        $modelReady = $activeModel !== null;

        $overviewLabels = [
            'Mahasiswa',
            'Data IPS',
            'Hasil Prediksi',
            'Model ANN',
        ];

        $overviewValues = [
            $totalMahasiswa,
            $completeDataIps,
            $predictedStudents,
            $modelReady ? 1 : 0,
        ];

        $targetValues = [
            max($totalMahasiswa, 1),
            max($totalMahasiswa, 1),
            max($completeDataIps, 1),
            1,
        ];

        $readinessLabels = [
            'Database',
            'Data IPS',
            'Prediksi',
            'Model ANN',
        ];

        $readinessValues = [
            $databaseConnected ? 100 : 0,
            $ipsCompletionRate,
            $predictionRate,
            $modelReady ? 100 : 0,
        ];

        return view(
            'admin.dashboard.index',
            [
                'databaseConnected' => $databaseConnected,
                'totalMahasiswa' => $totalMahasiswa,
                'totalDataIps' => $totalDataIps,
                'completeDataIps' => $completeDataIps,
                'totalPrediksi' => $totalPrediksi,
                'predictedStudents' => $predictedStudents,
                'totalModel' => $totalModel,
                'totalUsers' => $totalUsers,
                'activeOperators' => $activeOperators,
                'activeModel' => $activeModel,
                'ipsCompletionRate' => $ipsCompletionRate,
                'predictionRate' => $predictionRate,
                'modelReady' => $modelReady,
                'recentUsers' => $recentUsers,
                'overviewLabels' => $overviewLabels,
                'overviewValues' => $overviewValues,
                'targetValues' => $targetValues,
                'readinessLabels' => $readinessLabels,
                'readinessValues' => $readinessValues,
            ]
        );
    }
}