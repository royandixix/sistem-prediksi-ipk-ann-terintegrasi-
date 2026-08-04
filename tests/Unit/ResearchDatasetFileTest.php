<?php

test('dataset penelitian angkatan 2023 memiliki 612 sampel valid', function () {
    $path = dirname(__DIR__, 2).'/database/data/data_mhs_penelitian_2023.csv';

    expect(is_file($path))->toBeTrue();

    $handle = fopen($path, 'rb');
    $header = fgetcsv($handle);
    $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);

    $count = 0;

    while (($values = fgetcsv($handle)) !== false) {
        $row = array_combine($header, $values);

        expect($row['angkatan'])->toBe('2023');
        expect((float) $row['ips_1'])->toBeBetween(0, 4);
        expect((float) $row['ips_2'])->toBeBetween(0, 4);
        expect((float) $row['ips_3'])->toBeBetween(0, 4);
        expect((float) $row['ips_4'])->toBeBetween(0, 4);
        expect((float) $row['ips_5'])->toBeBetween(0, 4);
        expect((float) $row['ipk_akhir_aktual'])->toBeBetween(0, 4);

        $count++;
    }

    fclose($handle);

    expect($count)->toBe(612);
});

test('ringkasan dataset sesuai dengan berkas penelitian', function () {
    $path = dirname(__DIR__, 2).'/database/data/dataset_summary.json';
    $summary = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);

    expect($summary['source_rows'])->toBe(9913)
        ->and($summary['cohort_2023_students'])->toBe(682)
        ->and($summary['research_samples_included'])->toBe(612)
        ->and($summary['research_samples_excluded'])->toBe(70);
});
