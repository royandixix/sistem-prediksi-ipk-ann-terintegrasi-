@php
    $isEdit = isset($dataIps);

    $selectedMahasiswaId = old('mahasiswa_id', $dataIps->mahasiswa_id ?? ($selectedMahasiswa?->id ?? ''));
@endphp

<div class="space-y-7">
    <section>
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-slate-800">
                Identitas Mahasiswa
            </h3>

            <p class="mt-1 text-xs text-slate-400">
                Pilih mahasiswa yang akan dimasukkan nilai IPS-nya.
            </p>
        </div>

        @if ($isEdit)
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center gap-4">
                    <div
                        class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-blue-100 text-sm font-bold text-blue-600">
                        {{ strtoupper(substr($dataIps->mahasiswa->nama, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ $dataIps->mahasiswa->nama }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            NIM {{ $dataIps->mahasiswa->nim }}
                            · Angkatan {{ $dataIps->mahasiswa->angkatan }}
                        </p>
                    </div>
                </div>

                <input type="hidden" name="mahasiswa_id" value="{{ $dataIps->mahasiswa_id }}">
            </div>
        @else
            <div>
                <label for="mahasiswa_id" class="mb-2 block text-sm font-semibold text-slate-700">
                    Mahasiswa
                    <span class="text-red-500">*</span>
                </label>

                <select id="mahasiswa_id" name="mahasiswa_id" required
                    class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4
                    @error('mahasiswa_id')
                        border-red-300 focus:border-red-400 focus:ring-red-100
                    @else
                        border-slate-200 focus:border-blue-400 focus:ring-blue-100
                    @enderror">
                    <option value="">
                        Pilih mahasiswa
                    </option>

                    @foreach ($mahasiswas as $mahasiswa)
                        <option value="{{ $mahasiswa->id }}" @selected((string) $selectedMahasiswaId === (string) $mahasiswa->id)>
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
                    <p class="mt-2 text-xs text-amber-600">
                        Semua mahasiswa aktif sudah memiliki Data IPS.
                    </p>
                @endif
            </div>
        @endif
    </section>

    <div class="border-t border-slate-100"></div>

    <section>
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">
                    Nilai IPS Semester 1–5
                </h3>

                <p class="mt-1 text-xs text-slate-400">
                    Masukkan nilai dengan rentang 0.00 sampai 4.00.
                </p>
            </div>

            <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-right">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-500">
                    Rata-rata IPS
                </p>

                <p data-ips-average class="mt-1 text-xl font-bold text-blue-700">
                    {{ isset($dataIps) ? number_format($dataIps->averageIps(), 3) : '0.000' }}
                </p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ([
        'ips_1' => 'Semester 1',
        'ips_2' => 'Semester 2',
        'ips_3' => 'Semester 3',
        'ips_4' => 'Semester 4',
        'ips_5' => 'Semester 5',
    ] as $field => $label)
                <div>
                    <label for="{{ $field }}" class="mb-2 block text-xs font-semibold text-slate-700">
                        IPS {{ $label }}
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="number" id="{{ $field }}" name="{{ $field }}"
                        value="{{ old($field, $dataIps->{$field} ?? '') }}" min="0" max="4" step="0.01"
                        inputmode="decimal" required data-ips-input placeholder="0.00"
                        class="h-12 w-full rounded-xl border bg-white px-4 text-center text-base font-semibold text-slate-700 outline-none transition placeholder:text-slate-300 focus:ring-4
                        @error($field)
                            border-red-300 focus:border-red-400 focus:ring-red-100
                        @else
                            border-slate-200 focus:border-blue-400 focus:ring-blue-100
                        @enderror">

                    @error($field)
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endforeach
        </div>
    </section>

    <div class="border-t border-slate-100"></div>

    <section class="grid gap-5 lg:grid-cols-2">
        <div>
            <label for="ipk_akhir_aktual" class="mb-2 block text-sm font-semibold text-slate-700">
                IPK Akhir Aktual
                <span class="ml-1 text-xs font-normal text-slate-400">
                    Opsional
                </span>
            </label>

            <input type="number" id="ipk_akhir_aktual" name="ipk_akhir_aktual"
                value="{{ old('ipk_akhir_aktual', $dataIps->ipk_akhir_aktual ?? '') }}"
                min="0" max="4" step="0.001" inputmode="decimal" placeholder="Contoh: 3.650"
                class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:ring-4
                @error('ipk_akhir_aktual')
                    border-red-300 focus:border-red-400 focus:ring-red-100
                @else
                    border-slate-200 focus:border-blue-400 focus:ring-blue-100
                @enderror">

            <p class="mt-2 text-xs leading-5 text-slate-400">
                Diisi untuk data historis yang digunakan sebagai target training dan testing ANN.
            </p>

            @error('ipk_akhir_aktual')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="catatan" class="mb-2 block text-sm font-semibold text-slate-700">
                Catatan
                <span class="ml-1 text-xs font-normal text-slate-400">
                    Opsional
                </span>
            </label>

            <textarea id="catatan" name="catatan" rows="4" maxlength="1000"
                placeholder="Tambahkan catatan mengenai data akademik..."
                class="min-h-28 w-full resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-100">{{ old('catatan', $dataIps->catatan ?? '') }}</textarea>

            @error('catatan')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </section>
</div>

<div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
    <a href="{{ isset($dataIps) ? route('admin.data-ips.show', $dataIps) : route('admin.data-ips.index') }}"
        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
        Batal
    </a>

    <button type="submit" @disabled(!$isEdit && $mahasiswas->isEmpty())
        class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14"></path>
            <path d="m13 6 6 6-6 6"></path>
        </svg>

        {{ $submitLabel }}
    </button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = [...document.querySelectorAll('[data-ips-input]')];
            const averageElement = document.querySelector('[data-ips-average]');

            const calculateAverage = () => {
                if (!averageElement) return;

                const values = inputs
                    .map(input => Number.parseFloat(input.value))
                    .filter(value => Number.isFinite(value));

                if (values.length !== 5) {
                    averageElement.textContent = '0.000';
                    return;
                }

                const average = values.reduce((total, value) => total + value, 0) / values.length;
                averageElement.textContent = average.toFixed(3);
            };

            inputs.forEach(input => {
                input.addEventListener('input', calculateAverage);
            });

            calculateAverage();
        });
    </script>
@endpush
