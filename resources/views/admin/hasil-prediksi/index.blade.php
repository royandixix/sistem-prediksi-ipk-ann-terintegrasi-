@extends('layouts.admin')

@section('title', 'Hasil Prediksi')

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
                    <span>Hasil Prediksi</span>
                </div>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
                    Hasil Prediksi IPK
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Monitoring, evaluasi, dan analisis hasil prediksi IPK
                    mahasiswa menggunakan model Artificial Neural Network.
                </p>
            </div>

            <a
                href="{{ route('admin.prediksi-ipk.index') }}"
                class="inline-flex h-11 items-center justify-center gap-2 self-start rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 xl:self-auto"
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

        {{-- Validation errors --}}
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
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Statistics --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- Total results --}}
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Total Hasil
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalResults) }}
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

            {{-- Unique students --}}
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

            {{-- Average prediction --}}
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Rata-rata Prediksi
                        </p>

                        <p class="mt-3 text-3xl font-bold text-violet-600">
                            {{ $averagePrediction !== null
                                ? number_format(
                                    (float) $averagePrediction,
                                    3
                                )
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

            {{-- Average absolute error --}}
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            MAE Hasil
                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-600">
                            {{ $averageAbsoluteError !== null
                                ? number_format(
                                    (float) $averageAbsoluteError,
                                    4
                                )
                                : '-' }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            {{ number_format($evaluatedResults) }}
                            hasil memiliki IPK aktual
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
                            <path d="M12 8v8"></path>
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
                            Filter Hasil Prediksi
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Temukan hasil berdasarkan mahasiswa, model,
                            angkatan, status evaluasi, atau tanggal.
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
                action="{{ route('admin.hasil-prediksi.index') }}"
                method="GET"
                class="p-6"
            >
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    {{-- Search --}}
                    <div>
                        <label
                            for="search"
                            class="mb-2 block text-xs font-semibold text-slate-600"
                        >
                            Pencarian
                        </label>

                        <div class="relative">
                            <svg
                                class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ $filters['search'] ?? '' }}"
                                placeholder="Nama, NIM, atau nomor prediksi"
                                class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                            >
                        </div>
                    </div>

                    {{-- Cohort --}}
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

                    {{-- Model --}}
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

                    {{-- Evaluation --}}
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
                                    ($filters['evaluation'] ?? 'all')
                                    === 'all'
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
                                Belum memiliki IPK aktual
                            </option>
                        </select>
                    </div>

                    {{-- Date from --}}
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

                    {{-- Date to --}}
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
                        Statistik dan tabel mengikuti filter yang diterapkan.
                    </p>

                    <div class="flex flex-col gap-2 sm:flex-row">
                        @if ($hasActiveFilter)
                            <a
                                href="{{ route('admin.hasil-prediksi.index') }}"
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

        {{-- Evaluation summary --}}
        @if ($evaluatedResults > 0)
            <section class="grid gap-4 lg:grid-cols-3">
                <article class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-xs font-medium text-blue-500">
                        Jumlah Data Evaluasi
                    </p>

                    <p class="mt-2 text-2xl font-bold text-blue-700">
                        {{ number_format($evaluatedResults) }}
                    </p>

                    <p class="mt-1 text-[11px] text-blue-500">
                        Memiliki IPK prediksi dan aktual
                    </p>
                </article>

                <article class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                    <p class="text-xs font-medium text-amber-500">
                        Mean Absolute Error
                    </p>

                    <p class="mt-2 text-2xl font-bold text-amber-700">
                        {{ $averageAbsoluteError !== null
                            ? number_format(
                                (float) $averageAbsoluteError,
                                6
                            )
                            : '-' }}
                    </p>

                    <p class="mt-1 text-[11px] text-amber-500">
                        Rata-rata selisih absolut
                    </p>
                </article>

                <article class="rounded-xl border border-violet-100 bg-violet-50 p-5">
                    <p class="text-xs font-medium text-violet-500">
                        Mean Squared Error
                    </p>

                    <p class="mt-2 text-2xl font-bold text-violet-700">
                        {{ $averageSquaredError !== null
                            ? number_format(
                                (float) $averageSquaredError,
                                6
                            )
                            : '-' }}
                    </p>

                    <p class="mt-1 text-[11px] text-violet-500">
                        Rata-rata kuadrat error
                    </p>
                </article>
            </section>
        @endif

        {{-- Results table --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Daftar Hasil Prediksi
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Data hasil prediksi beserta perbandingan terhadap
                            IPK aktual.
                        </p>
                    </div>

                    <span class="self-start rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 sm:self-auto">
                        {{ number_format($results->total()) }} hasil
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1250px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10px] uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4 font-semibold">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Nomor Prediksi
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
                                Error Absolut
                            </th>

                            <th class="px-5 py-4 text-center font-semibold">
                                Squared Error
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Model
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Diproses
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($results as $result)
                            @php
                                $absoluteError = $result->absolute_error !== null
                                    ? (float) $result->absolute_error
                                    : null;

                                if ($absoluteError === null) {
                                    $errorLabel = 'Belum dievaluasi';
                                    $errorClass = 'bg-slate-100 text-slate-500';
                                } elseif ($absoluteError <= 0.05) {
                                    $errorLabel = 'Sangat dekat';
                                    $errorClass = 'bg-emerald-50 text-emerald-600';
                                } elseif ($absoluteError <= 0.10) {
                                    $errorLabel = 'Dekat';
                                    $errorClass = 'bg-blue-50 text-blue-600';
                                } elseif ($absoluteError <= 0.20) {
                                    $errorLabel = 'Cukup dekat';
                                    $errorClass = 'bg-amber-50 text-amber-600';
                                } else {
                                    $errorLabel = 'Selisih tinggi';
                                    $errorClass = 'bg-red-50 text-red-600';
                                }
                            @endphp

                            <tr class="transition hover:bg-slate-50">
                                {{-- Student --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                            {{ strtoupper(
                                                substr(
                                                    $result->mahasiswa?->nama
                                                        ?? 'M',
                                                    0,
                                                    1
                                                )
                                            ) }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">
                                                {{ $result->mahasiswa?->nama ?? '-' }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $result->mahasiswa?->nim ?? '-' }}

                                                @if ($result->mahasiswa?->angkatan)
                                                    · Angkatan
                                                    {{ $result->mahasiswa->angkatan }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Number --}}
                                <td class="px-5 py-4">
                                    <p class="max-w-44 truncate font-mono text-xs font-semibold text-slate-600">
                                        {{ $result->nomor_prediksi }}
                                    </p>
                                </td>

                                {{-- Average IPS --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-semibold text-slate-600">
                                        {{ number_format(
                                            $result->averageIps(),
                                            3
                                        ) }}
                                    </span>
                                </td>

                                {{-- Predicted --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="text-base font-bold text-blue-600">
                                        {{ number_format(
                                            (float) $result->ipk_prediksi,
                                            3
                                        ) }}
                                    </span>
                                </td>

                                {{-- Actual --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($result->ipk_aktual !== null)
                                        <span class="text-base font-bold text-violet-600">
                                            {{ number_format(
                                                (float) $result->ipk_aktual,
                                                3
                                            ) }}
                                        </span>
                                    @else
                                        <span class="text-xs font-medium text-slate-400">
                                            Belum tersedia
                                        </span>
                                    @endif
                                </td>

                                {{-- Absolute error --}}
                                <td class="px-5 py-4 text-center">
                                    <p class="text-sm font-bold text-amber-600">
                                        {{ $result->absolute_error !== null
                                            ? number_format(
                                                (float) $result->absolute_error,
                                                6
                                            )
                                            : '-' }}
                                    </p>

                                    <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $errorClass }}">
                                        {{ $errorLabel }}
                                    </span>
                                </td>

                                {{-- Squared error --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-semibold text-violet-600">
                                        {{ $result->squared_error !== null
                                            ? number_format(
                                                (float) $result->squared_error,
                                                6
                                            )
                                            : '-' }}
                                    </span>
                                </td>

                                {{-- Model --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        Model #{{ $result->model_ann_id }}
                                    </span>

                                    @if ($result->modelAnn)
                                        <p class="mt-2 text-[10px] text-slate-400">
                                            {{ $result->modelAnn->architectureLabel() }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Date and user --}}
                                <td class="px-6 py-4">
                                    <p class="text-xs font-semibold text-slate-600">
                                        {{ $result->predicted_at?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-[10px] text-slate-400">
                                        {{ $result->predicted_at?->format('H:i') ?? '-' }}
                                    </p>

                                    <p class="mt-2 text-[10px] text-slate-400">
                                        Oleh:
                                        {{ $result->predictedBy?->name
                                            ?? $result->predictedBy?->username
                                            ?? 'Administrator' }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="9"
                                    class="px-6 py-16 text-center"
                                >
                                    <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                        <svg
                                            class="h-7 w-7"
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

                                    <p class="mt-4 text-sm font-semibold text-slate-600">
                                        Belum ada hasil prediksi
                                    </p>

                                    <p class="mx-auto mt-1 max-w-sm text-xs leading-5 text-slate-400">
                                        Jalankan prediksi IPK mahasiswa atau
                                        ubah filter untuk menampilkan hasil.
                                    </p>

                                    <a
                                        href="{{ route('admin.prediksi-ipk.index') }}"
                                        class="mt-4 inline-flex h-10 items-center justify-center rounded-lg bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        Buka Prediksi IPK
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($results->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $results->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection