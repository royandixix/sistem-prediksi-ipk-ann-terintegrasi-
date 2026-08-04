@extends('layouts.admin')

@section('title', 'Data IPS')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                    Data IPS
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Kelola IPS Semester 1–5 mahasiswa angkatan 2023.
                </p>
            </div>

            <a href="{{ route('admin.data-ips.create') }}"
                class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>

                Input Data IPS
            </a>
        </div>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @php
                $cards = [
                    [
                        'label' => 'Total Data IPS',
                        'value' => $statistics['total'],
                        'description' => 'Data mahasiswa tersimpan',
                        'color' => 'text-blue-600',
                        'background' => 'bg-blue-50',
                    ],
                    [
                        'label' => 'Data Lengkap',
                        'value' => $statistics['complete'],
                        'description' => 'IPS Semester 1–5 lengkap',
                        'color' => 'text-emerald-600',
                        'background' => 'bg-emerald-50',
                    ],
                    [
                        'label' => 'Memiliki IPK Aktual',
                        'value' => $statistics['with_actual'],
                        'description' => 'Siap menjadi target training',
                        'color' => 'text-violet-600',
                        'background' => 'bg-violet-50',
                    ],
                    [
                        'label' => 'Rata-rata IPS',
                        'value' => number_format($statistics['average_ips'], 3),
                        'description' => 'Rata-rata seluruh data',
                        'color' => 'text-cyan-600',
                        'background' => 'bg-cyan-50',
                    ],
                    [
                        'label' => 'Data Estimasi',
                        'value' => $statistics['estimated'],
                        'description' => 'IPS awal hasil preprocessing',
                        'color' => 'text-amber-600',
                        'background' => 'bg-amber-50',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-medium text-slate-400">
                                {{ $card['label'] }}
                            </p>

                            <p class="mt-3 text-3xl font-bold {{ $card['color'] }}">
                                {{ $card['value'] }}
                            </p>

                            <p class="mt-2 text-[11px] text-slate-400">
                                {{ $card['description'] }}
                            </p>
                        </div>

                        <div
                            class="grid h-11 w-11 place-items-center rounded-xl {{ $card['background'] }} {{ $card['color'] }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M4 19V9"></path>
                                <path d="M10 19V5"></path>
                                <path d="M16 19v-7"></path>
                                <path d="M22 19V3"></path>
                                <path d="M2 19h22"></path>
                            </svg>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Daftar Data IPS
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Cari dan kelola nilai IPS mahasiswa.
                        </p>
                    </div>

                    <p class="text-xs text-slate-400">
                        Total {{ number_format($dataIpsCollection->total()) }} data
                    </p>
                </div>
            </div>

            <div class="border-b border-slate-100 bg-slate-50/70 p-4">
                <form action="{{ route('admin.data-ips.index') }}" method="GET"
                    class="grid gap-3 lg:grid-cols-[minmax(260px,1fr)_220px_120px_auto]">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>

                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari NIM atau nama mahasiswa..."
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                    </div>

                    <select name="status"
                        class="h-12 rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-600 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                        <option value="">Semua Data</option>
                        <option value="complete" @selected(request('status') === 'complete')>
                            Data Lengkap
                        </option>
                        <option value="with_actual" @selected(request('status') === 'with_actual')>
                            Memiliki IPK Aktual
                        </option>
                        <option value="without_actual" @selected(request('status') === 'without_actual')>
                            Tanpa IPK Aktual
                        </option>
                        <option value="estimated" @selected(request('status') === 'estimated')>
                            Data Estimasi
                        </option>
                        <option value="original" @selected(request('status') === 'original')>
                            Data Asli Manual
                        </option>
                    </select>

                    <select name="per_page"
                        class="h-12 rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-600 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                        @foreach ([10, 25, 50] as $size)
                            <option value="{{ $size }}" @selected($perPage === $size)>
                                {{ $size }} data
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex h-12 flex-1 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Filter
                        </button>

                        <a href="{{ route('admin.data-ips.index') }}"
                            class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-500 transition hover:bg-slate-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="w-full min-w-[1150px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-white text-[10px] uppercase tracking-wider text-slate-400">
                            <th class="px-5 py-4 font-semibold">Mahasiswa</th>
                            <th class="px-4 py-4 text-center font-semibold">IPS 1</th>
                            <th class="px-4 py-4 text-center font-semibold">IPS 2</th>
                            <th class="px-4 py-4 text-center font-semibold">IPS 3</th>
                            <th class="px-4 py-4 text-center font-semibold">IPS 4</th>
                            <th class="px-4 py-4 text-center font-semibold">IPS 5</th>
                            <th class="px-4 py-4 text-center font-semibold">Rata-rata</th>
                            <th class="px-4 py-4 text-center font-semibold">IPK Aktual</th>
                            <th class="px-5 py-4 font-semibold">Sumber</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($dataIpsCollection as $dataIps)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                            {{ strtoupper(substr($dataIps->mahasiswa->nama, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="max-w-52 truncate text-sm font-semibold text-slate-700">
                                                {{ $dataIps->mahasiswa->nama }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $dataIps->mahasiswa->nim }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                @foreach ([$dataIps->ips_1, $dataIps->ips_2, $dataIps->ips_3, $dataIps->ips_4, $dataIps->ips_5] as $ips)
                                    <td class="px-4 py-4 text-center text-sm font-medium text-slate-600">
                                        {{ number_format((float) $ips, 2) }}
                                    </td>
                                @endforeach

                                <td class="px-4 py-4 text-center">
                                    <span class="text-sm font-bold text-blue-600">
                                        {{ number_format($dataIps->averageIps(), 3) }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    @if ($dataIps->ipk_akhir_aktual !== null)
                                        <span class="text-sm font-bold text-violet-600">
                                            {{ number_format((float) $dataIps->ipk_akhir_aktual, 3) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">
                                            Belum ada
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if($dataIps->is_estimated)
                                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Estimasi
                                        </span>
                                    @elseif($dataIps->data_source)
                                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                            {{ $dataIps->data_source }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Input manual</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Lengkap
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('admin.data-ips.show', $dataIps) }}" title="Detail"
                                            class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.data-ips.edit', $dataIps) }}" title="Edit"
                                            class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.data-ips.destroy', $dataIps) }}" method="POST"
                                            data-delete-data-ips data-name="{{ $dataIps->mahasiswa->nama }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" title="Hapus"
                                                class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4h8v2"></path>
                                                    <path d="M19 6l-1 15H6L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-20 text-center">
                                    <p class="text-base font-semibold text-slate-600">
                                        Belum ada Data IPS
                                    </p>

                                    <p class="mt-2 text-sm text-slate-400">
                                        Input IPS Semester 1–5 untuk memulai proses prediksi.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-4 p-4 lg:hidden">
                @forelse($dataIpsCollection as $dataIps)
                    <article class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                {{ strtoupper(substr($dataIps->mahasiswa->nama, 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-700">
                                    {{ $dataIps->mahasiswa->nama }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    NIM {{ $dataIps->mahasiswa->nim }}
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-1">
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-600">
                                    Lengkap
                                </span>
                                @if($dataIps->is_estimated)
                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-semibold text-amber-700">Estimasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-5 gap-2">
                            @foreach ([$dataIps->ips_1, $dataIps->ips_2, $dataIps->ips_3, $dataIps->ips_4, $dataIps->ips_5] as $index => $ips)
                                <div class="rounded-lg bg-slate-50 p-2 text-center">
                                    <p class="text-[9px] text-slate-400">
                                        IPS {{ $index + 1 }}
                                    </p>

                                    <p class="mt-1 text-xs font-bold text-slate-700">
                                        {{ number_format((float) $ips, 2) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-3 rounded-lg bg-blue-50 p-3">
                            <div>
                                <p class="text-[9px] uppercase text-blue-400">
                                    Rata-rata
                                </p>

                                <p class="mt-1 text-sm font-bold text-blue-700">
                                    {{ number_format($dataIps->averageIps(), 3) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[9px] uppercase text-blue-400">
                                    IPK Aktual
                                </p>

                                <p class="mt-1 text-sm font-bold text-blue-700">
                                    {{ $dataIps->ipk_akhir_aktual !== null ? number_format((float) $dataIps->ipk_akhir_aktual, 3) : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <a href="{{ route('admin.data-ips.show', $dataIps) }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-200 text-xs font-semibold text-slate-600">
                                Detail
                            </a>

                            <a href="{{ route('admin.data-ips.edit', $dataIps) }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-200 text-xs font-semibold text-slate-600">
                                Edit
                            </a>

                            <form action="{{ route('admin.data-ips.destroy', $dataIps) }}" method="POST"
                                data-delete-data-ips data-name="{{ $dataIps->mahasiswa->nama }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="h-10 w-full rounded-lg border border-red-200 text-xs font-semibold text-red-600">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="py-16 text-center">
                        <p class="text-sm font-semibold text-slate-600">
                            Belum ada Data IPS
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Input nilai mahasiswa untuk memulai.
                        </p>
                    </div>
                @endforelse
            </div>

            @if ($dataIpsCollection->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $dataIpsCollection->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-delete-data-ips]').forEach(form => {
                form.addEventListener('submit', async event => {
                    if (form.dataset.confirmed === 'true') return;

                    event.preventDefault();

                    const result = await window.Swal.fire({
                        icon: 'warning',
                        title: 'Hapus Data IPS?',
                        html: `Data IPS <strong>${form.dataset.name}</strong> akan dihapus.`,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        reverseButtons: true
                    });

                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
