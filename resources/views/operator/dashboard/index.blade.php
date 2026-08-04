@extends('layouts.operator')

@section('title', 'Dashboard Operator')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                    <span>Operator</span>
                    <span>/</span>
                    <span>Dashboard</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                    Dashboard Operator
                </h1>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Kelola input IPS, proses prediksi, dan pantau hasil prediksi IPK mahasiswa.
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row">
                <a
                    href="{{ route('operator.data-ips.create') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
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

                    Input Data IPS
                </a>

                <a
                    href="{{ route('operator.prediksi-ipk.create') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 19V5"></path>
                        <path d="M4 19h16"></path>
                        <path d="m7 15 4-4 3 2 5-7"></path>
                    </svg>

                    Proses Prediksi
                </a>
            </div>
        </div>

        @if ($modelReady)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m5 12 4 4L19 6"></path>
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-emerald-700">
                            Sistem Prediksi Siap
                        </p>

                        <p class="mt-1 text-sm text-emerald-600">
                            Model #{{ $activeModel->id }} dengan arsitektur
                            {{ $activeModel->architectureLabel() }} siap digunakan.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-amber-100 text-amber-600">
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
                        <p class="text-sm font-bold text-amber-700">
                            Model ANN belum tersedia
                        </p>

                        <p class="mt-1 text-sm text-amber-600">
                            Hubungi administrator untuk melakukan training dan mengaktifkan model.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-3xl font-bold text-slate-800">
                            {{ number_format($candidateCount) }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Kandidat Prediksi
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Memiliki IPS Semester 1–5 lengkap
                        </p>
                    </div>

                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600">
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
                        <p class="text-3xl font-bold text-slate-800">
                            {{ number_format($predictedStudentCount) }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Sudah Diprediksi
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            {{ $predictionRate }}% dari seluruh kandidat
                        </p>
                    </div>

                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m5 12 4 4L19 6"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-3xl font-bold text-slate-800">
                            {{ number_format($pendingCount) }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Menunggu Prediksi
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Belum memiliki hasil prediksi
                        </p>
                    </div>

                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-amber-50 text-amber-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-3xl font-bold text-slate-800">
                            {{ number_format($myPredictionCount) }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Prediksi Saya
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Diproses melalui akun ini
                        </p>
                    </div>

                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-violet-50 text-violet-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M4 20V10"></path>
                            <path d="M10 20V4"></path>
                            <path d="M16 20v-7"></path>
                            <path d="M22 20V7"></path>
                        </svg>
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-5 xl:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.75fr)]">
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Hasil Prediksi Terbaru
                        </h2>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Prediksi terakhir yang diproses melalui akun operator.
                        </p>
                    </div>

                    <a
                        href="{{ route('operator.hasil-prediksi.index') }}"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700"
                    >
                        Lihat semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[680px] text-left">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                <th class="px-5 py-3.5">
                                    Mahasiswa
                                </th>

                                <th class="px-5 py-3.5">
                                    Prediksi
                                </th>

                                <th class="px-5 py-3.5">
                                    Aktual
                                </th>

                                <th class="px-5 py-3.5">
                                    Error
                                </th>

                                <th class="px-5 py-3.5 text-right">
                                    Waktu
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentPredictions as $prediction)
                                <tr class="transition hover:bg-blue-50/40">
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-bold text-slate-700">
                                            {{ $prediction->mahasiswa?->nama ?? '-' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $prediction->nomor_prediksi }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-sm font-bold text-blue-600">
                                        {{ number_format((float) $prediction->ipk_prediksi, 3) }}
                                    </td>

                                    <td class="px-5 py-4 text-sm font-semibold text-slate-600">
                                        {{ $prediction->ipk_aktual !== null
                                            ? number_format((float) $prediction->ipk_aktual, 3)
                                            : '-' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm font-semibold text-slate-600">
                                        {{ $prediction->absolute_error !== null
                                            ? number_format((float) $prediction->absolute_error, 4)
                                            : '-' }}
                                    </td>

                                    <td class="px-5 py-4 text-right text-xs text-slate-500">
                                        {{ $prediction->predicted_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="5"
                                        class="px-5 py-14 text-center"
                                    >
                                        <p class="text-sm font-semibold text-slate-500">
                                            Belum ada prediksi
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Jalankan proses prediksi untuk menampilkan data.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Ringkasan Operator
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-slate-400">
                        Statistik hasil yang diproses akun ini.
                    </p>
                </div>

                <div class="space-y-4 p-5">
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-500">
                            Rata-rata IPK Prediksi
                        </p>

                        <p class="mt-2 text-2xl font-bold text-blue-700">
                            {{ number_format($averagePrediction, 3) }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-500">
                            Rata-rata Absolute Error
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-700">
                            {{ number_format($averageAbsoluteError, 4) }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold text-slate-400">
                                    Model Digunakan
                                </p>

                                <p class="mt-1 text-base font-bold text-slate-700">
                                    {{ $activeModel ? 'Model #' . $activeModel->id : 'Belum tersedia' }}
                                </p>
                            </div>

                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $modelReady ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $modelReady ? 'Siap' : 'Tidak siap' }}
                            </span>
                        </div>

                        @if ($activeModel)
                            <div class="mt-4 border-t border-slate-100 pt-4">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-400">
                                        Arsitektur
                                    </span>

                                    <span class="font-bold text-slate-600">
                                        {{ $activeModel->architectureLabel() }}
                                    </span>
                                </div>

                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <span class="text-slate-400">
                                        MAE Testing
                                    </span>

                                    <span class="font-bold text-slate-600">
                                        {{ number_format((float) $activeModel->mae, 4) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </article>
        </section>

        <section>
            <div class="mb-3">
                <h2 class="text-lg font-bold text-slate-800">
                    Akses Cepat
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Pilih pekerjaan yang ingin dilakukan.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <a
                    href="{{ route('operator.data-ips.create') }}"
                    class="flex min-h-20 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:shadow-md"
                >
                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600">
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
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-700">
                            Input Data IPS
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Tambahkan IPS mahasiswa
                        </p>
                    </div>
                </a>

                <a
                    href="{{ route('operator.prediksi-ipk.create') }}"
                    class="flex min-h-20 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-emerald-300 hover:shadow-md"
                >
                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M4 19V5"></path>
                            <path d="M4 19h16"></path>
                            <path d="m7 15 4-4 3 2 5-7"></path>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-700">
                            Jalankan Prediksi
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Proses IPK dengan ANN
                        </p>
                    </div>
                </a>

                <a
                    href="{{ route('operator.hasil-prediksi.index') }}"
                    class="flex min-h-20 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-violet-300 hover:shadow-md"
                >
                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-violet-50 text-violet-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"></path>
                            <path d="m9 10 2 2 4-4"></path>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-700">
                            Lihat Hasil
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Riwayat hasil prediksi
                        </p>
                    </div>
                </a>
            </div>
        </section>
    </div>
@endsection