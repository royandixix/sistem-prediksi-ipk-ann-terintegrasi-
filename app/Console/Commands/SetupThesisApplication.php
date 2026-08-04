<?php

namespace App\Console\Commands;

use App\Models\DataIps;
use App\Models\ModelAnn;
use App\Models\User;
use App\Services\AnnTrainingService;
use Database\Seeders\ResearchDatasetSeeder;
use Illuminate\Console\Command;
use Throwable;

class SetupThesisApplication extends Command
{
    protected $signature = 'thesis:setup
        {--fresh : Hapus database lama lalu migrasi dan seed dari awal}
        {--force : Lewati konfirmasi penghapusan database}
        {--no-train : Lewati training model ANN otomatis}';

    protected $description = 'Siapkan aplikasi, dataset penelitian angkatan 2023, dan model ANN aktif.';

    public function handle(AnnTrainingService $trainingService): int
    {
        $this->components->info('Menyiapkan Sistem Prediksi IPK sesuai skripsi...');

        if ($this->option('fresh')) {
            if (! $this->option('force') && ! $this->confirm(
                'Semua data database lama akan dihapus. Lanjutkan?',
                true
            )) {
                $this->components->warn('Proses dibatalkan.');

                return self::SUCCESS;
            }

            $exitCode = $this->call('migrate:fresh', [
                '--seed' => true,
                '--force' => true,
            ]);
        } else {
            $exitCode = $this->call('migrate', ['--force' => true]);

            if ($exitCode === self::SUCCESS) {
                $exitCode = $this->call('db:seed', [
                    '--class' => ResearchDatasetSeeder::class,
                    '--force' => true,
                ]);
            }
        }

        if ($exitCode !== self::SUCCESS) {
            $this->components->error('Migrasi atau import dataset gagal.');

            return self::FAILURE;
        }

        $datasetCount = DataIps::query()
            ->eligibleForTraining()
            ->count();

        $this->components->info(
            "Dataset training siap: {$datasetCount} sampel."
        );

        if ($this->option('no-train')) {
            $this->components->warn('Training otomatis dilewati.');

            return self::SUCCESS;
        }

        $adminId = User::query()
            ->where('username', 'admin')
            ->value('id');

        try {
            $model = $trainingService->train([
                'nama_model' => 'ANN Skripsi Angkatan 2023',
                'hidden_neurons' => 8,
                'learning_rate' => 0.1,
                'epochs' => 1000,
                'target_error' => 0.001,
                'test_percentage' => 20,
                'random_seed' => 42,
                'catatan' => 'Model awal otomatis dari dataset penelitian Data Mhs.xlsx.',
            ], $adminId !== null ? (int) $adminId : null);

            $this->components->info(
                'Model ANN aktif berhasil dibuat: '
                .$model->nama_model
                .' | MAE: '
                .number_format((float) $model->mae, 6)
                .' | MSE: '
                .number_format((float) $model->mse, 6)
            );
        } catch (Throwable $exception) {
            report($exception);
            $this->components->error(
                'Dataset sudah masuk, tetapi training gagal: '
                .$exception->getMessage()
            );

            return self::FAILURE;
        }

        $activeModel = ModelAnn::query()->active()->trained()->first();

        if (! $activeModel) {
            $this->components->error('Model aktif tidak ditemukan setelah training.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->info('Aplikasi siap digunakan.');
        $this->line('Login admin    : admin / password123');
        $this->line('Login operator : operator / password123');
        $this->line('Jalankan server: php artisan serve');

        return self::SUCCESS;
    }
}
