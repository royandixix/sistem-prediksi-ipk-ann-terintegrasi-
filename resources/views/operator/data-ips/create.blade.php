@extends('layouts.operator')

@section('title', 'Input Data IPS')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                    <span>Operator</span>
                    <span>/</span>
                    <span>Data IPS</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                    Input Data IPS
                </h1>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Masukkan nilai IPS Semester 1 sampai Semester 5 mahasiswa.
                </p>
            </div>

            <a
                href="{{ route('operator.dashboard') }}"
                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
            >
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="m15 18-6-6 6-6"></path>
                </svg>

                Kembali ke Dashboard
            </a>
        </div>

        <section class="grid gap-4 sm:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-3xl font-bold text-slate-800">
                            {{ number_format($totalMahasiswa) }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Total Mahasiswa
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Mahasiswa aktif
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
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-3xl font-bold text-slate-800">
                            {{ number_format($totalDataIps) }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Data IPS Tersimpan
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Sudah selesai diinput
                        </p>
                    </div>

                    <div class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
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
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-3xl font-bold text-slate-800">
                            {{ number_format($remainingStudents) }}
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            Belum Memiliki IPS
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Menunggu proses input
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
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-5 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.6fr)]">
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Form Data IPS Mahasiswa
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-slate-400">
                        Gunakan angka antara 0.00 sampai 4.00 pada setiap semester.
                    </p>
                </div>

                <form
                    action="{{ route('operator.data-ips.store') }}"
                    method="POST"
                    class="p-5"
                >
                    @csrf

                    <div>
                        <label
                            for="mahasiswa_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Mahasiswa
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="mahasiswa_id"
                            name="mahasiswa_id"
                            required
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                            <option value="">
                                Pilih mahasiswa
                            </option>

                            @foreach ($mahasiswas as $mahasiswa)
                                <option
                                    value="{{ $mahasiswa->id }}"
                                    @selected(
                                        (string) old(
                                            'mahasiswa_id',
                                            $selectedMahasiswa?->id
                                        ) === (string) $mahasiswa->id
                                    )
                                >
                                    {{ $mahasiswa->nim }} — {{ $mahasiswa->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('mahasiswa_id')
                            <p class="mt-2 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        @if ($mahasiswas->isEmpty())
                            <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3">
                                <p class="text-xs font-semibold text-amber-700">
                                    Seluruh mahasiswa aktif sudah memiliki Data IPS.
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @for ($semester = 1; $semester <= 5; $semester++)
                            <div>
                                <label
                                    for="ips_{{ $semester }}"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    IPS Semester {{ $semester }}
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="ips_{{ $semester }}"
                                    name="ips_{{ $semester }}"
                                    type="number"
                                    min="0"
                                    max="4"
                                    step="0.01"
                                    value="{{ old('ips_' . $semester) }}"
                                    placeholder="0.00"
                                    required
                                    class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 transition placeholder:text-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                >

                                @error('ips_' . $semester)
                                    <p class="mt-2 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        @endfor

                        <div>
                            <label
                                for="ipk_akhir_aktual"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                IPK Akhir Aktual
                            </label>

                            <input
                                id="ipk_akhir_aktual"
                                name="ipk_akhir_aktual"
                                type="number"
                                min="0"
                                max="4"
                                step="0.001"
                                value="{{ old('ipk_akhir_aktual') }}"
                                placeholder="Opsional"
                                class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 transition placeholder:text-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            >

                            @error('ipk_akhir_aktual')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <div class="flex items-start gap-3">
                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 11v5"></path>
                                <path d="M12 8h.01"></path>
                            </svg>

                            <p class="text-xs leading-5 text-blue-700">
                                Periksa kembali nilai IPS sebelum disimpan. Data yang sudah digunakan dalam training model atau prediksi akan dikunci oleh sistem.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                        <button
                            type="reset"
                            class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        >
                            Reset Form
                        </button>

                        <button
                            type="submit"
                            @disabled($mahasiswas->isEmpty())
                            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="m5 12 4 4L19 6"></path>
                            </svg>

                            Simpan Data IPS
                        </button>
                    </div>
                </form>
            </article>

            <aside class="space-y-5">
                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-slate-800">
                            Panduan Input
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Tahapan pengisian Data IPS.
                        </p>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="flex gap-3">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-blue-50 text-xs font-bold text-blue-600">
                                1
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-700">
                                    Pilih mahasiswa
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-400">
                                    Hanya mahasiswa yang belum memiliki Data IPS yang ditampilkan.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-emerald-50 text-xs font-bold text-emerald-600">
                                2
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-700">
                                    Masukkan nilai IPS
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-400">
                                    Isi IPS Semester 1 sampai Semester 5 dengan benar.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-violet-50 text-xs font-bold text-violet-600">
                                3
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-700">
                                    Simpan data
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-400">
                                    Data akan tersedia sebagai kandidat proses prediksi.
                                </p>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-slate-800">
                            Input Terbaru
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Lima Data IPS terakhir.
                        </p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentDataIps as $dataIps)
                            <div class="px-5 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-700">
                                            {{ $dataIps->mahasiswa?->nama ?? '-' }}
                                        </p>

                                        <p class="mt-1 truncate text-xs text-slate-400">
                                            {{ $dataIps->mahasiswa?->nim ?? '-' }}
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-600">
                                        Lengkap
                                    </span>
                                </div>

                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <span class="text-slate-400">
                                        Rata-rata IPS
                                    </span>

                                    <span class="font-bold text-slate-600">
                                        {{ number_format(
                                            (
                                                (float) $dataIps->ips_1
                                                + (float) $dataIps->ips_2
                                                + (float) $dataIps->ips_3
                                                + (float) $dataIps->ips_4
                                                + (float) $dataIps->ips_5
                                            ) / 5,
                                            3
                                        ) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center">
                                <p class="text-sm font-semibold text-slate-500">
                                    Belum ada Data IPS
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Data terbaru akan tampil di sini.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </article>
            </aside>
        </section>
    </div>
@endsection