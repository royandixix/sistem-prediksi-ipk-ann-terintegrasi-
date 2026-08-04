@extends('layouts.operator')

@section('title', 'Detail Hasil Prediksi')

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

                    <a
                        href="{{ route('operator.hasil-prediksi.index') }}"
                        class="transition hover:text-blue-600"
                    >
                        Hasil Prediksi
                    </a>

                    <span>/</span>
                    <span>Detail</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                    Detail Hasil Prediksi
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $prediction->nomor_prediksi }}
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row">
                <a
                    href="{{ route('operator.hasil-prediksi.index') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50"
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

                    Kembali
                </a>

                <a
                    href="{{ route('operator.prediksi-ipk.create') }}"
                    class="inline-flex min-h-11 items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    Prediksi Baru
                </a>
            </div>
        </div>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50 px-5 py-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-blue-600 text-lg font-bold text-white">
                            {{ strtoupper(substr($prediction->mahasiswa?->nama ?? 'M', 0, 1)) }}
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-slate-800">
                                {{ $prediction->mahasiswa?->nama ?? '-' }}
                            </h2>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $prediction->mahasiswa?->nim ?? '-' }}
                                · Angkatan {{ $prediction->mahasiswa?->angkatan ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-2 self-start rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-600 sm:self-auto">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Prediksi tersimpan
                    </span>
                </div>
            </div>

            <div class="grid gap-4 p-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-xs font-semibold text-blue-500">
                        IPK Prediksi
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-700">
                        {{ number_format((float) $prediction->ipk_prediksi, 3) }}
                    </p>
                </div>

                <div class="rounded-xl border border-violet-100 bg-violet-50 p-5">
                    <p class="text-xs font-semibold text-violet-500">
                        IPK Aktual
                    </p>

                    <p class="mt-2 text-3xl font-bold text-violet-700">
                        {{ $prediction->ipk_aktual !== null
                            ? number_format((float) $prediction->ipk_aktual, 3)
                            : '-' }}
                    </p>
                </div>

                <div class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                    <p class="text-xs font-semibold text-amber-500">
                        Absolute Error
                    </p>

                    <p class="mt-2 text-3xl font-bold text-amber-700">
                        {{ $prediction->absolute_error !== null
                            ? number_format((float) $prediction->absolute_error, 4)
                            : '-' }}
                    </p>
                </div>

                <div class="rounded-xl border border-rose-100 bg-rose-50 p-5">
                    <p class="text-xs font-semibold text-rose-500">
                        Squared Error
                    </p>

                    <p class="mt-2 text-3xl font-bold text-rose-700">
                        {{ $prediction->squared_error !== null
                            ? number_format((float) $prediction->squared_error, 6)
                            : '-' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="grid gap-5 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.7fr)]">
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Nilai IPS Mahasiswa
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Data input yang digunakan dalam proses prediksi.
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                        @foreach ($prediction->ipsValues() as $index => $value)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                                <p class="text-xs font-semibold text-slate-400">
                                    IPS {{ $index + 1 }}
                                </p>

                                <p class="mt-2 text-xl font-bold text-slate-700">
                                    {{ number_format((float) $value, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs text-blue-500">
                                    Rata-rata IPS
                                </p>

                                <p class="mt-1 text-2xl font-bold text-blue-700">
                                    {{ number_format($prediction->averageIps(), 3) }}
                                </p>
                            </div>

                            <div class="grid h-11 w-11 place-items-center rounded-xl bg-white text-blue-600">
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
                        </div>
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Model ANN
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Model yang digunakan dalam prediksi.
                    </p>
                </div>

                <div class="space-y-4 p-5">
                    <div class="rounded-xl bg-emerald-50 p-4">
                        <p class="text-xs text-emerald-500">
                            Model
                        </p>

                        <p class="mt-1 text-lg font-bold text-emerald-700">
                            Model #{{ $prediction->model_ann_id }}
                        </p>

                        <p class="mt-1 text-xs text-emerald-600">
                            {{ $prediction->modelAnn?->kode_model ?? '-' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 text-sm">
                        <span class="text-slate-400">
                            Arsitektur
                        </span>

                        <span class="font-bold text-slate-700">
                            {{ $prediction->modelAnn?->architectureLabel() ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 text-sm">
                        <span class="text-slate-400">
                            MAE Testing
                        </span>

                        <span class="font-bold text-slate-700">
                            {{ $prediction->modelAnn?->mae !== null
                                ? number_format((float) $prediction->modelAnn->mae, 4)
                                : '-' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">
                            MSE Testing
                        </span>

                        <span class="font-bold text-slate-700">
                            {{ $prediction->modelAnn?->mse !== null
                                ? number_format((float) $prediction->modelAnn->mse, 6)
                                : '-' }}
                        </span>
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-5 lg:grid-cols-2">
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Input Ternormalisasi
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Nilai input setelah proses normalisasi.
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                        @forelse ($normalizedInputs as $index => $value)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 text-center">
                                <p class="text-[10px] font-semibold text-slate-400">
                                    Input {{ $index + 1 }}
                                </p>

                                <p class="mt-2 text-sm font-bold text-slate-700">
                                    {{ number_format((float) $value, 6) }}
                                </p>
                            </div>
                        @empty
                            <div class="col-span-full rounded-xl border border-dashed border-slate-200 py-8 text-center">
                                <p class="text-sm text-slate-400">
                                    Data normalisasi tidak tersedia.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Informasi Proses
                    </h2>
                </div>

                <div class="space-y-4 p-5">
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="text-sm text-slate-400">
                            Nomor Prediksi
                        </span>

                        <span class="text-right text-sm font-bold text-slate-700">
                            {{ $prediction->nomor_prediksi }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="text-sm text-slate-400">
                            Diproses Oleh
                        </span>

                        <span class="text-right text-sm font-bold text-slate-700">
                            {{ $prediction->predictedBy?->name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="text-sm text-slate-400">
                            Waktu Prediksi
                        </span>

                        <span class="text-right text-sm font-bold text-slate-700">
                            {{ $prediction->predicted_at?->translatedFormat('d F Y, H:i:s') ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-slate-400">
                            Status Evaluasi
                        </span>

                        @if ($prediction->isEvaluated())
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-600">
                                Sudah dievaluasi
                            </span>
                        @else
                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-600">
                                Belum dievaluasi
                            </span>
                        @endif
                    </div>
                </div>
            </article>
        </section>

        @if (filled($prediction->keterangan))
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800">
                    Keterangan
                </h2>

                <p class="mt-3 text-sm leading-6 text-slate-500">
                    {{ $prediction->keterangan }}
                </p>
            </section>
        @endif
    </div>
@endsection