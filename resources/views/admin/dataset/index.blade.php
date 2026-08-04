@extends('layouts.admin')

@section('title', 'Dataset Penelitian')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Dataset Penelitian
            </h1>
            <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500">
                Keterhubungan data akademik <strong>Data Mhs.xlsx</strong> dengan variabel penelitian
                IPS Semester 1-5 dan target IPK aktual mahasiswa Teknik Informatika angkatan 2023.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.dataset.sync') }}">
            @csrf
            <button
                type="submit"
                @disabled($hasModel || $hasPrediction)
                class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300"
            >
                Sinkronkan Dataset
            </button>
        </form>
    </div>

    <section class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-900">
        <p class="font-semibold">Catatan metodologis penting</p>
        <p class="mt-1">
            File sumber hanya memiliki empat periode akademik. Agar kompatibel dengan lima input ANN pada skripsi,
            IPS Semester 1 dan Semester 2 diestimasi dengan nilai rata-rata yang sama berdasarkan IPK kumulatif
            pada periode GANJIL2425. Gunakan data IPS Semester 1 dan 2 asli bila tersedia sebelum hasil akhir skripsi dipublikasikan.
        </p>
        <p class="mt-2 rounded-lg bg-white/70 px-3 py-2 font-mono text-xs">
            Estimasi IPS awal = ((3 x IPK GANJIL2425) - IPS GANJIL2425) / 2
        </p>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Baris Data Mentah', 'value' => $summary['source_rows'], 'desc' => 'Seluruh rekaman pada file sumber'],
                ['label' => 'Mahasiswa Unik', 'value' => $summary['unique_students'], 'desc' => 'Seluruh angkatan pada file sumber'],
                ['label' => 'Mahasiswa Angkatan 2023', 'value' => $summary['cohort_2023_students'], 'desc' => 'Populasi penelitian'],
                ['label' => 'Sampel Siap ANN', 'value' => $summary['research_samples_included'], 'desc' => 'Data lengkap setelah preprocessing'],
            ];
        @endphp

        @foreach($cards as $card)
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">{{ $card['label'] }}</p>
                <p class="mt-3 text-3xl font-bold text-slate-800">{{ number_format($card['value']) }}</p>
                <p class="mt-2 text-xs text-slate-400">{{ $card['desc'] }}</p>
            </article>
        @endforeach
    </section>

    <div class="grid gap-5 xl:grid-cols-2">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-800">Pemetaan Variabel Skripsi</h2>
            <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Variabel Sistem</th>
                            <th class="px-4 py-3">Sumber Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($summary['mapping'] as $variable => $source)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-700">{{ strtoupper($variable) }}</td>
                                <td class="px-4 py-3 leading-6 text-slate-600">{{ $source }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-800">Kualitas dan Status Import</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                    <dt class="text-slate-500">Sampel masuk database</dt>
                    <dd class="font-bold text-slate-800">{{ number_format($summary['database_research_samples']) }}</dd>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                    <dt class="text-slate-500">Sampel memakai estimasi</dt>
                    <dd class="font-bold text-amber-600">{{ number_format($summary['database_estimated_samples']) }}</dd>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                    <dt class="text-slate-500">Sampel dikeluarkan</dt>
                    <dd class="font-bold text-rose-600">{{ number_format($summary['research_samples_excluded']) }}</dd>
                </div>
                <div class="rounded-lg border border-slate-200 px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Alasan eksklusi</dt>
                    <dd class="mt-2 text-slate-600">
                        @foreach($summary['exclusion_reasons'] as $reason => $count)
                            <span class="mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs">
                                {{ str_replace('_', ' ', $reason) }}: {{ $count }}
                            </span>
                        @endforeach
                    </dd>
                </div>
            </dl>
        </section>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Berkas Audit Dataset</h2>
                <p class="mt-1 text-sm text-slate-400">Unduh data mentah, hasil preprocessing, data yang dikeluarkan, dan ringkasan proses.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach([
                    'raw' => 'Data Mentah',
                    'processed' => 'Data Penelitian',
                    'excluded' => 'Data Dikeluarkan',
                    'summary' => 'Ringkasan JSON',
                ] as $type => $label)
                    <a href="{{ route('admin.dataset.download', $type) }}"
                        class="inline-flex h-10 items-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
