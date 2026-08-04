@extends('layouts.admin')

@section('title', 'Detail Mahasiswa')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-[11px] text-slate-400">
                <a href="{{ route('admin.dashboard') }}" class="transition hover:text-blue-600">
                    Dashboard
                </a>
                <span>/</span>
                <a href="{{ route('admin.mahasiswa.index') }}" class="transition hover:text-blue-600">
                    Data Mahasiswa
                </a>
                <span>/</span>
                <span class="text-slate-600">{{ $mahasiswa->nim }}</span>
            </div>

            <h1 class="mt-3 text-xl font-semibold text-slate-800">
                Detail Mahasiswa
            </h1>

            <p class="mt-1 text-xs text-slate-400">
                Informasi identitas, data IPS, dan riwayat prediksi mahasiswa.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.mahasiswa.index') }}"
                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                Kembali
            </a>

            <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}"
                class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700">
                Edit Data
            </a>
        </div>
    </div>

    <div class="grid gap-5 xl:grid-cols-[1fr_1.6fr]">
        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div
                        class="grid h-14 w-14 place-items-center rounded-full bg-blue-50 text-lg font-semibold text-blue-600">
                        {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <h2 class="truncate text-base font-semibold text-slate-800">
                            {{ $mahasiswa->nama }}
                        </h2>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $mahasiswa->nim }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-slate-100 px-5">
                <div class="flex items-center justify-between gap-4 py-4">
                    <span class="text-xs text-slate-400">Angkatan</span>
                    <span class="text-xs font-semibold text-slate-700">
                        {{ $mahasiswa->angkatan }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 py-4">
                    <span class="text-xs text-slate-400">Program Studi</span>
                    <span class="text-xs font-semibold text-slate-700">
                        {{ $mahasiswa->program_studi }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 py-4">
                    <span class="text-xs text-slate-400">Status</span>
                    @if ($mahasiswa->status === 'aktif')
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-600">
                            Aktif
                        </span>
                    @else
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-500">
                            Nonaktif
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between gap-4 py-4">
                    <span class="text-xs text-slate-400">Ditambahkan oleh</span>
                    <span class="text-xs font-semibold text-slate-700">
                        {{ $mahasiswa->createdBy?->name ?? '-' }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 py-4">
                    <span class="text-xs text-slate-400">Tanggal dibuat</span>
                    <span class="text-xs font-semibold text-slate-700">
                        {{ $mahasiswa->created_at->translatedFormat('d F Y H:i') }}
                    </span>
                </div>
            </div>
        </section>

        <div class="space-y-5">
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-700">
                            Data IPS Semester 1–5
                        </h2>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Data input yang digunakan untuk proses ANN.
                        </p>
                    </div>

                    @if (!$mahasiswa->dataIps)
                        <a href="{{ route('admin.data-ips.create', ['mahasiswa' => $mahasiswa->nim]) }}"
                            class="text-xs font-semibold text-blue-600 transition hover:text-blue-700">
                            Input IPS
                        </a>
                    @endif
                </div>

                @if ($mahasiswa->dataIps)
                    <div class="grid grid-cols-2 gap-px bg-slate-100 sm:grid-cols-5">
                        @foreach ([
            'IPS 1' => $mahasiswa->dataIps->ips_1,
            'IPS 2' => $mahasiswa->dataIps->ips_2,
            'IPS 3' => $mahasiswa->dataIps->ips_3,
            'IPS 4' => $mahasiswa->dataIps->ips_4,
            'IPS 5' => $mahasiswa->dataIps->ips_5,
        ] as $label => $value)
                            <div class="bg-white p-5 text-center">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400">
                                    {{ $label }}
                                </p>
                                <p class="mt-2 text-lg font-semibold text-slate-700">
                                    {{ number_format((float) $value, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div
                        class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-500">
                            Rata-rata IPS:
                            <strong class="text-slate-700">
                                {{ number_format($mahasiswa->dataIps->averageIps(), 3) }}
                            </strong>
                        </p>

                        @if ($mahasiswa->dataIps->ipk_akhir_aktual !== null)
                            <p class="text-xs text-slate-500">
                                IPK aktual:
                                <strong class="text-slate-700">
                                    {{ number_format((float) $mahasiswa->dataIps->ipk_akhir_aktual, 3) }}
                                </strong>
                            </p>
                        @endif
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-sm font-semibold text-slate-600">
                            Data IPS belum tersedia
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            Masukkan IPS Semester 1 sampai Semester 5 terlebih dahulu.
                        </p>
                    </div>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-700">
                            Riwayat Prediksi IPK
                        </h2>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Seluruh hasil prediksi mahasiswa.
                        </p>
                    </div>

                    @if ($mahasiswa->dataIps)
                        <a href="{{ route('admin.prediksi-ipk.index', ['mahasiswa' => $mahasiswa->nim]) }}"
                            class="text-xs font-semibold text-blue-600 transition hover:text-blue-700">
                            Jalankan Prediksi
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] text-left">
                        <thead class="bg-slate-50">
                            <tr class="text-[9px] uppercase tracking-wider text-slate-400">
                                <th class="px-5 py-3 font-semibold">Tanggal</th>
                                <th class="px-5 py-3 font-semibold">Model ANN</th>
                                <th class="px-5 py-3 font-semibold">IPK Prediksi</th>
                                <th class="px-5 py-3 font-semibold">IPK Aktual</th>
                                <th class="px-5 py-3 font-semibold">MAE Individual</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa->prediksiIpks as $prediksi)
                                <tr>
                                    <td class="px-5 py-3 text-xs text-slate-500">
                                        {{ $prediksi->predicted_at->translatedFormat('d M Y H:i') }}
                                    </td>
                                    <td class="px-5 py-3 text-xs font-medium text-slate-600">
                                        {{ $prediksi->modelAnn?->nama_model ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-xs font-semibold text-blue-600">
                                        {{ number_format((float) $prediksi->ipk_prediksi, 3) }}
                                    </td>
                                    <td class="px-5 py-3 text-xs text-slate-500">
                                        {{ $prediksi->ipk_aktual !== null ? number_format((float) $prediksi->ipk_aktual, 3) : '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-xs text-slate-500">
                                        {{ $prediksi->absolute_error !== null ? number_format((float) $prediksi->absolute_error, 6) : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-xs text-slate-400">
                                        Mahasiswa belum memiliki hasil prediksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
