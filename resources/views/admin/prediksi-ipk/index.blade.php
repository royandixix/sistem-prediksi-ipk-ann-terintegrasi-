@extends('layouts.admin')

@section('title', 'Prediksi IPK')

@section('content')
    @php
        $modelReady = $activeModel?->isReadyForPrediction() ?? false;
    @endphp

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
                    <span>Prediksi IPK</span>
                </div>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
                    Prediksi IPK Mahasiswa
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Prediksi IPK akhir berdasarkan IPS Semester 1–5 menggunakan
                    model Artificial Neural Network aktif.
                </p>
            </div>

            @if ($modelReady)
                <div class="inline-flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <span class="relative flex h-3 w-3">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-50"></span>
                        <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                    </span>

                    <div>
                        <p class="text-xs font-semibold text-emerald-700">
                            Sistem Prediksi Siap
                        </p>

                        <p class="mt-0.5 text-[11px] text-emerald-600">
                            Model #{{ $activeModel->id }}
                            · {{ $activeModel->architectureLabel() }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Pesan berhasil --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="m8 12 3 3 5-6"></path>
                </svg>

                <div>
                    <p class="text-sm font-semibold text-emerald-700">
                        Prediksi berhasil
                    </p>

                    <p class="mt-1 text-xs text-emerald-600">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Pesan gagal --}}
        @if (session('error'))
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
                        Prediksi gagal
                    </p>

                    <p class="mt-1 text-xs text-red-600">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Validasi model --}}
        @if ($errors->has('model'))
            <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-amber-500"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 3 2.5 20h19Z"></path>
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                </svg>

                <p class="text-sm font-medium text-amber-700">
                    {{ $errors->first('model') }}
                </p>
            </div>
        @endif

        {{-- Statistik --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Model ANN
                        </p>

                        <p
                            class="mt-3 text-xl font-bold
                            {{ $modelReady ? 'text-emerald-600' : 'text-amber-600' }}"
                        >
                            {{ $modelReady ? 'Aktif dan Siap' : 'Belum Tersedia' }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            @if ($modelReady)
                                Model #{{ $activeModel->id }}
                                · {{ $activeModel->architectureLabel() }}
                            @else
                                Training model harus dijalankan
                            @endif
                        </p>
                    </div>

                    <div
                        class="grid h-11 w-11 place-items-center rounded-xl
                        {{ $modelReady
                            ? 'bg-emerald-50 text-emerald-600'
                            : 'bg-amber-50 text-amber-600' }}"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="12" r="3"></circle>
                            <circle cx="5" cy="7" r="2"></circle>
                            <circle cx="19" cy="7" r="2"></circle>
                            <circle cx="5" cy="17" r="2"></circle>
                            <circle cx="19" cy="17" r="2"></circle>
                            <path d="m7 8 3 3"></path>
                            <path d="m17 8-3 3"></path>
                            <path d="m7 16 3-3"></path>
                            <path d="m17 16-3-3"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Kandidat Prediksi
                        </p>

                        <p class="mt-3 text-3xl font-bold text-blue-600">
                            {{ number_format($totalCandidates) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Memiliki IPS Semester 1–5 lengkap
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
                            Sudah Diprediksi
                        </p>

                        <p class="mt-3 text-3xl font-bold text-violet-600">
                            {{ number_format($predictedStudents) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Mahasiswa telah memiliki hasil
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
                            <path d="m5 12 4 4L19 6"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Menunggu Prediksi
                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-600">
                            {{ number_format($pendingStudents) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Belum memiliki hasil prediksi
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

        {{-- Form dan model --}}
        <div class="grid gap-5 xl:grid-cols-[minmax(0,1.3fr)_minmax(340px,.7fr)]">
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                Form Prediksi IPK
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Pilih mahasiswa untuk menampilkan Data IPS dan
                                menjalankan proses prediksi.
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
                </div>

                <form
                    id="prediction-form"
                    action="{{ route('admin.prediksi-ipk.store') }}"
                    method="POST"
                    class="p-6"
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
                            @disabled($candidates->isEmpty())
                            class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400
                            @error('mahasiswa_id')
                                border-red-300 focus:border-red-400 focus:ring-red-100
                            @else
                                border-slate-200 focus:border-blue-400 focus:ring-blue-50
                            @enderror"
                        >
                            <option value="">
                                Pilih mahasiswa
                            </option>

                            @foreach ($candidates as $candidate)
                                @php
                                    $data = $candidate->dataIps;
                                @endphp

                                <option
                                    value="{{ $candidate->id }}"
                                    data-nim="{{ $candidate->nim }}"
                                    data-nama="{{ $candidate->nama }}"
                                    data-angkatan="{{ $candidate->angkatan }}"
                                    data-program-studi="{{ $candidate->program_studi }}"
                                    data-ips1="{{ $data?->ips_1 }}"
                                    data-ips2="{{ $data?->ips_2 }}"
                                    data-ips3="{{ $data?->ips_3 }}"
                                    data-ips4="{{ $data?->ips_4 }}"
                                    data-ips5="{{ $data?->ips_5 }}"
                                    data-actual="{{ $data?->ipk_akhir_aktual }}"
                                    @selected(
                                        (string) old('mahasiswa_id')
                                        === (string) $candidate->id
                                    )
                                >
                                    {{ $candidate->nim }}
                                    — {{ $candidate->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('mahasiswa_id')
                            <p class="mt-2 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        @if ($candidates->isEmpty())
                            <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">
                                <p class="text-xs leading-5 text-amber-700">
                                    Belum ada mahasiswa dengan IPS Semester 1–5
                                    lengkap yang dapat diproses.
                                </p>

                                <a
                                    href="{{ route('admin.data-ips.index') }}"
                                    class="mt-2 inline-flex text-xs font-semibold text-amber-700 underline underline-offset-4"
                                >
                                    Kelola Data IPS
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Preview mahasiswa --}}
                    <div
                        id="student-preview"
                        class="mt-6 hidden rounded-xl border border-slate-200 bg-slate-50 p-5"
                    >
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <div class="grid h-12 w-12 place-items-center rounded-full bg-blue-100 text-base font-bold text-blue-600">
                                    <span id="student-initial">M</span>
                                </div>

                                <div>
                                    <p
                                        id="student-name"
                                        class="text-sm font-semibold text-slate-800"
                                    >
                                        -
                                    </p>

                                    <p
                                        id="student-nim"
                                        class="mt-1 text-xs text-slate-400"
                                    >
                                        -
                                    </p>
                                </div>
                            </div>

                            <div class="text-left sm:text-right">
                                <p
                                    id="student-program"
                                    class="text-xs font-semibold text-slate-600"
                                >
                                    -
                                </p>

                                <p
                                    id="student-cohort"
                                    class="mt-1 text-[11px] text-slate-400"
                                >
                                    -
                                </p>
                            </div>
                        </div>

                        {{-- IPS statis: tidak menggunakan variabel $semester --}}
                        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-5">
                            <div class="rounded-xl border border-slate-200 bg-white p-3 text-center">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400">
                                    IPS 1
                                </p>

                                <p
                                    id="preview-ips-1"
                                    class="mt-2 text-lg font-bold text-slate-700"
                                >
                                    0.00
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-3 text-center">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400">
                                    IPS 2
                                </p>

                                <p
                                    id="preview-ips-2"
                                    class="mt-2 text-lg font-bold text-slate-700"
                                >
                                    0.00
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-3 text-center">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400">
                                    IPS 3
                                </p>

                                <p
                                    id="preview-ips-3"
                                    class="mt-2 text-lg font-bold text-slate-700"
                                >
                                    0.00
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-3 text-center">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400">
                                    IPS 4
                                </p>

                                <p
                                    id="preview-ips-4"
                                    class="mt-2 text-lg font-bold text-slate-700"
                                >
                                    0.00
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-3 text-center">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400">
                                    IPS 5
                                </p>

                                <p
                                    id="preview-ips-5"
                                    class="mt-2 text-lg font-bold text-slate-700"
                                >
                                    0.00
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                                <p class="text-xs font-medium text-blue-500">
                                    Rata-rata IPS
                                </p>

                                <p
                                    id="preview-average"
                                    class="mt-2 text-2xl font-bold text-blue-700"
                                >
                                    0.000
                                </p>
                            </div>

                            <div class="rounded-xl border border-violet-100 bg-violet-50 p-4">
                                <p class="text-xs font-medium text-violet-500">
                                    IPK Akhir Aktual
                                </p>

                                <p
                                    id="preview-actual"
                                    class="mt-2 text-2xl font-bold text-violet-700"
                                >
                                    -
                                </p>

                                <p class="mt-1 text-[10px] text-violet-500">
                                    Digunakan untuk evaluasi apabila tersedia
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Penjelasan proses --}}
                    <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <div class="flex items-start gap-3">
                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0 text-blue-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 11v5"></path>
                                <path d="M12 8h.01"></path>
                            </svg>

                            <div>
                                <p class="text-sm font-semibold text-blue-700">
                                    Proses prediksi
                                </p>

                                <p class="mt-1 text-xs leading-5 text-blue-600">
                                    Sistem melakukan normalisasi nilai IPS,
                                    forward propagation, denormalisasi output,
                                    kemudian menyimpan hasil prediksi dan nilai
                                    error apabila IPK aktual tersedia.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol submit --}}
                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs text-slate-400">
                                Hasil diproses menggunakan model ANN aktif.
                            </p>

                            @unless ($modelReady)
                                <p class="mt-1 text-xs font-medium text-amber-600">
                                    Belum ada model ANN yang siap digunakan.
                                </p>
                            @endunless
                        </div>

                        <button
                            type="submit"
                            data-prediction-button
                            data-model-ready="{{ $modelReady ? 'true' : 'false' }}"
                            disabled
                            class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
                        >
                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M8 5v14l11-7Z"></path>
                            </svg>

                            Proses Prediksi
                        </button>
                    </div>
                </form>
            </section>

            {{-- Informasi model --}}
            <div class="space-y-5">
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                Model yang Digunakan
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Informasi model ANN aktif.
                            </p>
                        </div>

                        <div
                            class="grid h-10 w-10 place-items-center rounded-xl
                            {{ $modelReady
                                ? 'bg-emerald-50 text-emerald-600'
                                : 'bg-slate-100 text-slate-400' }}"
                        >
                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M5 12h4"></path>
                                <path d="M15 12h4"></path>
                                <path d="M12 5v4"></path>
                                <path d="M12 15v4"></path>
                            </svg>
                        </div>
                    </div>

                    @if ($modelReady)
                        <div class="mt-5">
                            <div class="flex items-center justify-between rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                                <div>
                                    <p class="text-xs text-emerald-500">
                                        Model aktif
                                    </p>

                                    <p class="mt-1 text-lg font-bold text-emerald-700">
                                        Model #{{ $activeModel->id }}
                                    </p>
                                </div>

                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-semibold text-emerald-600">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Siap
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        Arsitektur
                                    </p>

                                    <p class="mt-2 text-base font-bold text-slate-700">
                                        {{ $activeModel->architectureLabel() }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        Total Dataset
                                    </p>

                                    <p class="mt-2 text-base font-bold text-slate-700">
                                        {{ number_format($activeModel->totalDataset()) }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        MAE Testing
                                    </p>

                                    <p class="mt-2 text-base font-bold text-blue-600">
                                        {{ $activeModel->mae !== null
                                            ? number_format((float) $activeModel->mae, 4)
                                            : '-' }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        MSE Testing
                                    </p>

                                    <p class="mt-2 text-base font-bold text-violet-600">
                                        {{ $activeModel->mse !== null
                                            ? number_format((float) $activeModel->mse, 4)
                                            : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 border-t border-slate-100 pt-4">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-400">
                                        Data training
                                    </span>

                                    <span class="font-semibold text-slate-700">
                                        {{ number_format($activeModel->train_count) }}
                                    </span>
                                </div>

                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <span class="text-slate-400">
                                        Data testing
                                    </span>

                                    <span class="font-semibold text-slate-700">
                                        {{ number_format($activeModel->test_count) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-5 rounded-xl border border-dashed border-slate-200 py-10 text-center">
                            <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                                <svg
                                    class="h-6 w-6"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <circle cx="5" cy="7" r="2"></circle>
                                    <circle cx="19" cy="7" r="2"></circle>
                                    <circle cx="5" cy="17" r="2"></circle>
                                    <circle cx="19" cy="17" r="2"></circle>
                                    <path d="m7 8 3 3"></path>
                                    <path d="m17 8-3 3"></path>
                                    <path d="m7 16 3-3"></path>
                                    <path d="m17 16-3-3"></path>
                                </svg>
                            </div>

                            <p class="mt-4 text-sm font-semibold text-slate-600">
                                Belum ada model aktif
                            </p>

                            <p class="mx-auto mt-2 max-w-xs text-xs leading-5 text-slate-400">
                                Lengkapi dataset dan jalankan training ANN
                                terlebih dahulu.
                            </p>

                            <a
                                href="{{ route('admin.model-ann.index') }}"
                                class="mt-4 inline-flex h-10 items-center justify-center rounded-lg bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700"
                            >
                                Buka Model ANN
                            </a>
                        </div>
                    @endif
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-base font-semibold text-slate-800">
                        Alur Prediksi
                    </h2>

                    <div class="mt-5 space-y-4">
                        <div class="flex gap-3">
                            <div class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                1
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-slate-700">
                                    Pilih mahasiswa
                                </p>

                                <p class="mt-1 text-[11px] leading-5 text-slate-400">
                                    Sistem mengambil IPS Semester 1–5.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                2
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-slate-700">
                                    Forward propagation
                                </p>

                                <p class="mt-1 text-[11px] leading-5 text-slate-400">
                                    Input diproses menggunakan bobot model ANN.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                3
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-slate-700">
                                    Simpan hasil
                                </p>

                                <p class="mt-1 text-[11px] leading-5 text-slate-400">
                                    IPK prediksi dan evaluasi error disimpan.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        {{-- Hasil terbaru --}}
        @if ($latestResult)
            <section class="overflow-hidden rounded-xl border border-emerald-200 bg-white shadow-sm">
                <div class="border-b border-emerald-100 bg-emerald-50 px-6 py-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-500">
                                Hasil Prediksi Terbaru
                            </p>

                            <h2 class="mt-1 text-lg font-bold text-emerald-800">
                                {{ $latestResult->mahasiswa?->nama ?? '-' }}
                            </h2>

                            <p class="mt-1 text-xs text-emerald-600">
                                {{ $latestResult->nomor_prediksi }}
                            </p>
                        </div>

                        <span class="inline-flex items-center gap-2 self-start rounded-full bg-white px-3 py-1 text-xs font-semibold text-emerald-600 sm:self-auto">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Berhasil
                        </span>
                    </div>
                </div>

                <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                        <p class="text-xs font-medium text-blue-500">
                            IPK Prediksi
                        </p>

                        <p class="mt-2 text-3xl font-bold text-blue-700">
                            {{ number_format(
                                (float) $latestResult->ipk_prediksi,
                                3
                            ) }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-violet-100 bg-violet-50 p-5">
                        <p class="text-xs font-medium text-violet-500">
                            IPK Aktual
                        </p>

                        <p class="mt-2 text-3xl font-bold text-violet-700">
                            {{ $latestResult->ipk_aktual !== null
                                ? number_format(
                                    (float) $latestResult->ipk_aktual,
                                    3
                                )
                                : '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                        <p class="text-xs font-medium text-amber-500">
                            Absolute Error
                        </p>

                        <p class="mt-2 text-3xl font-bold text-amber-700">
                            {{ $latestResult->absolute_error !== null
                                ? number_format(
                                    (float) $latestResult->absolute_error,
                                    4
                                )
                                : '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-medium text-slate-400">
                            Model ANN
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-700">
                            #{{ $latestResult->model_ann_id }}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $latestResult->predicted_at?->format('d M Y, H:i') ?? '-' }}
                        </p>
                    </div>
                </div>

                @if ($latestResult->keterangan)
                    <div class="border-t border-slate-100 px-6 py-4">
                        <p class="text-xs leading-5 text-slate-500">
                            {{ $latestResult->keterangan }}
                        </p>
                    </div>
                @endif
            </section>
        @endif

        {{-- Riwayat terbaru --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Prediksi Terbaru
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Riwayat hasil prediksi yang baru diproses.
                        </p>
                    </div>

                    <span class="self-start rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 sm:self-auto">
                        {{ number_format($totalPredictions) }} hasil
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left">
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

                            <th class="px-5 py-4 font-semibold">
                                Model
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Waktu
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentPredictions as $prediction)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">
                                            {{ strtoupper(
                                                substr(
                                                    $prediction->mahasiswa?->nama ?? 'M',
                                                    0,
                                                    1
                                                )
                                            ) }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">
                                                {{ $prediction->mahasiswa?->nama ?? '-' }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $prediction->mahasiswa?->nim ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-xs font-semibold text-slate-600">
                                        {{ $prediction->nomor_prediksi }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-slate-600">
                                    {{ number_format(
                                        $prediction->averageIps(),
                                        3
                                    ) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-bold text-blue-600">
                                        {{ number_format(
                                            (float) $prediction->ipk_prediksi,
                                            3
                                        ) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-violet-600">
                                    {{ $prediction->ipk_aktual !== null
                                        ? number_format(
                                            (float) $prediction->ipk_aktual,
                                            3
                                        )
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-amber-600">
                                    {{ $prediction->absolute_error !== null
                                        ? number_format(
                                            (float) $prediction->absolute_error,
                                            4
                                        )
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        #{{ $prediction->model_ann_id }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-xs font-medium text-slate-600">
                                        {{ $prediction->predicted_at?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-[10px] text-slate-400">
                                        {{ $prediction->predicted_at?->format('H:i') ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="8"
                                    class="px-6 py-16 text-center"
                                >
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                                        <svg
                                            class="h-6 w-6"
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

                                    <p class="mt-1 text-xs text-slate-400">
                                        Hasil akan muncul setelah model ANN digunakan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('mahasiswa_id');
            const preview = document.getElementById('student-preview');
            const form = document.getElementById('prediction-form');
            const button = document.querySelector(
                '[data-prediction-button]'
            );

            const formatNumber = (value, decimals = 2) => {
                const number = Number.parseFloat(value);

                return Number.isFinite(number)
                    ? number.toFixed(decimals)
                    : '-';
            };

            const updateButtonState = () => {
                if (!button || !select) {
                    return;
                }

                const modelReady =
                    button.dataset.modelReady === 'true';

                button.disabled =
                    !modelReady
                    || !select.value;
            };

            const updatePreview = () => {
                if (!select || !preview) {
                    return;
                }

                const option =
                    select.options[select.selectedIndex];

                if (!option || !option.value) {
                    preview.classList.add('hidden');
                    updateButtonState();

                    return;
                }

                const values = [
                    Number.parseFloat(option.dataset.ips1),
                    Number.parseFloat(option.dataset.ips2),
                    Number.parseFloat(option.dataset.ips3),
                    Number.parseFloat(option.dataset.ips4),
                    Number.parseFloat(option.dataset.ips5),
                ];

                const studentName =
                    option.dataset.nama || '-';

                document.getElementById(
                    'student-name'
                ).textContent = studentName;

                document.getElementById(
                    'student-nim'
                ).textContent =
                    `NIM ${option.dataset.nim || '-'}`;

                document.getElementById(
                    'student-initial'
                ).textContent =
                    studentName.charAt(0).toUpperCase();

                document.getElementById(
                    'student-program'
                ).textContent =
                    option.dataset.programStudi || '-';

                document.getElementById(
                    'student-cohort'
                ).textContent =
                    option.dataset.angkatan
                        ? `Angkatan ${option.dataset.angkatan}`
                        : '-';

                values.forEach((value, index) => {
                    const target = document.getElementById(
                        `preview-ips-${index + 1}`
                    );

                    if (target) {
                        target.textContent =
                            Number.isFinite(value)
                                ? value.toFixed(2)
                                : '-';
                    }
                });

                const validValues = values.filter(
                    Number.isFinite
                );

                const average =
                    validValues.length === 5
                        ? validValues.reduce(
                            (total, value) => total + value,
                            0
                        ) / validValues.length
                        : null;

                document.getElementById(
                    'preview-average'
                ).textContent =
                    average !== null
                        ? average.toFixed(3)
                        : '-';

                document.getElementById(
                    'preview-actual'
                ).textContent = formatNumber(
                    option.dataset.actual,
                    3
                );

                preview.classList.remove('hidden');
                updateButtonState();
            };

            select?.addEventListener(
                'change',
                updatePreview
            );

            updatePreview();
            updateButtonState();

            if (!form || !button || !select) {
                return;
            }

            form.addEventListener(
                'submit',
                async event => {
                    if (
                        form.dataset.confirmed === 'true'
                    ) {
                        return;
                    }

                    event.preventDefault();

                    if (!select.value) {
                        select.focus();
                        return;
                    }

                    if (
                        button.dataset.modelReady !== 'true'
                    ) {
                        return;
                    }

                    const selectedOption =
                        select.options[
                            select.selectedIndex
                        ];

                    const studentName =
                        selectedOption.dataset.nama
                        || 'mahasiswa terpilih';

                    let confirmed = false;

                    if (window.Swal) {
                        const result =
                            await window.Swal.fire({
                                icon: 'question',
                                title: 'Proses Prediksi IPK?',
                                text:
                                    `Prediksi akan dijalankan untuk ${studentName} menggunakan model ANN aktif.`,
                                showCancelButton: true,
                                confirmButtonText:
                                    'Ya, Proses Prediksi',
                                cancelButtonText: 'Batal',
                                confirmButtonColor:
                                    '#2563eb',
                                cancelButtonColor:
                                    '#64748b',
                                reverseButtons: true,
                            });

                        confirmed = result.isConfirmed;
                    } else {
                        confirmed = window.confirm(
                            `Proses prediksi IPK untuk ${studentName}?`
                        );
                    }

                    if (!confirmed) {
                        return;
                    }

                    form.dataset.confirmed = 'true';
                    button.disabled = true;

                    button.innerHTML = `
                        <svg
                            class="h-5 w-5 animate-spin"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="9"
                                stroke="currentColor"
                                stroke-width="3"
                            ></circle>

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3Z"
                            ></path>
                        </svg>

                        Memproses Prediksi...
                    `;

                    form.submit();
                }
            );
        });
    </script>
@endpush