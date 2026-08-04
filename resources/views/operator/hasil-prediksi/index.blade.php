@extends('layouts.operator')

@section('title', 'Hasil Prediksi')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                    <a
                        href="{{ route('operator.dashboard') }}"
                        class="transition hover:text-blue-600"
                    >
                        Dashboard
                    </a>

                    <span>/</span>
                    <span>Hasil Prediksi</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                    Hasil Prediksi IPK
                </h1>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Riwayat dan evaluasi hasil prediksi yang diproses melalui akun Operator.
                </p>
            </div>

            <a
                href="{{ route('operator.prediksi-ipk.create') }}"
                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700"
            >
                <svg
                    class="h-4 w-4"
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

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-red-100 text-red-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 8v5"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-red-700">
                            Filter tidak dapat diproses
                        </p>

                        <ul class="mt-2 space-y-1 text-xs text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Total Hasil
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalResults) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
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

                        <p class="mt-2 text-xs text-slate-400">
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
                            <circle cx="9" cy="8" r="4"></circle>
                            <path d="M2.5 21a6.5 6.5 0 0 1 13 0"></path>
                            <path d="M17 11a4 4 0 0 1 4 4v6"></path>
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

                        <p class="mt-2 text-xs text-slate-400">
                            Rata-rata hasil ANN
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
                            Rata-rata Error
                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-600">
                            {{ $averageAbsoluteError !== null
                                ? number_format((float) $averageAbsoluteError, 4)
                                : '-' }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            {{ number_format($evaluatedResults) }} hasil dievaluasi
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

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Filter Hasil
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Cari berdasarkan mahasiswa, model, angkatan, evaluasi, atau tanggal.
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
                action="{{ route('operator.hasil-prediksi.index') }}"
                method="GET"
                class="p-5"
            >
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div>
                        <label
                            for="search"
                            class="mb-2 block text-xs font-semibold text-slate-600"
                        >
                            Pencarian
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Nama, NIM, atau nomor prediksi"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
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
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
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
                                    {{ $cohort }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
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
                                    Model #{{ $model->id }} — {{ $model->kode_model }}
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
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                            <option
                                value="all"
                                @selected(($filters['evaluation'] ?? 'all') === 'all')
                            >
                                Semua hasil
                            </option>

                            <option
                                value="evaluated"
                                @selected(($filters['evaluation'] ?? '') === 'evaluated')
                            >
                                Sudah dievaluasi
                            </option>

                            <option
                                value="not_evaluated"
                                @selected(($filters['evaluation'] ?? '') === 'not_evaluated')
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
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
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
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                    </div>
                </div>

                <div class="mt-5 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    @if ($hasActiveFilter)
                        <a
                            href="{{ route('operator.hasil-prediksi.index') }}"
                            class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        >
                            Reset Filter
                        </a>
                    @endif

                    <button
                        type="submit"
                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        Daftar Hasil Prediksi
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Menampilkan hasil yang diproses melalui akun Operator ini.
                    </p>
                </div>

                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">
                    {{ number_format($results->total()) }} data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[920px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-5 py-4">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4">
                                IPK Prediksi
                            </th>

                            <th class="px-5 py-4">
                                IPK Aktual
                            </th>

                            <th class="px-5 py-4">
                                Absolute Error
                            </th>

                            <th class="px-5 py-4">
                                Model
                            </th>

                            <th class="px-5 py-4">
                                Waktu
                            </th>

                            <th class="px-5 py-4 text-right">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($results as $result)
                            <tr class="transition hover:bg-blue-50/40">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-bold text-slate-700">
                                        {{ $result->mahasiswa?->nama ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $result->mahasiswa?->nim ?? '-' }}
                                        · {{ $result->nomor_prediksi }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-base font-bold text-blue-600">
                                        {{ number_format((float) $result->ipk_prediksi, 3) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-slate-600">
                                    {{ $result->ipk_aktual !== null
                                        ? number_format((float) $result->ipk_aktual, 3)
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($result->absolute_error !== null)
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-600">
                                            {{ number_format((float) $result->absolute_error, 4) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                        #{{ $result->model_ann_id }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-xs text-slate-500">
                                    {{ $result->predicted_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a
                                        href="{{ route('operator.hasil-prediksi.show', $result) }}"
                                        class="inline-flex h-9 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-3 text-xs font-bold text-blue-600 transition hover:bg-blue-600 hover:text-white"
                                    >
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="7"
                                    class="px-5 py-16 text-center"
                                >
                                    <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                        <svg
                                            class="h-6 w-6"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"></path>
                                        </svg>
                                    </div>

                                    <p class="mt-4 text-sm font-semibold text-slate-600">
                                        Belum ada hasil prediksi
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Jalankan proses prediksi untuk menampilkan hasil.
                                    </p>

                                    <a
                                        href="{{ route('operator.prediksi-ipk.create') }}"
                                        class="mt-4 inline-flex min-h-10 items-center justify-center rounded-lg bg-blue-600 px-4 text-xs font-bold text-white"
                                    >
                                        Jalankan Prediksi
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($results->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $results->links() }}
                </div>
            @endif
        </section>

        @if ($averageSquaredError !== null)
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Rata-rata Squared Error
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-700">
                            {{ number_format((float) $averageSquaredError, 6) }}
                        </p>
                    </div>

                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                        MSE hasil
                    </span>
                </div>
            </div>
        @endif
    </div>
@endsection