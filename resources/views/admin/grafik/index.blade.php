@extends('layouts.admin')

@section('title', 'Grafik Analisis')

@section('content')
    <div class="space-y-5">
        {{-- Header --}}
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <div class="flex items-center gap-2 text-[10px] text-slate-400">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="transition hover:text-blue-600"
                    >
                        Dashboard
                    </a>

                    <span>/</span>
                    <span>Grafik</span>
                </div>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
                    Grafik Analisis Prediksi IPK
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Visualisasi perbandingan, distribusi, tren, dan evaluasi
                    hasil prediksi Artificial Neural Network.
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row">
                <a
                    href="{{ route('admin.hasil-prediksi.index') }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 6h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 18h16"></path>
                    </svg>

                    Hasil Prediksi
                </a>

                <a
                    href="{{ route('admin.prediksi-ipk.index') }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>

                    Prediksi Baru
                </a>
            </div>
        </div>

        {{-- Validation --}}
        @if ($errors->any())
            <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 8v5"></path>
                    <path d="M12 17h.01"></path>
                </svg>

                <div>
                    <p class="text-sm font-semibold text-red-700">
                        Filter tidak dapat diproses
                    </p>

                    <ul class="mt-1 space-y-1 text-xs text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Statistik utama --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Total Prediksi
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalPredictions) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Hasil sesuai filter
                        </p>
                    </div>

                    <div class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M4 19V9"></path>
                            <path d="M10 19V5"></path>
                            <path d="M16 19v-7"></path>
                            <path d="M22 19V3"></path>
                            <path d="M2 19h22"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Mahasiswa
                        </p>

                        <p class="mt-3 text-3xl font-bold text-blue-600">
                            {{ number_format($totalStudents) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Mahasiswa unik diprediksi
                        </p>
                    </div>

                    <div class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M19 8v6"></path>
                            <path d="M22 11h-6"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
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
                    </div>

                    <div class="grid h-11 w-11 place-items-center rounded-xl bg-violet-50 text-violet-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M4 17 10 11l4 4 6-8"></path>
                            <path d="M14 7h6v6"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            MAE Prediksi
                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-600">
                            {{ $meanAbsoluteError !== null
                                ? number_format((float) $meanAbsoluteError, 4)
                                : '-' }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            {{ number_format($evaluatedCount) }}
                            data telah dievaluasi
                        </p>
                    </div>

                    <div class="grid h-11 w-11 place-items-center rounded-xl bg-amber-50 text-amber-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8 12h8"></path>
                        </svg>
                    </div>
                </div>
            </article>
        </section>

        {{-- Filter --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Filter Grafik
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Analisis grafik berdasarkan model, angkatan,
                            evaluasi, dan rentang tanggal.
                        </p>
                    </div>

                    <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-slate-500">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M4 5h16"></path>
                            <path d="M7 12h10"></path>
                            <path d="M10 19h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <form
                action="{{ route('admin.grafik.index') }}"
                method="GET"
                class="p-6"
            >
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div>
                        <label
                            for="model_id"
                            class="mb-2 block text-xs font-semibold text-slate-600"
                        >
                            Model ANN
                        </label>

                        <select
                            id="model_id"
                            name="model_id"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
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
                                    Model #{{ $model->id }}
                                    · {{ $model->architectureLabel() }}
                                    {{ $model->is_active ? '· Aktif' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            for="angkatan"
                            class="mb-2 block text-xs font-semibold text-slate-600"
                        >
                            Angkatan
                        </label>

                        <select
                            id="angkatan"
                            name="angkatan"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
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
                        <label
                            for="evaluation"
                            class="mb-2 block text-xs font-semibold text-slate-600"
                        >
                            Status Evaluasi
                        </label>

                        <select
                            id="evaluation"
                            name="evaluation"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
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
                        <label
                            for="date_from"
                            class="mb-2 block text-xs font-semibold text-slate-600"
                        >
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="date_from"
                            name="date_from"
                            value="{{ $filters['date_from'] ?? '' }}"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                    </div>

                    <div>
                        <label
                            for="date_to"
                            class="mb-2 block text-xs font-semibold text-slate-600"
                        >
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            id="date_to"
                            name="date_to"
                            value="{{ $filters['date_to'] ?? '' }}"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                        >
                    </div>
                </div>

                <div class="mt-5 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-400">
                        Semua statistik dan grafik mengikuti filter.
                    </p>

                    <div class="flex flex-col gap-2 sm:flex-row">
                        @if ($hasActiveFilter)
                            <a
                                href="{{ route('admin.grafik.index') }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 transition hover:bg-slate-50"
                            >
                                Reset Filter
                            </a>
                        @endif

                        <button
                            type="submit"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 text-xs font-semibold text-white transition hover:bg-blue-700"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M4 5h16"></path>
                                <path d="M7 12h10"></path>
                                <path d="M10 19h4"></path>
                            </svg>

                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </section>

        @if ($hasData)
            {{-- Ringkasan evaluasi --}}
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-xs font-medium text-blue-500">
                        Rata-rata IPK Aktual
                    </p>

                    <p class="mt-2 text-2xl font-bold text-blue-700">
                        {{ $averageActual !== null
                            ? number_format((float) $averageActual, 3)
                            : '-' }}
                    </p>
                </article>

                <article class="rounded-xl border border-violet-100 bg-violet-50 p-5">
                    <p class="text-xs font-medium text-violet-500">
                        Mean Squared Error
                    </p>

                    <p class="mt-2 text-2xl font-bold text-violet-700">
                        {{ $meanSquaredError !== null
                            ? number_format((float) $meanSquaredError, 6)
                            : '-' }}
                    </p>
                </article>

                <article class="rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                    <p class="text-xs font-medium text-emerald-500">
                        Error Terbaik
                    </p>

                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        {{ $bestAbsoluteError !== null
                            ? number_format((float) $bestAbsoluteError, 6)
                            : '-' }}
                    </p>
                </article>

                <article class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-medium text-slate-400">
                        Prediksi Terakhir
                    </p>

                    <p class="mt-2 text-base font-bold text-slate-700">
                        {{ $latestPredictionAt?->format('d M Y') ?? '-' }}
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        {{ $latestPredictionAt?->format('H:i') ?? '-' }}
                    </p>
                </article>
            </section>

            {{-- Grafik utama --}}
            <div class="grid gap-5 xl:grid-cols-[minmax(0,1.35fr)_minmax(340px,.65fr)]">
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-base font-semibold text-slate-800">
                            Perbandingan IPK Prediksi dan Aktual
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Maksimal 12 hasil prediksi terbaru.
                        </p>
                    </div>

                    <div class="h-[380px] p-6">
                        <canvas id="comparison-chart"></canvas>
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-base font-semibold text-slate-800">
                            Distribusi IPK Prediksi
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Pengelompokan nilai IPK hasil ANN.
                        </p>
                    </div>

                    <div class="h-[380px] p-6">
                        <canvas id="distribution-chart"></canvas>
                    </div>
                </section>
            </div>

            <div class="grid gap-5 xl:grid-cols-2">
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-base font-semibold text-slate-800">
                            Tren Prediksi
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Rata-rata IPK prediksi dan aktual per bulan.
                        </p>
                    </div>

                    <div class="h-[340px] p-6">
                        <canvas id="trend-chart"></canvas>
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-base font-semibold text-slate-800">
                            Distribusi Absolute Error
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Pengelompokan tingkat selisih prediksi.
                        </p>
                    </div>

                    @if ($evaluatedCount > 0)
                        <div class="h-[340px] p-6">
                            <canvas id="error-chart"></canvas>
                        </div>
                    @else
                        <div class="grid h-[340px] place-items-center p-6 text-center">
                            <div>
                                <p class="text-sm font-semibold text-slate-600">
                                    Belum ada data evaluasi
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    IPK aktual diperlukan untuk menghitung error.
                                </p>
                            </div>
                        </div>
                    @endif
                </section>
            </div>

            {{-- Ringkasan angkatan --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-base font-semibold text-slate-800">
                        Ringkasan Berdasarkan Angkatan
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Perbandingan hasil prediksi pada setiap angkatan.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[750px] text-left">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/60 text-[10px] uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-4 font-semibold">
                                    Angkatan
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    Jumlah
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    Rata-rata Prediksi
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    Rata-rata Aktual
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    MAE
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($cohortSummary as $summary)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-slate-700">
                                            Angkatan {{ $summary['angkatan'] }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-center text-sm font-semibold text-slate-600">
                                        {{ number_format($summary['jumlah']) }}
                                    </td>

                                    <td class="px-5 py-4 text-center text-sm font-bold text-blue-600">
                                        {{ number_format(
                                            (float) $summary['rata_prediksi'],
                                            3
                                        ) }}
                                    </td>

                                    <td class="px-5 py-4 text-center text-sm font-bold text-violet-600">
                                        {{ $summary['rata_aktual'] !== null
                                            ? number_format(
                                                (float) $summary['rata_aktual'],
                                                3
                                            )
                                            : '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-sm font-bold text-amber-600">
                                        {{ $summary['mae'] !== null
                                            ? number_format(
                                                (float) $summary['mae'],
                                                4
                                            )
                                            : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="5"
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

            {{-- Data terbaru --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                Data Prediksi Terbaru
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Data yang digunakan dalam visualisasi.
                            </p>
                        </div>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                            {{ number_format($recentPredictions->count()) }} data
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-left">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/60 text-[10px] uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-4 font-semibold">
                                    Mahasiswa
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    Rata-rata IPS
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    IPK Prediksi
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    IPK Aktual
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    Error
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Tanggal
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentPredictions as $prediction)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ $prediction->mahasiswa?->nama ?? '-' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $prediction->mahasiswa?->nim ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-center text-sm font-semibold text-slate-600">
                                        {{ number_format(
                                            $prediction->averageIps(),
                                            3
                                        ) }}
                                    </td>

                                    <td class="px-5 py-4 text-center text-sm font-bold text-blue-600">
                                        {{ number_format(
                                            (float) $prediction->ipk_prediksi,
                                            3
                                        ) }}
                                    </td>

                                    <td class="px-5 py-4 text-center text-sm font-bold text-violet-600">
                                        {{ $prediction->ipk_aktual !== null
                                            ? number_format(
                                                (float) $prediction->ipk_aktual,
                                                3
                                            )
                                            : '-' }}
                                    </td>

                                    <td class="px-5 py-4 text-center text-sm font-bold text-amber-600">
                                        {{ $prediction->absolute_error !== null
                                            ? number_format(
                                                (float) $prediction->absolute_error,
                                                4
                                            )
                                            : '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $prediction->predicted_at?->format('d M Y, H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            {{-- Empty state --}}
            <section class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center shadow-sm">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-blue-50 text-blue-500">
                    <svg
                        class="h-8 w-8"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 19V9"></path>
                        <path d="M10 19V5"></path>
                        <path d="M16 19v-7"></path>
                        <path d="M22 19V3"></path>
                        <path d="M2 19h22"></path>
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-bold text-slate-700">
                    Belum ada data grafik
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-400">
                    Grafik akan ditampilkan setelah model ANN digunakan untuk
                    memproses prediksi IPK mahasiswa.
                </p>

                <a
                    href="{{ route('admin.prediksi-ipk.index') }}"
                    class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Buka Prediksi IPK
                </a>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    @if ($hasData)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof Chart === 'undefined') {
                    return;
                }

                Chart.defaults.font.family =
                    getComputedStyle(document.body).fontFamily;

                Chart.defaults.color = '#64748b';

                const gridColor = 'rgba(148, 163, 184, 0.15)';

                const comparisonCanvas =
                    document.getElementById('comparison-chart');

                if (comparisonCanvas) {
                    new Chart(comparisonCanvas, {
                        type: 'line',
                        data: {
                            labels: @json($comparisonLabels),
                            datasets: [
                                {
                                    label: 'IPK Prediksi',
                                    data: @json($comparisonPredicted),
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.10)',
                                    pointBackgroundColor: '#2563eb',
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: false,
                                },
                                {
                                    label: 'IPK Aktual',
                                    data: @json($comparisonActual),
                                    borderColor: '#7c3aed',
                                    backgroundColor: 'rgba(124, 58, 237, 0.10)',
                                    pointBackgroundColor: '#7c3aed',
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    borderWidth: 2,
                                    borderDash: [6, 4],
                                    tension: 0.3,
                                    spanGaps: true,
                                    fill: false,
                                },
                            ],
                        },
                        options: {
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            scales: {
                                y: {
                                    min: 0,
                                    max: 4,
                                    ticks: {
                                        stepSize: 0.5,
                                    },
                                    grid: {
                                        color: gridColor,
                                    },
                                },
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                    ticks: {
                                        maxRotation: 35,
                                        minRotation: 0,
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                },
                            },
                        },
                    });
                }

                const distributionCanvas =
                    document.getElementById('distribution-chart');

                if (distributionCanvas) {
                    new Chart(distributionCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: @json($distributionLabels),
                            datasets: [
                                {
                                    data: @json($distributionValues),
                                    backgroundColor: [
                                        '#ef4444',
                                        '#f97316',
                                        '#f59e0b',
                                        '#2563eb',
                                        '#7c3aed',
                                        '#10b981',
                                    ],
                                    borderWidth: 0,
                                    hoverOffset: 8,
                                },
                            ],
                        },
                        options: {
                            maintainAspectRatio: false,
                            cutout: '66%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 16,
                                    },
                                },
                            },
                        },
                    });
                }

                const trendCanvas =
                    document.getElementById('trend-chart');

                if (trendCanvas) {
                    new Chart(trendCanvas, {
                        type: 'line',
                        data: {
                            labels: @json($trendLabels),
                            datasets: [
                                {
                                    label: 'Rata-rata Prediksi',
                                    data: @json($trendPredicted),
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                                    pointBackgroundColor: '#2563eb',
                                    borderWidth: 2,
                                    tension: 0.35,
                                    fill: true,
                                },
                                {
                                    label: 'Rata-rata Aktual',
                                    data: @json($trendActual),
                                    borderColor: '#7c3aed',
                                    backgroundColor: 'rgba(124, 58, 237, 0.08)',
                                    pointBackgroundColor: '#7c3aed',
                                    borderWidth: 2,
                                    tension: 0.35,
                                    spanGaps: true,
                                    fill: false,
                                },
                            ],
                        },
                        options: {
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            scales: {
                                y: {
                                    min: 0,
                                    max: 4,
                                    ticks: {
                                        stepSize: 0.5,
                                    },
                                    grid: {
                                        color: gridColor,
                                    },
                                },
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                },
                            },
                        },
                    });
                }

                const errorCanvas =
                    document.getElementById('error-chart');

                if (errorCanvas) {
                    new Chart(errorCanvas, {
                        type: 'bar',
                        data: {
                            labels: @json($errorLabels),
                            datasets: [
                                {
                                    label: 'Jumlah Prediksi',
                                    data: @json($errorValues),
                                    backgroundColor: [
                                        '#10b981',
                                        '#2563eb',
                                        '#f59e0b',
                                        '#ef4444',
                                    ],
                                    borderRadius: 8,
                                    borderSkipped: false,
                                },
                            ],
                        },
                        options: {
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                    },
                                    grid: {
                                        color: gridColor,
                                    },
                                },
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    display: false,
                                },
                            },
                        },
                    });
                }
            });
        </script>
    @endif
@endpush