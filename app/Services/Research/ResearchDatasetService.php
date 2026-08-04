<?php

namespace App\Services\Research;

use App\Models\DataIps;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ResearchDatasetService
{
    public const SOURCE_NAME = 'Data Mhs.xlsx';

    public const PREPROCESSING_CODE = 'compatibility_ips_1_5_v1';

    public function processedCsvPath(): string
    {
        return database_path('data/data_mhs_penelitian_2023.csv');
    }

    public function rawCsvPath(): string
    {
        return database_path('data/data_mhs_raw.csv');
    }

    public function excludedCsvPath(): string
    {
        return database_path('data/data_mhs_excluded_2023.csv');
    }

    public function summaryPath(): string
    {
        return database_path('data/dataset_summary.json');
    }

    public function summary(): array
    {
        $path = $this->summaryPath();

        if (! is_file($path)) {
            throw new RuntimeException('Ringkasan dataset penelitian tidak ditemukan.');
        }

        $decoded = json_decode(
            (string) file_get_contents($path),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        return array_merge($decoded, [
            'database_students' => Mahasiswa::query()
                ->where('angkatan', 2023)
                ->count(),
            'database_research_samples' => DataIps::query()
                ->where('data_source', self::SOURCE_NAME)
                ->count(),
            'database_estimated_samples' => DataIps::query()
                ->where('data_source', self::SOURCE_NAME)
                ->where('is_estimated', true)
                ->count(),
            'processed_csv_exists' => is_file($this->processedCsvPath()),
            'raw_csv_exists' => is_file($this->rawCsvPath()),
            'excluded_csv_exists' => is_file($this->excludedCsvPath()),
        ]);
    }

    public function import(?int $createdBy = null): array
    {
        $path = $this->processedCsvPath();

        if (! is_file($path)) {
            throw new RuntimeException(
                'Dataset hasil preprocessing tidak ditemukan di database/data.'
            );
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException('Dataset penelitian tidak dapat dibuka.');
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);
            throw new RuntimeException('Header dataset penelitian tidak valid.');
        }

        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
        $required = [
            'nim',
            'nama',
            'angkatan',
            'program_studi',
            'ips_1',
            'ips_2',
            'ips_3',
            'ips_4',
            'ips_5',
            'ipk_akhir_aktual',
            'is_estimated',
            'data_source',
            'source_terms',
            'preprocessing_method',
            'catatan',
        ];

        $missing = array_values(array_diff($required, $header));

        if ($missing !== []) {
            fclose($handle);
            throw new RuntimeException(
                'Kolom dataset tidak lengkap: '.implode(', ', $missing)
            );
        }

        $createdStudents = 0;
        $updatedStudents = 0;
        $createdIps = 0;
        $updatedIps = 0;
        $processedRows = 0;

        DB::transaction(function () use (
            $handle,
            $header,
            $createdBy,
            &$createdStudents,
            &$updatedStudents,
            &$createdIps,
            &$updatedIps,
            &$processedRows
        ): void {
            while (($values = fgetcsv($handle)) !== false) {
                if ($values === [null] || $values === []) {
                    continue;
                }

                $row = array_combine($header, array_pad($values, count($header), null));

                if (! is_array($row)) {
                    continue;
                }

                $nim = trim((string) ($row['nim'] ?? ''));
                $nama = trim((string) ($row['nama'] ?? ''));

                if ($nim === '' || $nama === '') {
                    continue;
                }

                $student = Mahasiswa::query()->firstOrNew(['nim' => $nim]);
                $studentWasExisting = $student->exists;
                $student->fill([
                    'nama' => $nama,
                    'angkatan' => (int) ($row['angkatan'] ?? 2023),
                    'program_studi' => trim((string) ($row['program_studi'] ?? 'Teknik Informatika')),
                    'status' => 'aktif',
                    'created_by' => $student->created_by ?? $createdBy,
                ]);
                $student->save();

                if ($studentWasExisting) {
                    $updatedStudents++;
                } else {
                    $createdStudents++;
                }

                $dataIps = DataIps::query()->firstOrNew([
                    'mahasiswa_id' => $student->id,
                ]);
                $ipsWasExisting = $dataIps->exists;

                $dataIps->fill([
                    'ips_1' => $this->academicValue($row, 'ips_1'),
                    'ips_2' => $this->academicValue($row, 'ips_2'),
                    'ips_3' => $this->academicValue($row, 'ips_3'),
                    'ips_4' => $this->academicValue($row, 'ips_4'),
                    'ips_5' => $this->academicValue($row, 'ips_5'),
                    'ipk_akhir_aktual' => $this->academicValue($row, 'ipk_akhir_aktual'),
                    'validated_at' => now(),
                    'catatan' => trim((string) ($row['catatan'] ?? '')),
                    'data_source' => self::SOURCE_NAME,
                    'preprocessing_method' => self::PREPROCESSING_CODE,
                    'is_estimated' => filter_var(
                        $row['is_estimated'] ?? true,
                        FILTER_VALIDATE_BOOLEAN
                    ),
                    'source_terms' => array_values(array_filter(
                        explode('|', (string) ($row['source_terms'] ?? ''))
                    )),
                    'created_by' => $dataIps->created_by ?? $createdBy,
                ]);
                $dataIps->save();

                if ($ipsWasExisting) {
                    $updatedIps++;
                } else {
                    $createdIps++;
                }

                $processedRows++;
            }
        }, attempts: 3);

        fclose($handle);

        return [
            'processed_rows' => $processedRows,
            'created_students' => $createdStudents,
            'updated_students' => $updatedStudents,
            'created_data_ips' => $createdIps,
            'updated_data_ips' => $updatedIps,
        ];
    }

    private function academicValue(array $row, string $key): float
    {
        $value = (float) ($row[$key] ?? -1);

        if ($value < 0 || $value > 4) {
            throw new RuntimeException(
                "Nilai {$key} di luar rentang 0.00 sampai 4.00."
            );
        }

        return round($value, 3);
    }
}
