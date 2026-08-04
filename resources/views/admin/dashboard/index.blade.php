@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <style>
        .dashboard-reveal {
            opacity: 0;
            transform: translateY(18px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .dashboard-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .dashboard-card {
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 14px 30px -15px rgb(15 23 42 / 0.25);
        }

        .dashboard-icon {
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }

        .dashboard-card:hover .dashboard-icon {
            transform: scale(1.08) rotate(-3deg);
        }

        .dashboard-link-arrow {
            transition: transform 0.2s ease;
        }

        .dashboard-card:hover .dashboard-link-arrow,
        .dashboard-action:hover .dashboard-link-arrow {
            transform: translateX(4px);
        }

        .dashboard-progress-bar {
            width: 0;
            transition: width 1.1s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .dashboard-action {
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .dashboard-action:hover {
            transform: translateY(-3px);
            box-shadow:
                0 12px 24px -14px rgb(15 23 42 / 0.28);
        }

        .dashboard-pulse {
            position: relative;
        }

        .dashboard-pulse::after {
            position: absolute;
            inset: 0;
            border-radius: inherit;
            content: '';
            animation: dashboardPulse 2s infinite;
        }

        @keyframes dashboardPulse {
            0% {
                box-shadow: 0 0 0 0 rgb(16 185 129 / 0.35);
            }

            70% {
                box-shadow: 0 0 0 10px rgb(16 185 129 / 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgb(16 185 129 / 0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .dashboard-reveal,
            .dashboard-card,
            .dashboard-icon,
            .dashboard-link-arrow,
            .dashboard-progress-bar,
            .dashboard-action {
                transition: none;
            }

            .dashboard-pulse::after {
                animation: none;
            }
        }
    </style>

    <div class="space-y-6">
        <div
            class="dashboard-reveal flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
            data-dashboard-reveal
            data-delay="0"
        >
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                    <span>Administrator</span>
                    <span>/</span>
                    <span>Dashboard</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                    Dashboard
                </h1>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Ringkasan data dan kesiapan sistem prediksi IPK akhir mahasiswa.
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <span class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm">
                    <svg
                        class="h-4 w-4 text-slate-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                        <path d="M16 3v4"></path>
                        <path d="M8 3v4"></path>
                        <path d="M3 10h18"></path>
                    </svg>

                    {{ now()->translatedFormat('d F Y') }}
                </span>

                <a
                    href="{{ route('admin.mahasiswa.create') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-lg"
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

                    Tambah Mahasiswa
                </a>
            </div>
        </div>

        @if (! $databaseConnected)
            <div
                class="dashboard-reveal rounded-xl border border-red-200 bg-red-50 p-4"
                data-dashboard-reveal
                data-delay="50"
            >
                <div class="flex items-start gap-3">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-red-100 text-red-600">
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
                            Database tidak terhubung
                        </p>

                        <p class="mt-1 text-sm leading-6 text-red-600">
                            Periksa konfigurasi database sebelum menggunakan sistem.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article
                class="dashboard-card dashboard-reveal group rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-200"
                data-dashboard-reveal
                data-delay="80"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-3xl font-bold tracking-tight text-slate-800"
                            data-counter="{{ $totalMahasiswa }}"
                        >
                            0
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Total Mahasiswa
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Mahasiswa aktif dalam sistem
                        </p>
                    </div>

                    <div class="dashboard-icon grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="9" cy="8" r="4"></circle>
                            <path d="M3 21a6 6 0 0 1 12 0"></path>
                            <path d="M17 11v6"></path>
                            <path d="M14 14h6"></path>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                    <span class="text-xs text-slate-400">
                        Data mahasiswa aktif
                    </span>

                    <a
                        href="{{ route('admin.mahasiswa.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700"
                    >
                        Lihat data

                        <svg
                            class="dashboard-link-arrow h-3.5 w-3.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </div>
            </article>

            <article
                class="dashboard-card dashboard-reveal group rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-200"
                data-dashboard-reveal
                data-delay="140"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-3xl font-bold tracking-tight text-slate-800"
                            data-counter="{{ $completeDataIps }}"
                        >
                            0
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Data IPS Lengkap
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            {{ number_format($totalDataIps) }} data IPS tersimpan
                        </p>
                    </div>

                    <div class="dashboard-icon grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M5 3h14v18H5Z"></path>
                            <path d="M8 7h8"></path>
                            <path d="M8 11h8"></path>
                            <path d="M8 15h5"></path>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                    <span class="text-xs font-medium text-slate-400">
                        {{ $ipsCompletionRate }}% kelengkapan
                    </span>

                    <a
                        href="{{ route('admin.data-ips.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700"
                    >
                        Kelola IPS

                        <svg
                            class="dashboard-link-arrow h-3.5 w-3.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </div>
            </article>

            <article
                class="dashboard-card dashboard-reveal group rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-violet-200"
                data-dashboard-reveal
                data-delay="200"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-3xl font-bold tracking-tight text-slate-800"
                            data-counter="{{ $predictedStudents }}"
                        >
                            0
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Mahasiswa Diprediksi
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            {{ number_format($totalPrediksi) }} hasil tersimpan
                        </p>
                    </div>

                    <div class="dashboard-icon grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-violet-50 text-violet-600">
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
                            <path d="M22 19H2"></path>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                    <span class="text-xs font-medium text-slate-400">
                        {{ $predictionRate }}% telah diprediksi
                    </span>

                    <a
                        href="{{ route('admin.hasil-prediksi.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700"
                    >
                        Lihat hasil

                        <svg
                            class="dashboard-link-arrow h-3.5 w-3.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </div>
            </article>

            <article
                class="dashboard-card dashboard-reveal group rounded-xl border border-slate-200 bg-white p-5 shadow-sm {{ $modelReady ? 'hover:border-emerald-200' : 'hover:border-amber-200' }}"
                data-dashboard-reveal
                data-delay="260"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-2xl font-bold tracking-tight {{ $modelReady ? 'text-emerald-600' : 'text-amber-500' }}">
                            {{ $modelReady ? 'Siap' : 'Belum Siap' }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Status Model ANN
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            {{ number_format($totalModel) }} model tersimpan
                        </p>
                    </div>

                    <div class="dashboard-icon {{ $modelReady ? 'dashboard-pulse' : '' }} grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $modelReady ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 3v6"></path>
                            <path d="M12 15v6"></path>
                            <path d="M3 12h6"></path>
                            <path d="M15 12h6"></path>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                    <span class="text-xs font-medium text-slate-400">
                        @if ($activeModel)
                            Model #{{ $activeModel->id }}
                        @else
                            Tidak ada model aktif
                        @endif
                    </span>

                    <a
                        href="{{ route('admin.model-ann.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700"
                    >
                        Kelola model

                        <svg
                            class="dashboard-link-arrow h-3.5 w-3.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </div>
            </article>
        </section>

        <section class="grid gap-5 xl:grid-cols-[minmax(0,2fr)_minmax(340px,1fr)]">
            <article
                class="dashboard-card dashboard-reveal overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                data-dashboard-reveal
                data-delay="320"
            >
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Ringkasan Data
                        </h2>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Perbandingan data tersedia dengan target sistem.
                        </p>
                    </div>

                    <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                        <span class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                            Tersedia
                        </span>

                        <span class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                            Target
                        </span>
                    </div>
                </div>

                <div class="h-[350px] p-5">
                    <canvas
                        data-overview-chart
                        data-labels='@json($overviewLabels)'
                        data-values='@json($overviewValues)'
                        data-targets='@json($targetValues)'
                    ></canvas>
                </div>
            </article>

            <article
                class="dashboard-card dashboard-reveal overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                data-dashboard-reveal
                data-delay="380"
            >
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Status Sistem
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-slate-400">
                        Persentase kesiapan setiap komponen.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 border-b border-slate-100 px-5 py-4">
                    <div>
                        <p
                            class="text-xl font-bold text-slate-800"
                            data-counter="{{ $totalUsers }}"
                        >
                            0
                        </p>

                        <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            Pengguna
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xl font-bold text-slate-800"
                            data-counter="{{ $activeOperators }}"
                        >
                            0
                        </p>

                        <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            Operator
                        </p>
                    </div>

                    <div>
                        <p class="text-xl font-bold {{ $databaseConnected ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $databaseConnected ? 'Aktif' : 'Error' }}
                        </p>

                        <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            Database
                        </p>
                    </div>
                </div>

                <div class="h-[250px] p-5">
                    <canvas
                        data-readiness-chart
                        data-labels='@json($readinessLabels)'
                        data-values='@json($readinessValues)'
                    ></canvas>
                </div>
            </article>
        </section>

        <section class="grid gap-5 xl:grid-cols-2">
            <article
                class="dashboard-card dashboard-reveal overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                data-dashboard-reveal
                data-delay="440"
            >
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Progres Sistem
                        </h2>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Tahapan kesiapan proses prediksi IPK.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.prediksi-ipk.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700"
                    >
                        Mulai prediksi

                        <svg
                            class="dashboard-link-arrow h-3.5 w-3.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </div>

                <div class="space-y-6 p-5">
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-600">
                                Kelengkapan data IPS
                            </span>

                            <span class="text-sm font-bold text-slate-800">
                                {{ $ipsCompletionRate }}%
                            </span>
                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="dashboard-progress-bar h-full rounded-full bg-blue-500"
                                data-progress="{{ $ipsCompletionRate }}"
                            ></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-600">
                                Mahasiswa telah diprediksi
                            </span>

                            <span class="text-sm font-bold text-slate-800">
                                {{ $predictionRate }}%
                            </span>
                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="dashboard-progress-bar h-full rounded-full bg-emerald-500"
                                data-progress="{{ $predictionRate }}"
                            ></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-600">
                                Kesiapan model ANN
                            </span>

                            <span class="text-sm font-bold text-slate-800">
                                {{ $modelReady ? 100 : 0 }}%
                            </span>
                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="dashboard-progress-bar h-full rounded-full bg-amber-400"
                                data-progress="{{ $modelReady ? 100 : 0 }}"
                            ></div>
                        </div>
                    </div>

                    @if ($activeModel)
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-emerald-600">
                                        Model ANN Aktif
                                    </p>

                                    <p class="mt-1 text-base font-bold text-emerald-800">
                                        Model #{{ $activeModel->id }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-emerald-600">
                                        Arsitektur
                                    </p>

                                    <p class="mt-1 text-base font-bold text-emerald-800">
                                        {{ $activeModel->architectureLabel() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </article>

            <article
                class="dashboard-card dashboard-reveal overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                data-dashboard-reveal
                data-delay="500"
            >
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Pengguna Terbaru
                        </h2>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Akun yang terakhir ditambahkan ke sistem.
                        </p>
                    </div>

                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-blue-600">
                        {{ number_format($totalUsers) }} Akun
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] text-left">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                <th class="px-5 py-3.5">
                                    Pengguna
                                </th>

                                <th class="px-5 py-3.5">
                                    Peran
                                </th>

                                <th class="px-5 py-3.5 text-right">
                                    Ditambahkan
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentUsers as $recentUser)
                                <tr class="transition hover:bg-blue-50/40">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">
                                                {{ strtoupper(substr($recentUser->name ?? 'U', 0, 1)) }}
                                            </div>

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-bold text-slate-700">
                                                    {{ $recentUser->name }}
                                                </p>

                                                <p class="mt-0.5 truncate text-xs text-slate-400">
                                                    {{ $recentUser->email ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                            {{ ucwords(str_replace(['_', '-'], ' ', $recentUser->role)) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-3.5 text-right text-xs font-medium text-slate-500">
                                        {{ $recentUser->created_at?->diffForHumans() ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="3"
                                        class="px-5 py-12 text-center text-sm text-slate-400"
                                    >
                                        Belum ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section
            class="dashboard-reveal"
            data-dashboard-reveal
            data-delay="560"
        >
            <div class="mb-3">
                <h2 class="text-lg font-bold text-slate-800">
                    Akses Cepat
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Pilih proses yang ingin dijalankan.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <a
                    href="{{ route('admin.mahasiswa.create') }}"
                    class="dashboard-action group flex min-h-20 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-blue-300"
                >
                    <div class="dashboard-icon grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600">
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

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-700">
                            Tambah Mahasiswa
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Masukkan data baru
                        </p>
                    </div>

                    <svg
                        class="dashboard-link-arrow h-4 w-4 text-slate-300 group-hover:text-blue-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

                <a
                    href="{{ route('admin.data-ips.create') }}"
                    class="dashboard-action group flex min-h-20 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-emerald-300"
                >
                    <div class="dashboard-icon grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
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

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-700">
                            Input Data IPS
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Semester 1 sampai 5
                        </p>
                    </div>

                    <svg
                        class="dashboard-link-arrow h-4 w-4 text-slate-300 group-hover:text-emerald-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

                <a
                    href="{{ route('admin.model-ann.index') }}"
                    class="dashboard-action group flex min-h-20 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-violet-300"
                >
                    <div class="dashboard-icon grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-violet-50 text-violet-600">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 3v6"></path>
                            <path d="M12 15v6"></path>
                            <path d="M3 12h6"></path>
                            <path d="M15 12h6"></path>
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-700">
                            Training Model
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Kelola model ANN
                        </p>
                    </div>

                    <svg
                        class="dashboard-link-arrow h-4 w-4 text-slate-300 group-hover:text-violet-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

                <a
                    href="{{ route('admin.prediksi-ipk.index') }}"
                    class="dashboard-action group flex min-h-20 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-amber-300"
                >
                    <div class="dashboard-icon grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-amber-50 text-amber-600">
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
                        <p class="text-sm font-bold text-slate-700">
                            Jalankan Prediksi
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Prediksi IPK mahasiswa
                        </p>
                    </div>

                    <svg
                        class="dashboard-link-arrow h-4 w-4 text-slate-300 group-hover:text-amber-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealElements = document.querySelectorAll(
                '[data-dashboard-reveal]'
            );

            revealElements.forEach(element => {
                const delay = Number(element.dataset.delay || 0);

                window.setTimeout(() => {
                    element.classList.add('is-visible');
                }, delay);
            });

            const counters = document.querySelectorAll(
                '[data-counter]'
            );

            counters.forEach(counter => {
                const target = Number(counter.dataset.counter || 0);
                const duration = 800;
                const startTime = performance.now();

                const updateCounter = currentTime => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(
                        elapsed / duration,
                        1
                    );

                    const easedProgress =
                        1 - Math.pow(1 - progress, 3);

                    const currentValue = Math.round(
                        target * easedProgress
                    );

                    counter.textContent =
                        new Intl.NumberFormat('id-ID')
                            .format(currentValue);

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    }
                };

                requestAnimationFrame(updateCounter);
            });

            const progressBars = document.querySelectorAll(
                '[data-progress]'
            );

            window.setTimeout(() => {
                progressBars.forEach(progressBar => {
                    const value = Math.max(
                        0,
                        Math.min(
                            100,
                            Number(
                                progressBar.dataset.progress || 0
                            )
                        )
                    );

                    progressBar.style.width = `${value}%`;
                });
            }, 400);
        });
    </script>
@endpush