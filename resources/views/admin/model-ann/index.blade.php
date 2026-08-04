@extends('layouts.admin')

@section('title', 'Model ANN')

@section('content')
    <div class="space-y-5">
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

                    <span>Model ANN</span>
                </div>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
                    Model Artificial Neural Network
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Training, evaluasi, dan pengelolaan model prediksi IPK mahasiswa.
                </p>
            </div>

            @if ($activeModel)
                <div class="inline-flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <span class="relative flex h-3 w-3">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-50"></span>
                        <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                    </span>

                    <div>
                        <p class="text-xs font-semibold text-emerald-700">
                            Model Aktif
                        </p>

                        <p class="mt-0.5 text-[11px] text-emerald-600">
                            Model #{{ $activeModel->id }}
                            · {{ $activeModel->architectureLabel() }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

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

                <p class="text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </p>
            </div>
        @endif

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

                <p class="text-sm font-medium text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        @endif

        @if ($errors->has('dataset'))
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
                    {{ $errors->first('dataset') }}
                </p>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Total Data IPS
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalDataIps) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Seluruh data akademik
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
                            Data Layak Training
                        </p>

                        <p class="mt-3 text-3xl font-bold text-emerald-600">
                            {{ number_format($eligibleDataset) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            IPS lengkap dan memiliki target
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
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Tanpa IPK Aktual
                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-600">
                            {{ number_format($withoutActualIpk) }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Belum dapat menjadi target training
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
                            <path d="M12 8v5"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                    </div>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            MAE Terbaik
                        </p>

                        <p class="mt-3 text-3xl font-bold text-violet-600">
                            {{ $bestMae !== null
                                ? number_format((float) $bestMae, 4)
                                : '-' }}
                        </p>

                        <p class="mt-2 text-[11px] text-slate-400">
                            Nilai error model terendah
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
        </section>

        <div class="grid gap-5 xl:grid-cols-[minmax(0,1.25fr)_minmax(360px,.75fr)]">
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                Konfigurasi Training
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Atur parameter backpropagation sebelum melatih model.
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
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6l-.04.08H10l-.04-.08a1.7 1.7 0 0 0-1-.6 1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1L3.92 14v-4L4 9.96a1.7 1.7 0 0 0 .6-1 1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6l.04-.08h4L14.08 4a1.7 1.7 0 0 0 1 .6 1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 9c.1.38.3.72.6 1l.08.04v4L20 14.08a1.7 1.7 0 0 0-.6.92Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <form
                    id="training-form"
                    action="{{ route('admin.model-ann.train') }}"
                    method="POST"
                    class="p-6"
                >
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label
                                for="hidden_neurons"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Neuron Hidden Layer
                            </label>

                            <input
                                type="number"
                                id="hidden_neurons"
                                name="hidden_neurons"
                                value="{{ old('hidden_neurons', 8) }}"
                                min="3"
                                max="32"
                                required
                                class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4
                                @error('hidden_neurons')
                                    border-red-300 focus:border-red-400 focus:ring-red-100
                                @else
                                    border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                @enderror"
                            >

                            <p class="mt-2 text-xs text-slate-400">
                                Jumlah neuron pada lapisan tersembunyi.
                            </p>

                            @error('hidden_neurons')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="learning_rate"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Learning Rate
                            </label>

                            <input
                                type="number"
                                id="learning_rate"
                                name="learning_rate"
                                value="{{ old('learning_rate', 0.1) }}"
                                min="0.001"
                                max="1"
                                step="0.001"
                                required
                                class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4
                                @error('learning_rate')
                                    border-red-300 focus:border-red-400 focus:ring-red-100
                                @else
                                    border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                @enderror"
                            >

                            <p class="mt-2 text-xs text-slate-400">
                                Besar pembaruan bobot setiap iterasi.
                            </p>

                            @error('learning_rate')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="epochs"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Jumlah Epoch
                            </label>

                            <input
                                type="number"
                                id="epochs"
                                name="epochs"
                                value="{{ old('epochs', 1000) }}"
                                min="100"
                                max="10000"
                                step="100"
                                required
                                class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4
                                @error('epochs')
                                    border-red-300 focus:border-red-400 focus:ring-red-100
                                @else
                                    border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                @enderror"
                            >

                            <p class="mt-2 text-xs text-slate-400">
                                Maksimal iterasi proses pembelajaran.
                            </p>

                            @error('epochs')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="test_percentage"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Persentase Testing
                            </label>

                            <select
                                id="test_percentage"
                                name="test_percentage"
                                required
                                class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50"
                            >
                                @foreach ([10, 20, 30, 40] as $percentage)
                                    <option
                                        value="{{ $percentage }}"
                                        @selected((int) old('test_percentage', 20) === $percentage)
                                    >
                                        {{ $percentage }}% testing
                                        · {{ 100 - $percentage }}% training
                                    </option>
                                @endforeach
                            </select>

                            @error('test_percentage')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label
                                for="random_seed"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Random Seed
                            </label>

                            <input
                                type="number"
                                id="random_seed"
                                name="random_seed"
                                value="{{ old('random_seed', 42) }}"
                                min="1"
                                max="999999"
                                required
                                class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4
                                @error('random_seed')
                                    border-red-300 focus:border-red-400 focus:ring-red-100
                                @else
                                    border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                @enderror"
                            >

                            <p class="mt-2 text-xs text-slate-400">
                                Nilai yang sama menghasilkan pembagian dataset yang konsisten.
                            </p>

                            @error('random_seed')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

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
                                    Arsitektur jaringan
                                </p>

                                <p class="mt-1 text-xs leading-5 text-blue-600">
                                    5 neuron input untuk IPS Semester 1–5,
                                    satu hidden layer, dan 1 neuron output untuk
                                    IPK akhir. Fungsi aktivasi menggunakan sigmoid.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs text-slate-400">
                                Model terbaru otomatis menjadi model aktif.
                            </p>

                            @unless ($trainingReady)
                                <p class="mt-1 text-xs font-medium text-amber-600">
                                    Dataset belum memenuhi batas minimum training.
                                </p>
                            @endunless
                        </div>

                        <button
                            type="submit"
                            data-training-button
                            @disabled(!$trainingReady)
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

                            Mulai Training ANN
                        </button>
                    </div>
                </form>
            </section>

            <div class="space-y-5">
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                Kesiapan Dataset
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Pemeriksaan data sebelum training.
                            </p>
                        </div>

                        <span
                            class="rounded-full px-3 py-1 text-xs font-semibold
                            {{ $trainingReady
                                ? 'bg-emerald-50 text-emerald-600'
                                : 'bg-amber-50 text-amber-600' }}"
                        >
                            {{ $trainingReady ? 'Siap' : 'Belum Siap' }}
                        </span>
                    </div>

                    <div class="mt-6">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-500">
                                Data layak / minimum
                            </span>

                            <span class="font-semibold text-slate-700">
                                {{ $eligibleDataset }} / {{ $minimumDataset }}
                            </span>
                        </div>

                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full transition-all
                                {{ $trainingReady
                                    ? 'bg-emerald-500'
                                    : 'bg-blue-500' }}"
                                style="width: {{ $readinessPercentage }}%"
                            ></div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                            <span class="text-xs text-slate-500">
                                IPS Semester 1–5 lengkap
                            </span>

                            <span class="text-xs font-semibold text-slate-700">
                                {{ $completeIpsDataset }} data
                            </span>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                            <span class="text-xs text-slate-500">
                                Memiliki target IPK aktual
                            </span>

                            <span class="text-xs font-semibold text-emerald-600">
                                {{ $eligibleDataset }} data
                            </span>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                            <span class="text-xs text-slate-500">
                                IPS lengkap tanpa target IPK
                            </span>

                            <span class="text-xs font-semibold text-amber-600">
                                {{ $withoutActualIpk }} data
                            </span>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                            <span class="text-xs text-slate-500">
                                Data IPS belum lengkap
                            </span>

                            <span class="text-xs font-semibold text-red-500">
                                {{ $incompleteIpsDataset }} data
                            </span>
                        </div>
                    </div>

                    @unless ($trainingReady)
                        <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-xs leading-5 text-amber-700">
                                Tambahkan minimal
                                <strong>{{ $requiredAdditional }}</strong>
                                data mahasiswa lagi yang memiliki IPS Semester
                                1–5 lengkap dan IPK akhir aktual.
                            </p>

                            <a
                                href="{{ route('admin.data-ips.index') }}"
                                class="mt-3 inline-flex text-xs font-semibold text-amber-700 underline underline-offset-4"
                            >
                                Kelola Data IPS
                            </a>
                        </div>
                    @else
                        <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                            <p class="text-xs leading-5 text-emerald-700">
                                Dataset telah memenuhi batas minimum. Proses
                                training ANN dapat dijalankan.
                            </p>
                        </div>
                    @endunless
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-base font-semibold text-slate-800">
                        Model Aktif
                    </h2>

                    @if ($activeModel)
                        <div class="mt-5">
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 p-4">
                                <div>
                                    <p class="text-xs text-slate-400">
                                        Arsitektur
                                    </p>

                                    <p class="mt-1 text-lg font-bold text-slate-800">
                                        {{ $activeModel->architectureLabel() }}
                                    </p>
                                </div>

                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                    Aktif
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        MAE Testing
                                    </p>

                                    <p class="mt-2 text-xl font-bold text-blue-600">
                                        {{ $activeModel->mae !== null
                                            ? number_format((float) $activeModel->mae, 4)
                                            : '-' }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        MSE Testing
                                    </p>

                                    <p class="mt-2 text-xl font-bold text-violet-600">
                                        {{ $activeModel->mse !== null
                                            ? number_format((float) $activeModel->mse, 4)
                                            : '-' }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        Data Training
                                    </p>

                                    <p class="mt-2 text-xl font-bold text-slate-700">
                                        {{ number_format($activeModel->train_count) }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 p-4">
                                    <p class="text-xs text-slate-400">
                                        Data Testing
                                    </p>

                                    <p class="mt-2 text-xl font-bold text-slate-700">
                                        {{ number_format($activeModel->test_count) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 border-t border-slate-100 pt-4">
                                <p class="text-xs text-slate-400">
                                    Dilatih oleh
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-700">
                                    {{ $activeModel->trainedBy?->name
                                        ?? $activeModel->trainedBy?->username
                                        ?? 'Administrator' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $activeModel->training_finished_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
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

                            <p class="mt-1 text-xs text-slate-400">
                                Lengkapi dataset dan jalankan training.
                            </p>
                        </div>
                    @endif
                </section>
            </div>
        </div>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Riwayat Training Model
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Daftar model ANN yang pernah dilatih.
                        </p>
                    </div>

                    <span class="text-xs text-slate-400">
                        {{ number_format($models->total()) }} model
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10px] uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4 font-semibold">Model</th>
                            <th class="px-5 py-4 font-semibold">Arsitektur</th>
                            <th class="px-5 py-4 text-center font-semibold">Training</th>
                            <th class="px-5 py-4 text-center font-semibold">Testing</th>
                            <th class="px-5 py-4 text-center font-semibold">MAE</th>
                            <th class="px-5 py-4 text-center font-semibold">MSE</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Waktu</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($models as $model)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-xs font-bold text-blue-600">
                                            #{{ $model->id }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">
                                                ANN Prediksi IPK
                                            </p>

                                            <p class="mt-1 max-w-40 truncate text-[10px] text-slate-400">
                                                {{ $model->uuid }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ $model->architectureLabel() }}
                                    </span>

                                    <p class="mt-1 text-[10px] text-slate-400">
                                        Input · Hidden · Output
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-slate-600">
                                    {{ number_format($model->train_count) }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-slate-600">
                                    {{ number_format($model->test_count) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-bold text-blue-600">
                                        {{ $model->mae !== null
                                            ? number_format((float) $model->mae, 4)
                                            : '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-bold text-violet-600">
                                        {{ $model->mse !== null
                                            ? number_format((float) $model->mse, 4)
                                            : '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($model->is_active)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                            Arsip
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-xs font-medium text-slate-600">
                                        {{ $model->training_finished_at?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-[10px] text-slate-400">
                                        {{ $model->training_finished_at?->format('H:i') ?? '-' }}
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
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <circle cx="5" cy="7" r="2"></circle>
                                            <circle cx="19" cy="7" r="2"></circle>
                                            <path d="m7 8 3 3"></path>
                                            <path d="m17 8-3 3"></path>
                                        </svg>
                                    </div>

                                    <p class="mt-4 text-sm font-semibold text-slate-600">
                                        Belum ada riwayat model
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Riwayat akan muncul setelah training pertama.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($models->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $models->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('training-form');
            const button = document.querySelector('[data-training-button]');

            if (!form || !button) {
                return;
            }

            form.addEventListener('submit', async event => {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();

                let confirmed = false;

                if (window.Swal) {
                    const result = await window.Swal.fire({
                        icon: 'question',
                        title: 'Mulai Training ANN?',
                        html: 'Model akan dilatih menggunakan dataset yang memiliki <strong>IPS Semester 1–5 dan IPK akhir aktual</strong>.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Mulai Training',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#64748b',
                        reverseButtons: true,
                    });

                    confirmed = result.isConfirmed;
                } else {
                    confirmed = window.confirm(
                        'Mulai proses training ANN?'
                    );
                }

                if (!confirmed) {
                    return;
                }

                form.dataset.confirmed = 'true';
                button.disabled = true;

                button.innerHTML = `
                    <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
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
                    Memproses Training...
                `;

                form.submit();
            });
        });
    </script>
@endpush