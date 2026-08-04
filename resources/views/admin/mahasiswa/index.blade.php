@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-800">
                    Data Mahasiswa
                </h1>
                <p class="mt-1 text-xs text-slate-400">
                    Kelola data mahasiswa Teknik Informatika angkatan 2023.
                </p>
            </div>

            <a href="{{ route('admin.mahasiswa.create') }}"
                class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                Tambah Mahasiswa
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $cards = [
                    [
                        'label' => 'Total Mahasiswa',
                        'value' => $statistics['total'],
                        'description' => 'Data mahasiswa tersimpan',
                        'color' => 'text-blue-600',
                        'background' => 'bg-blue-50',
                    ],
                    [
                        'label' => 'Mahasiswa Aktif',
                        'value' => $statistics['aktif'],
                        'description' => 'Mahasiswa berstatus aktif',
                        'color' => 'text-emerald-600',
                        'background' => 'bg-emerald-50',
                    ],
                    [
                        'label' => 'IPS Lengkap',
                        'value' => $statistics['ips_lengkap'],
                        'description' => 'IPS Semester 1–5 lengkap',
                        'color' => 'text-cyan-600',
                        'background' => 'bg-cyan-50',
                    ],
                    [
                        'label' => 'Sudah Diprediksi',
                        'value' => $statistics['sudah_diprediksi'],
                        'description' => 'Memiliki hasil prediksi',
                        'color' => 'text-violet-600',
                        'background' => 'bg-violet-50',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-medium text-slate-400">
                                {{ $card['label'] }}
                            </p>
                            <p class="mt-2 text-2xl font-semibold {{ $card['color'] }}">
                                {{ number_format($card['value']) }}
                            </p>
                            <p class="mt-2 text-[10px] text-slate-400">
                                {{ $card['description'] }}
                            </p>
                        </div>

                        <div
                            class="grid h-10 w-10 place-items-center rounded-lg {{ $card['background'] }} {{ $card['color'] }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <circle cx="9" cy="8" r="4"></circle>
                                <path d="M2.5 21a6.5 6.5 0 0 1 13 0"></path>
                                <path d="M17 11a4 4 0 0 1 4 4v6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-700">
                            Daftar Mahasiswa
                        </h2>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Cari dan kelola data mahasiswa.
                        </p>
                    </div>

                    <p class="text-[10px] text-slate-400">
                        Total {{ number_format($mahasiswas->total()) }} data
                    </p>
                </div>
            </div>

            <div class="border-b border-slate-100 bg-slate-50/70 p-4">
                <form action="{{ route('admin.mahasiswa.index') }}" method="GET"
                    class="grid gap-3 md:grid-cols-2 xl:grid-cols-[minmax(250px,1fr)_160px_160px_100px_auto]">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>

                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari NIM atau nama..."
                            class="h-10 w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 text-xs text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                    </div>

                    <select name="angkatan"
                        class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-600 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                        <option value="">Semua Angkatan</option>
                        @foreach ($angkatans as $angkatan)
                            <option value="{{ $angkatan }}" @selected((string) request('angkatan') === (string) $angkatan)>
                                {{ $angkatan }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status"
                        class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-600 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                        <option value="">Semua Status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                    </select>

                    <select name="per_page"
                        class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-600 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                        @foreach ([10, 25, 50] as $size)
                            <option value="{{ $size }}" @selected($perPage === $size)>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex h-10 flex-1 items-center justify-center rounded-lg bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700">
                            Fi1lter
                        </button>

                        <a href="{{ route('admin.mahasiswa.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-500 transition hover:bg-slate-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[1000px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-white text-[9px] uppercase tracking-wider text-slate-400">
                            <th class="px-5 py-3 font-semibold">No.</th>
                            <th class="px-5 py-3 font-semibold">Mahasiswa</th>
                            <th class="px-5 py-3 font-semibold">Akademik</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold">Data IPS</th>
                            <th class="px-5 py-3 font-semibold">Prediksi</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($mahasiswas as $mahasiswa)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 text-xs text-slate-400">
                                    {{ $mahasiswas->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">
                                            {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('admin.mahasiswa.show', $mahasiswa) }}"
                                                class="block max-w-52 truncate text-xs font-semibold text-slate-700 hover:text-blue-600">
                                                {{ $mahasiswa->nama }}
                                            </a>
                                            <p class="mt-1 text-[10px] text-slate-400">
                                                {{ $mahasiswa->nim }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-xs font-medium text-slate-600">
                                        {{ $mahasiswa->angkatan }}
                                    </p>
                                    <p class="mt-1 text-[10px] text-slate-400">
                                        {{ $mahasiswa->program_studi }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold
{{ $mahasiswa->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ ucfirst($mahasiswa->status) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($mahasiswa->dataIps?->is_complete)
                                        <span class="text-[10px] font-semibold text-emerald-600">
                                            Lengkap
                                        </span>
                                    @elseif($mahasiswa->dataIps)
                                        <span class="text-[10px] font-semibold text-amber-600">
                                            Belum lengkap
                                        </span>
                                    @else
                                        <a href="{{ route('admin.data-ips.create', ['mahasiswa' => $mahasiswa->nim]) }}"
                                            class="text-[10px] font-semibold text-blue-600">
                                            Input IPS
                                        </a>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if ($mahasiswa->prediksiTerbaru)
                                        <p class="text-xs font-semibold text-blue-600">
                                            {{ number_format((float) $mahasiswa->prediksiTerbaru->ipk_prediksi, 3) }}
                                        </p>
                                        <p class="mt-1 text-[9px] text-slate-400">
                                            {{ $mahasiswa->prediksi_ipks_count }} prediksi
                                        </p>
                                    @else
                                        <span class="text-[10px] text-slate-400">
                                            Belum diprediksi
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('admin.mahasiswa.show', $mahasiswa) }}"
                                            class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"
                                            title="Detail">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}"
                                            class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600"
                                            title="Edit">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST"
                                            data-delete-mahasiswa data-name="{{ $mahasiswa->nama }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                                                title="Hapus">
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
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <p class="text-sm font-semibold text-slate-600">
                                        Belum ada data mahasiswa
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Tambahkan data mahasiswa untuk memulai.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 p-4 md:hidden">
                @forelse($mahasiswas as $mahasiswa)
                    <article class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-700">
                                    {{ $mahasiswa->nama }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    NIM {{ $mahasiswa->nim }}
                                </p>
                            </div>

                            <span
                                class="rounded-full px-2.5 py-1 text-[9px] font-semibold
{{ $mahasiswa->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($mahasiswa->status) }}
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 rounded-lg bg-slate-50 p-3">
                            <div>
                                <p class="text-[9px] uppercase text-slate-400">Angkatan</p>
                                <p class="mt-1 text-xs font-semibold text-slate-700">
                                    {{ $mahasiswa->angkatan }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[9px] uppercase text-slate-400">Data IPS</p>
                                <p class="mt-1 text-xs font-semibold text-slate-700">
                                    {{ $mahasiswa->dataIps?->is_complete ? 'Lengkap' : 'Belum lengkap' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <a href="{{ route('admin.mahasiswa.show', $mahasiswa) }}"
                                class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 text-xs font-semibold text-slate-600">
                                Detail
                            </a>

                            <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}"
                                class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 text-xs font-semibold text-slate-600">
                                Edit
                            </a>

                            <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST"
                                data-delete-mahasiswa data-name="{{ $mahasiswa->nama }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="h-9 w-full rounded-lg border border-red-200 text-xs font-semibold text-red-600">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="py-16 text-center">
                        <p class="text-sm font-semibold text-slate-600">
                            Belum ada data mahasiswa
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            Tambahkan mahasiswa untuk memulai.
                        </p>
                    </div>
                @endforelse
            </div>

            @if ($mahasiswas->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $mahasiswas->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-delete-mahasiswa]').forEach(form => {
                form.addEventListener('submit', async event => {
                    if (form.dataset.confirmed === 'true') return;
                    event.preventDefault();

                    const result = await window.Swal.fire({
                        icon: 'warning',
                        title: 'Hapus Mahasiswa?',
                        html: `Data <strong>${form.dataset.name}</strong> akan dihapus.`,
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
