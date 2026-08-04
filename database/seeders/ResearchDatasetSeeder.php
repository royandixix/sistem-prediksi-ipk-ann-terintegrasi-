<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Research\ResearchDatasetService;
use Illuminate\Database\Seeder;

class ResearchDatasetSeeder extends Seeder
{
    public function run(ResearchDatasetService $datasetService): void
    {
        $adminId = User::query()
            ->where('username', 'admin')
            ->value('id');

        $result = $datasetService->import(
            $adminId !== null ? (int) $adminId : null
        );

        $this->command?->info(
            'Dataset penelitian: '
            .$result['processed_rows']
            .' sampel berhasil disinkronkan.'
        );
    }
}
