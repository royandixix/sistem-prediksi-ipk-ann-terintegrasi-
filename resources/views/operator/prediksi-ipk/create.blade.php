@extends('layouts.operator')

@section('title', 'Prediksi IPK')

@section('content')
    @php
        $modelReady = $activeModel !== null;
    @endphp

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
                    <span>Prediksi IPK</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                    Prediksi IPK Mahasiswa
                </h1>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Proses prediksi IPK akhir berdasarkan nilai IPS Semester 1 sampai Semester 5.
                </p>
            </div>

            @if ($modelReady)
                <div class="inline-flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>

                    <div>
                        <p class="text-xs font-bold text-emerald-700">
                            Sistem Prediksi Siap
                        </p>

                        <p class="mt-1 text-xs text-emerald-600">
                            Model #{{ $activeModel->id }}
                            · {{ $activeModel->architectureLabel() }}
                        </p>
                    </div>
                </div>
            @else
                <div class="inline-flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                    <span class="h-3 w-3 rounded-full bg-amber-500"></span>

                    <div>
                        <p class="text-xs font-bold text-amber-700">
                            Model Belum Siap
                        </p>

                        <p class="mt-1 text-xs text-amber-600">
                            Hubungi administrator.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-emerald-100 text-emerald-600">
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

                    <div>
                        <p class="text-sm font-bold text-emerald-700">
                            Prediksi berhasil
                        </p>

                        <p class="mt-1 text-xs text-emerald-600">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
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
                            Prediksi gagal
                        </p>

                        <p class="mt-1 text-xs text-red-600">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->has('model'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm font-medium text-amber-700">
                {{ $errors->first('model') }}
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Model ANN
                </p>

                <p class="mt-3 text-xl font-bold {{ $modelReady ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $modelReady ? 'Aktif dan Siap' : 'Belum Tersedia' }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    {{ $modelReady ? 'Model #' . $activeModel->id : 'Menunggu administrator' }}
                </p>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Kandidat Prediksi
                </p>

                <p class="mt-3 text-3xl font-bold text-blue-600">
                    {{ number_format($totalCandidates) }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Memiliki IPS Semester 1–5 lengkap
                </p>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Sudah Diprediksi
                </p>

                <p class="mt-3 text-3xl font-bold text-violet-600">
                    {{ number_format($predictedStudents) }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Mahasiswa telah memiliki hasil
                </p>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Prediksi Saya
                </p>

                <p class="mt-3 text-3xl font-bold text-amber-600">
                    {{ number_format($myPredictionCount) }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Diproses melalui akun ini
                </p>
            </article>
        </section>

        <section class="grid gap-5 xl:grid-cols-[minmax(0,1.3fr)_minmax(330px,0.7fr)]">
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">
                        Form Prediksi IPK
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-slate-400">
                        Pilih mahasiswa untuk menampilkan Data IPS dan menjalankan proses prediksi.
                    </p>
                </div>

                <form
                    id="prediction-form"
                    action="{{ route('operator.prediksi-ipk.store') }}"
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

                            @foreach ($candidates as $candidate)
                                @php
                                    $dataIps = $candidate->dataIps;
                                @endphp

                                <option
                                    value="{{ $candidate->id }}"
                                    data-nim="{{ $candidate->nim }}"
                                    data-nama="{{ $candidate->nama }}"
                                    data-ips1="{{ $dataIps?->ips_1 }}"
                                    data-ips2="{{ $dataIps?->ips_2 }}"
                                    data-ips3="{{ $dataIps?->ips_3 }}"
                                    data-ips4="{{ $dataIps?->ips_4 }}"
                                    data-ips5="{{ $dataIps?->ips_5 }}"
                                    data-actual="{{ $dataIps?->ipk_akhir_aktual }}"
                                    @selected((string) old('mahasiswa_id') === (string) $candidate->id)
                                >
                                    {{ $candidate->nim }} — {{ $candidate->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('mahasiswa_id')
                            <p class="mt-2 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div
                        id="student-preview"
                        class="mt-5 hidden rounded-xl border border-blue-100 bg-blue-50 p-4"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                id="student-initial"
                                class="grid h-10 w-10 place-items-center rounded-full bg-blue-600 text-sm font-bold text-white"
                            >
                                M
                            </div>

                            <div>
                                <p
                                    id="student-name"
                                    class="text-sm font-bold text-slate-700"
                                >
                                    -
                                </p>

                                <p
                                    id="student-nim"
                                    class="mt-1 text-xs text-slate-400"
                                >
                                    NIM -
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-5">
                            @for ($semester = 1; $semester <= 5; $semester++)
                                <div class="rounded-lg bg-white p-3 text-center">
                                    <p class="text-[10px] font-semibold text-slate-400">
                                        IPS {{ $semester }}
                                    </p>

                                    <p
                                        id="preview-ips-{{ $semester }}"
                                        class="mt-1 text-sm font-bold text-slate-700"
                                    >
                                        -
                                    </p>
                                </div>
                            @endfor
                        </div>

                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-400">
                                    Rata-rata IPS
                                </p>

                                <p
                                    id="preview-average"
                                    class="mt-1 text-lg font-bold text-blue-600"
                                >
                                    0.000
                                </p>
                            </div>

                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-400">
                                    IPK Aktual
                                </p>

                                <p
                                    id="preview-actual"
                                    class="mt-1 text-lg font-bold text-violet-600"
                                >
                                    -
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <p class="text-xs leading-5 text-blue-700">
                            Sistem melakukan normalisasi nilai IPS, forward propagation, denormalisasi output, kemudian menyimpan hasil prediksi dan nilai error apabila IPK aktual tersedia.
                        </p>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-400">
                            Hasil diproses menggunakan model ANN aktif.
                        </p>

                        <button
                            type="submit"
                            data-prediction-button
                            @disabled(! $modelReady || $candidates->isEmpty())
                            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
                        >
                            <svg
                                class="h-4 w-4"
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
            </article>

            <aside class="space-y-5">
                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-slate-800">
                            Model yang Digunakan
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Informasi model ANN aktif.
                        </p>
                    </div>

                    @if ($modelReady)
                        <div class="space-y-4 p-5">
                            <div class="flex items-center justify-between rounded-xl bg-emerald-50 p-4">
                                <div>
                                    <p class="text-xs text-emerald-600">
                                        Model aktif
                                    </p>

                                    <p class="mt-1 text-lg font-bold text-emerald-700">
                                        Model #{{ $activeModel->id }}
                                    </p>
                                </div>

                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-emerald-600">
                                    Siap
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
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
                                        {{ number_format((float) $activeModel->mae, 4) }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        MSE Testing
                                    </p>

                                    <p class="mt-2 text-base font-bold text-violet-600">
                                        {{ number_format((float) $activeModel->mse, 4) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <p class="text-sm font-semibold text-slate-600">
                                Model belum tersedia
                            </p>

                            <p class="mt-2 text-xs leading-5 text-slate-400">
                                Administrator harus melakukan training dan mengaktifkan model.
                            </p>
                        </div>
                    @endif
                </article>

                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-slate-800">
                            Alur Prediksi
                        </h2>
                    </div>

                    <div class="space-y-4 p-5">
                        @foreach ([
                            ['1', 'Pilih mahasiswa', 'Sistem mengambil IPS Semester 1–5.'],
                            ['2', 'Forward propagation', 'Input diproses menggunakan bobot model ANN.'],
                            ['3', 'Simpan hasil', 'IPK prediksi dan evaluasi error disimpan.'],
                        ] as $step)
                            <div class="flex gap-3">
                                <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-blue-50 text-xs font-bold text-blue-600">
                                    {{ $step[0] }}
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $step[1] }}
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-400">
                                        {{ $step[2] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </aside>
        </section>

        @if ($latestResult)
            <section class="overflow-hidden rounded-xl border border-emerald-200 bg-white shadow-sm">
                <div class="border-b border-emerald-100 bg-emerald-50 px-5 py-4">
                    <div class="flex items-center justify-between gap-4">
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

                        <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-emerald-600">
                            Berhasil
                        </span>
                    </div>
                </div>

                <div class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-blue-50 p-4">
                        <p class="text-xs text-blue-500">
                            IPK Prediksi
                        </p>

                        <p class="mt-2 text-2xl font-bold text-blue-700">
                            {{ number_format((float) $latestResult->ipk_prediksi, 3) }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-violet-50 p-4">
                        <p class="text-xs text-violet-500">
                            IPK Aktual
                        </p>

                        <p class="mt-2 text-2xl font-bold text-violet-700">
                            {{ $latestResult->ipk_aktual !== null
                                ? number_format((float) $latestResult->ipk_aktual, 3)
                                : '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-amber-50 p-4">
                        <p class="text-xs text-amber-500">
                            Absolute Error
                        </p>

                        <p class="mt-2 text-2xl font-bold text-amber-700">
                            {{ $latestResult->absolute_error !== null
                                ? number_format((float) $latestResult->absolute_error, 4)
                                : '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">
                            Model ANN
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-700">
                            #{{ $latestResult->model_ann_id }}
                        </p>
                    </div>
                </div>
            </section>
        @endif

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        Prediksi Terbaru Saya
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Delapan hasil terakhir yang diproses melalui akun ini.
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
                <table class="w-full min-w-[720px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-5 py-3.5">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-3.5">
                                IPK Prediksi
                            </th>

                            <th class="px-5 py-3.5">
                                IPK Aktual
                            </th>

                            <th class="px-5 py-3.5">
                                Error
                            </th>

                            <th class="px-5 py-3.5">
                                Model
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

                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        #{{ $prediction->model_ann_id }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right text-xs text-slate-500">
                                    {{ $prediction->predicted_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="px-5 py-14 text-center"
                                >
                                    <p class="text-sm font-semibold text-slate-500">
                                        Belum ada hasil prediksi
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Pilih mahasiswa dan jalankan proses prediksi.
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
            const button = document.querySelector('[data-prediction-button]');

            const updatePreview = () => {
                if (!select || !preview) {
                    return;
                }

                const option = select.options[select.selectedIndex];

                if (!option || !option.value) {
                    preview.classList.add('hidden');
                    return;
                }

                const values = [
                    Number.parseFloat(option.dataset.ips1),
                    Number.parseFloat(option.dataset.ips2),
                    Number.parseFloat(option.dataset.ips3),
                    Number.parseFloat(option.dataset.ips4),
                    Number.parseFloat(option.dataset.ips5),
                ];

                document.getElementById('student-name').textContent =
                    option.dataset.nama || '-';

                document.getElementById('student-nim').textContent =
                    `NIM ${option.dataset.nim || '-'}`;

                document.getElementById('student-initial').textContent =
                    (option.dataset.nama || 'M').charAt(0).toUpperCase();

                values.forEach((value, index) => {
                    const target = document.getElementById(
                        `preview-ips-${index + 1}`
                    );

                    if (target) {
                        target.textContent = Number.isFinite(value)
                            ? value.toFixed(2)
                            : '-';
                    }
                });

                const validValues = values.filter(Number.isFinite);

                const average = validValues.length === 5
                    ? validValues.reduce(
                        (total, value) => total + value,
                        0
                    ) / 5
                    : 0;

                document.getElementById('preview-average').textContent =
                    average.toFixed(3);

                const actual = Number.parseFloat(
                    option.dataset.actual
                );

                document.getElementById('preview-actual').textContent =
                    Number.isFinite(actual)
                        ? actual.toFixed(3)
                        : '-';

                preview.classList.remove('hidden');
            };

            select?.addEventListener('change', updatePreview);
            updatePreview();

            form?.addEventListener('submit', () => {
                if (!button || button.disabled) {
                    return;
                }

                button.disabled = true;

                button.innerHTML = `
                    <svg
                        class="h-4 w-4 animate-spin"
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
            });
        });
    </script>
@endpush