@php
    $isEdit = isset($mahasiswa);
    $statusValue = old('status', $mahasiswa->status ?? 'aktif');
    $statusLabel = $statusValue === 'aktif' ? 'Aktif' : 'Nonaktif';
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="nim" class="mb-2 block text-sm font-semibold text-slate-700">
            NIM Mahasiswa
            <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="nim"
            name="nim"
            value="{{ old('nim', $mahasiswa->nim ?? '') }}"
            maxlength="30"
            autocomplete="off"
            placeholder="Contoh: 222373"
            required
            class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:ring-4
            @error('nim')
                border-red-300 focus:border-red-400 focus:ring-red-100
            @else
                border-slate-200 focus:border-blue-400 focus:ring-blue-100
            @enderror"
        >

        @error('nim')
            <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600">
                <svg
                    class="h-4 w-4 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 8v5"></path>
                    <path d="M12 17h.01"></path>
                </svg>

                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label for="nama" class="mb-2 block text-sm font-semibold text-slate-700">
            Nama Lengkap
            <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="nama"
            name="nama"
            value="{{ old('nama', $mahasiswa->nama ?? '') }}"
            maxlength="150"
            autocomplete="name"
            placeholder="Masukkan nama lengkap mahasiswa"
            required
            class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:ring-4
            @error('nama')
                border-red-300 focus:border-red-400 focus:ring-red-100
            @else
                border-slate-200 focus:border-blue-400 focus:ring-blue-100
            @enderror"
        >

        @error('nama')
            <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600">
                <svg
                    class="h-4 w-4 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 8v5"></path>
                    <path d="M12 17h.01"></path>
                </svg>

                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <div class="mb-2 flex items-center justify-between gap-3">
            <label for="angkatan" class="block text-sm font-semibold text-slate-700">
                Angkatan
                <span class="text-red-500">*</span>
            </label>

            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-semibold text-amber-700">
                <svg
                    class="h-3 w-3"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                </svg>

                Dikunci
            </span>
        </div>

        <div class="relative">
            <input
                type="number"
                id="angkatan"
                name="angkatan"
                value="{{ old('angkatan', $mahasiswa->angkatan ?? 2023) }}"
                readonly
                aria-readonly="true"
                class="h-12 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 pr-11 text-sm font-semibold text-slate-600 outline-none"
            >

            <svg
                class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
            </svg>
        </div>

        <p class="mt-2 text-xs leading-5 text-slate-400">
            Penelitian hanya menggunakan mahasiswa angkatan 2023.
        </p>

        @error('angkatan')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <div class="mb-2 flex items-center justify-between gap-3">
            <label for="program_studi" class="block text-sm font-semibold text-slate-700">
                Program Studi
                <span class="text-red-500">*</span>
            </label>

            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-semibold text-amber-700">
                <svg
                    class="h-3 w-3"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                </svg>

                Dikunci
            </span>
        </div>

        <div class="relative">
            <input
                type="text"
                id="program_studi"
                name="program_studi"
                value="{{ old('program_studi', $mahasiswa->program_studi ?? 'Teknik Informatika') }}"
                readonly
                aria-readonly="true"
                class="h-12 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 pr-11 text-sm font-semibold text-slate-600 outline-none"
            >

            <svg
                class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
            </svg>
        </div>

        <p class="mt-2 text-xs leading-5 text-slate-400">
            Program studi penelitian ditetapkan sebagai Teknik Informatika.
        </p>

        @error('program_studi')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <div class="mb-2 flex items-center justify-between gap-3">
            <label for="status_display" class="block text-sm font-semibold text-slate-700">
                Status Mahasiswa
                <span class="text-red-500">*</span>
            </label>

            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-semibold text-amber-700">
                <svg
                    class="h-3 w-3"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                </svg>

                Dikunci
            </span>
        </div>

        <div class="relative">
            <input
                type="text"
                id="status_display"
                value="{{ $statusLabel }}"
                readonly
                aria-readonly="true"
                class="h-12 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 pr-11 text-sm font-semibold text-slate-600 outline-none"
            >

            <input
                type="hidden"
                id="status"
                name="status"
                value="{{ $statusValue }}"
            >

            <svg
                class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2
                {{ $statusValue === 'aktif' ? 'text-emerald-500' : 'text-slate-400' }}"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                @if($statusValue === 'aktif')
                    <path d="M20 6 9 17l-5-5"></path>
                @else
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M8 12h8"></path>
                @endif
            </svg>
        </div>

        <p class="mt-2 text-xs leading-5 text-slate-400">
            @if($isEdit)
                Status mahasiswa dipertahankan sesuai data yang sudah tersimpan.
            @else
                Mahasiswa baru otomatis ditetapkan berstatus aktif.
            @endif
        </p>

        @error('status')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>
</div>

<div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
    <a
        href="{{ isset($mahasiswa)
            ? route('admin.mahasiswa.show', $mahasiswa)
            : route('admin.mahasiswa.index') }}"
        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50"
    >
        Batal
    </a>

    <button
        type="submit"
        class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700"
    >
        <svg
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        >
            <path d="M5 12h14"></path>
            <path d="m13 6 6 6-6 6"></path>
        </svg>

        {{ $submitLabel }}
    </button>
</div>  