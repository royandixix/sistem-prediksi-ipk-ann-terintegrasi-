@extends('layouts.admin')

@section('title', 'Laporan Prediksi IPK')

@section('content')
    <style>
        .print-only {
            display: none;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm;
            }

            body * {
                visibility: hidden !important;
            }

            #report-area,
            #report-area * {
                visibility: visible !important;
            }

            #report-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .report-card {
                box-shadow: none !important;
                break-inside: avoid;
            }

            table {
                font-size: 10px !important;
            }
        }
    </style>

    <div
        id="report-area"
        class="space-y-5"
    >
        {{-- Header cetak --}}
        <div class="print-only border-b border-slate-300 pb-4 text-center">
            <h1 class="text-xl font-bold text-slate-900">
                LAPORAN HASIL PREDIKSI IPK MAHASISWA
            </h1>

            <p class="mt-1 text-sm text-slate-600">
                Sistem Prediksi IPK Akhir Menggunakan Artificial Neural Network
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Dicetak pada {{ now()->format('d-m-Y H:i') }}
            </p>
        </div>

        {{-- Header halaman --}}
        <div class="no-print flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <div class="flex items-center gap-2 text-[10px] text-slate-400">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="transition hover:text-blue-600"
                    >
                        Dashboard
                    </a>

                    <span>/</span>
                    <span>Laporan</span>
                </div>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
                    Laporan Prediksi IPK
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Rekapitulasi hasil prediksi, evaluasi model, dan data
                    akademik mahasiswa.
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row">
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M6 9V3h12v6"></path>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="7"></rect>
                    </svg>

                    Cetak / Simpan PDF
                </button>

                <a
                    href="{{ route(
                        'admin.laporan.export-csv',
                        request()->query()
                    ) }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M12 3v12"></path>
                        <path d="m7 10 5 5 5-5"></path>
                        <path d="M5 21h14"></path>
                    </svg>

                    Export CSV
                </a>
            </div>
        </div>

        {{-- Validasi --}}
        @if ($errors->any())
            <div class="no-print rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-sm font-semibold text-red-700">
                    Filter laporan tidak dapat diproses.
                </p>

                <ul class="mt-2 space-y-1 text-xs text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Statistik --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="report-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Total Prediksi
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-800">
                    {{ number_format($totalPredictions) }}
                </p>

                <p class="mt-2 text-[11px] text-slate-400">
                    Hasil sesuai filter
                </p>
            </article>

            <article class="report-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Mahasiswa
                </p>

                <p class="mt-3 text-3xl font-bold text-blue-600">
                    {{ number_format($totalStudents) }}
                </p>

                <p class="mt-2 text-[11px] text-slate-400">
                    Mahasiswa unik diprediksi
                </p>
            </article>

            <article class="report-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Rata-rata Prediksi
                </p>

                <p class="mt-3 text-3xl font-bold text-violet-600">
                    {{ $averagePrediction !== null
                        ? number_format((float) $averagePrediction, 3)
                        : '-' }}
                </p>

                <p class="mt-2 text-[11px] text-slate-400">
                    Rata-rata IPK hasil ANN
                </p>
            </article>

            <article class="report-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    MAE
                </p>

                <p class="mt-3 text-3xl font-bold text-amber-600">
                    {{ $meanAbsoluteError !== null
                        ? number_format(
                            (float) $meanAbsoluteError,
                            6
                        )
                        : '-' }}
                </p>

                <p class="mt-2 text-[11px] text-slate-400">
                    {{ number_format($evaluatedCount) }} data dievaluasi
                </p>
            </article>
        </section>

        {{-- Filter --}}
        <section class="no-print overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-base font-semibold text-slate-800">
                    Filter Laporan
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Pilih mahasiswa, angkatan, model, status evaluasi, atau
                    rentang tanggal.
                </p>
            </div>

            <form
                method="GET"
                action="{{ route('admin.laporan.index') }}"
                class="p-6"
            >
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">
                            Pencarian
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Nama, NIM, atau nomor prediksi"
                            class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">
                            Angkatan
                        </label>

                        <select
                            name="angkatan"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                            <option value="">
                                Semua angkatan
                            </option>

                            @foreach ($cohorts as $cohort)
                                <option
                                    value="{{ $cohort }}"
                                    @selected(
                                        (string) ($filters['angkatan'] ?? '')
                                        === (string) $cohort
                                    )
                                >
                                    Angkatan {{ $cohort }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">
                            Model ANN
                        </label>

                        <select
                            name="model_id"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                            <option value="">
                                Semua model
                            </option>

                            @foreach ($models as $model)
                                <option
                                    value="{{ $model->id }}"
                                    @selected(
                                        (string) ($filters['model_id'] ?? '')
                                        === (string) $model->id
                                    )
                                >
                                    {{ $model->kode_model }}
                                    · {{ $model->architectureLabel() }}
                                    {{ $model->is_active ? '· Aktif' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">
                            Status Evaluasi
                        </label>

                        <select
                            name="evaluation"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                            <option
                                value="all"
                                @selected(
                                    ($filters['evaluation'] ?? 'all') === 'all'
                                )
                            >
                                Semua hasil
                            </option>

                            <option
                                value="evaluated"
                                @selected(
                                    ($filters['evaluation'] ?? 'all')
                                    === 'evaluated'
                                )
                            >
                                Memiliki IPK aktual
                            </option>

                            <option
                                value="not_evaluated"
                                @selected(
                                    ($filters['evaluation'] ?? 'all')
                                    === 'not_evaluated'
                                )
                            >
                                Belum dievaluasi
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            value="{{ $filters['date_from'] ?? '' }}"
                            class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            value="{{ $filters['date_to'] ?? '' }}"
                            class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                    </div>
                </div>

                <div class="mt-5 flex justify-end gap-2 border-t border-slate-100 pt-5">
                    @if ($hasActiveFilter)
                        <a
                            href="{{ route('admin.laporan.index') }}"
                            class="inline-flex h-10 items-center rounded-lg border border-slate-200 px-4 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                        >
                            Reset
                        </a>
                    @endif

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center rounded-lg bg-blue-600 px-5 text-xs font-semibold text-white hover:bg-blue-700"
                    >
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </section>

        {{-- Evaluasi --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="report-card rounded-xl border border-blue-100 bg-blue-50 p-5">
                <p class="text-xs font-medium text-blue-500">
                    IPK Aktual Rata-rata
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $averageActual !== null
                        ? number_format((float) $averageActual, 3)
                        : '-' }}
                </p>
            </article>

            <article class="report-card rounded-xl border border-violet-100 bg-violet-50 p-5">
                <p class="text-xs font-medium text-violet-500">
                    Mean Squared Error
                </p>

                <p class="mt-2 text-2xl font-bold text-violet-700">
                    {{ $meanSquaredError !== null
                        ? number_format(
                            (float) $meanSquaredError,
                            6
                        )
                        : '-' }}
                </p>
            </article>

            <article class="report-card rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                <p class="text-xs font-medium text-emerald-500">
                    Error Terbaik
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-700">
                    {{ $bestAbsoluteError !== null
                        ? number_format(
                            (float) $bestAbsoluteError,
                            6
                        )
                        : '-' }}
                </p>
            </article>

            <article class="report-card rounded-xl border border-amber-100 bg-amber-50 p-5">
                <p class="text-xs font-medium text-amber-500">
                    Tingkat Evaluasi
                </p>

                <p class="mt-2 text-2xl font-bold text-amber-700">
                    {{ number_format($evaluationRate, 1) }}%
                </p>
            </article>
        </section>

        {{-- Model aktif --}}
        @if ($activeModel)
            <section class="report-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
                    <div class="xl:col-span-2">
                        <p class="text-xs text-slate-400">
                            Model Aktif
                        </p>

                        <p class="mt-2 text-base font-bold text-slate-800">
                            {{ $activeModel->nama_model }}
                        </p>

                        <p class="mt-1 text-xs text-blue-600">
                            {{ $activeModel->kode_model }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">Arsitektur</p>
                        <p class="mt-2 font-bold text-slate-700">
                            {{ $activeModel->architectureLabel() }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">Dataset</p>
                        <p class="mt-2 font-bold text-slate-700">
                            {{ number_format($activeModel->total_data) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">MAE Model</p>
                        <p class="mt-2 font-bold text-blue-600">
                            {{ number_format((float) $activeModel->mae, 6) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">MSE Model</p>
                        <p class="mt-2 font-bold text-violet-600">
                            {{ number_format((float) $activeModel->mse, 6) }}
                        </p>
                    </div>
                </div>
            </section>
        @endif

        {{-- Ringkasan angkatan --}}
        <section class="report-card overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-base font-semibold text-slate-800">
                    Ringkasan Berdasarkan Angkatan
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4">Angkatan</th>
                            <th class="px-5 py-4 text-center">Mahasiswa</th>
                            <th class="px-5 py-4 text-center">Prediksi</th>
                            <th class="px-5 py-4 text-center">Rata Prediksi</th>
                            <th class="px-5 py-4 text-center">Rata Aktual</th>
                            <th class="px-5 py-4 text-center">MAE</th>
                            <th class="px-6 py-4 text-center">MSE</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($cohortSummaries as $summary)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ $summary->angkatan ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ number_format($summary->total_mahasiswa) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ number_format($summary->total_prediksi) }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-blue-600">
                                    {{ number_format(
                                        (float) $summary->rata_prediksi,
                                        3
                                    ) }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-violet-600">
                                    {{ $summary->rata_aktual !== null
                                        ? number_format(
                                            (float) $summary->rata_aktual,
                                            3
                                        )
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-amber-600">
                                    {{ $summary->mae !== null
                                        ? number_format(
                                            (float) $summary->mae,
                                            6
                                        )
                                        : '-' }}
                                </td>

                                <td class="px-6 py-4 text-center font-semibold text-slate-600">
                                    {{ $summary->mse !== null
                                        ? number_format(
                                            (float) $summary->mse,
                                            6
                                        )
                                        : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="7"
                                    class="px-6 py-12 text-center text-sm text-slate-400"
                                >
                                    Belum ada ringkasan angkatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Detail laporan --}}
        <section class="report-card overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Detail Hasil Prediksi
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Data mahasiswa dan hasil evaluasi prediksi.
                        </p>
                    </div>

                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                        {{ number_format($totalPredictions) }} hasil
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1200px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4">Mahasiswa</th>
                            <th class="px-5 py-4">Nomor</th>
                            <th class="px-5 py-4 text-center">Rata IPS</th>
                            <th class="px-5 py-4 text-center">Prediksi</th>
                            <th class="px-5 py-4 text-center">Aktual</th>
                            <th class="px-5 py-4 text-center">Abs. Error</th>
                            <th class="px-5 py-4 text-center">Squared Error</th>
                            <th class="px-5 py-4">Model</th>
                            <th class="px-6 py-4">Tanggal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($results as $prediction)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $prediction->mahasiswa?->nama ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $prediction->mahasiswa?->nim ?? '-' }}
                                        · Angkatan
                                        {{ $prediction->mahasiswa?->angkatan ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-xs font-semibold text-slate-600">
                                    {{ $prediction->nomor_prediksi }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-slate-600">
                                    {{ number_format(
                                        $prediction->averageIps(),
                                        3
                                    ) }}
                                </td>

                                <td class="px-5 py-4 text-center font-bold text-blue-600">
                                    {{ number_format(
                                        (float) $prediction->ipk_prediksi,
                                        3
                                    ) }}
                                </td>

                                <td class="px-5 py-4 text-center font-bold text-violet-600">
                                    {{ $prediction->ipk_aktual !== null
                                        ? number_format(
                                            (float) $prediction->ipk_aktual,
                                            3
                                        )
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-amber-600">
                                    {{ $prediction->absolute_error !== null
                                        ? number_format(
                                            (float) $prediction->absolute_error,
                                            6
                                        )
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-slate-600">
                                    {{ $prediction->squared_error !== null
                                        ? number_format(
                                            (float) $prediction->squared_error,
                                            6
                                        )
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-xs font-semibold text-slate-600">
                                        {{ $prediction->modelAnn?->kode_model ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $prediction->predicted_at?->format(
                                        'd-m-Y H:i'
                                    ) ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="9"
                                    class="px-6 py-16 text-center"
                                >
                                    <p class="text-sm font-semibold text-slate-600">
                                        Belum ada data laporan
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Jalankan prediksi IPK terlebih dahulu.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($results->hasPages())
                <div class="no-print border-t border-slate-100 px-6 py-4">
                    {{ $results->links() }}
                </div>
            @endif
        </section>

        <div class="print-only mt-8">
            <div class="ml-auto w-64 text-center text-sm">
                <p>Administrator,</p>

                <div class="h-16"></div>

                <p class="font-semibold text-slate-900">
                    {{ auth()->user()?->name ?? 'Administrator' }}
                </p>
            </div>
        </div>
    </div>
@endsection