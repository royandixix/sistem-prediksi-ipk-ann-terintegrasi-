@extends('layouts.operator')

@section('title', 'Profil Operator')

@section('content')
    @php
        $initial = strtoupper(substr($user->name ?? 'O', 0, 1));

        $roleValue =
            $user->role instanceof \BackedEnum
                ? $user->role->value
                : ($user->role instanceof \UnitEnum
                    ? $user->role->name
                    : (string) ($user->role ?? 'operator'));

        $roleLabel = match ($roleValue) {
            'admin' => 'Administrator',
            'operator' => 'Operator',
            'administrator' => 'Administrator',
            'user' => 'Pengguna',
            default => ucwords(str_replace(['_', '-'], ' ', $roleValue)),
        };
    @endphp

    <div class="space-y-5">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <div class="flex items-center gap-2 text-[10px] text-slate-400">
                    <a href="{{ route('operator.dashboard') }}" class="transition hover:text-blue-600">
                        Dashboard
                    </a>

                    <span>/</span>
                    <span>Profil</span>
                </div>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
                    Profil Operator
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Kelola informasi akun dan keamanan password operator.
                </p>
            </div>

            <div class="inline-flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                <span class="relative flex h-3 w-3">
                    <span
                        class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-50"></span>
                    <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                </span>

                <div>
                    <p class="text-xs font-semibold text-emerald-700">
                        Akun Aktif
                    </p>

                    <p class="mt-0.5 text-[11px] text-emerald-600">
                        Login sebagai Operator
                    </p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="m8 12 3 3 5-6"></path>
                </svg>

                <div>
                    <p class="text-sm font-semibold text-emerald-700">
                        Profil berhasil diperbarui
                    </p>

                    <p class="mt-1 text-xs text-emerald-600">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif
        @if (session('warning'))
            <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 8v5"></path>
                    <path d="M12 17h.01"></path>
                </svg>

                <div>
                    <p class="text-sm font-semibold text-amber-700">
                        Data tidak dapat disimpan
                    </p>

                    <p class="mt-1 text-xs text-amber-600">
                        {{ session('warning') }}
                    </p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 8v5"></path>
                    <path d="M12 17h.01"></path>
                </svg>

                <div>
                    <p class="text-sm font-semibold text-red-700">
                        Data belum dapat disimpan
                    </p>

                    <ul class="mt-2 space-y-1 text-xs text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid gap-5 xl:grid-cols-[320px_minmax(0,1fr)]">
            <div class="space-y-5">
                <section class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <div
                        class="mx-auto grid h-24 w-24 place-items-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-3xl font-bold text-white shadow-lg shadow-blue-600/20">
                        {{ $initial }}
                    </div>

                    <h2 class="mt-5 text-lg font-bold text-slate-800">
                        {{ $user->name ?? 'Operator' }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        {{ $user->email ?? '-' }}
                    </p>

                    <span
                        class="mt-4 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7Z"></path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>

                        {{ $roleLabel }}
                    </span>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-800">
                        Informasi Akun
                    </h2>

                    <div class="mt-5 space-y-4">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-400">
                                ID Pengguna
                            </span>

                            <span class="text-xs font-semibold text-slate-700">
                                #{{ $user->id }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-400">
                                Peran
                            </span>

                            <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-600">
                                {{ $roleLabel }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-400">
                                Bergabung
                            </span>

                            <span class="text-xs font-semibold text-slate-700">
                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-400">
                                Terakhir diperbarui
                            </span>

                            <span class="text-xs font-semibold text-slate-700">
                                {{ $user->updated_at?->format('d M Y, H:i') ?? '-' }}
                            </span>
                        </div>
                    </div>
                </section>
            </div>

            <form
                id="profile-form"
                action="{{ route('operator.profil.update') }}"
                method="POST"
                class="space-y-5"
                data-original-name="{{ $user->name }}"
                data-original-email="{{ $user->email }}"
            >
                @csrf
                @method('PUT')

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-base font-semibold text-slate-800">
                                    Informasi Profil
                                </h2>

                                <p class="mt-1 text-xs text-slate-400">
                                    Perbarui nama dan alamat email operator.
                                </p>
                            </div>

                            <div class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path d="M4 21a8 8 0 0 1 16 0"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5 p-6 md:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                required autocomplete="name" placeholder="Masukkan nama lengkap"
                                class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4
                                @error('name')
                                    border-red-300 focus:border-red-400 focus:ring-red-50
                                @else
                                    border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                @enderror">

                            @error('name')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                Alamat Email
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required autocomplete="email"
                                placeholder="operator@example.com"
                                class="h-12 w-full rounded-xl border bg-white px-4 text-sm text-slate-700 outline-none transition focus:ring-4
                                @error('email')
                                    border-red-300 focus:border-red-400 focus:ring-red-50
                                @else
                                    border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                @enderror">

                            @error('email')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-base font-semibold text-slate-800">
                                    Keamanan Password
                                </h2>

                                <p class="mt-1 text-xs text-slate-400">
                                    Kosongkan bagian ini apabila tidak mengganti password.
                                </p>
                            </div>

                            <div class="grid h-10 w-10 place-items-center rounded-xl bg-violet-50 text-violet-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5 p-6">
                        <div>
                            <label for="current_password" class="mb-2 block text-sm font-semibold text-slate-700">
                                Password Saat Ini
                            </label>

                            <div class="relative">
                                <input type="password" id="current_password" name="current_password"
                                    autocomplete="current-password" placeholder="Masukkan password saat ini"
                                    class="h-12 w-full rounded-xl border bg-white px-4 pr-12 text-sm text-slate-700 outline-none transition focus:ring-4
                                    @error('current_password')
                                        border-red-300 focus:border-red-400 focus:ring-red-50
                                    @else
                                        border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                    @enderror">

                                <button type="button" data-toggle-password="current_password"
                                    class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-400 transition hover:text-blue-600"
                                    aria-label="Tampilkan password">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>

                            @error('current_password')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Password Baru
                                </label>

                                <div class="relative">
                                    <input type="password" id="password" name="password" autocomplete="new-password"
                                        placeholder="Minimal 8 karakter"
                                        class="h-12 w-full rounded-xl border bg-white px-4 pr-12 text-sm text-slate-700 outline-none transition focus:ring-4
                                        @error('password')
                                            border-red-300 focus:border-red-400 focus:ring-red-50
                                        @else
                                            border-slate-200 focus:border-blue-400 focus:ring-blue-50
                                        @enderror">

                                    <button type="button" data-toggle-password="password"
                                        class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-400 transition hover:text-blue-600"
                                        aria-label="Tampilkan password">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="mt-2 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="mb-2 block text-sm font-semibold text-slate-700">
                                    Konfirmasi Password Baru
                                </label>

                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        autocomplete="new-password" placeholder="Ulangi password baru"
                                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 pr-12 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50">

                                    <button type="button" data-toggle-password="password_confirmation"
                                        class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-400 transition hover:text-blue-600"
                                        aria-label="Tampilkan password">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-amber-100 bg-amber-50 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 8v5"></path>
                                    <path d="M12 17h.01"></path>
                                </svg>

                                <p class="text-xs leading-5 text-amber-700">
                                    Gunakan kombinasi huruf besar, huruf kecil,
                                    angka, dan simbol untuk meningkatkan keamanan akun.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('operator.dashboard') }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 4h12l2 2v14H5Z"></path>
                            <path d="M8 4v6h8V4"></path>
                            <path d="M8 20v-6h8v6"></path>
                        </svg>

                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document
                .querySelectorAll('[data-toggle-password]')
                .forEach(button => {
                    button.addEventListener('click', () => {
                        const input = document.getElementById(
                            button.dataset.togglePassword
                        );

                        if (!input) {
                            return;
                        }

                        input.type =
                            input.type === 'password' ?
                            'text' :
                            'password';
                    });
                });

            const form = document.getElementById('profile-form');

            if (!form) {
                return;
            }

            form.addEventListener('submit', event => {
                const name = document
                    .getElementById('name')
                    .value
                    .trim();

                const email = document
                    .getElementById('email')
                    .value
                    .trim();

                const currentPassword = document
                    .getElementById('current_password')
                    .value;

                const password = document
                    .getElementById('password')
                    .value;

                const confirmation = document
                    .getElementById('password_confirmation')
                    .value;

                const originalName =
                    form.dataset.originalName.trim();

                const originalEmail =
                    form.dataset.originalEmail
                    .trim()
                    .toLowerCase();

                if (!name || !email) {
                    event.preventDefault();

                    window.alert(
                        'Data tidak dapat disimpan. Nama dan email wajib diisi.'
                    );

                    return;
                }

                if (
                    !email.includes('@') ||
                    !email.includes('.')
                ) {
                    event.preventDefault();

                    window.alert(
                        'Data tidak dapat disimpan. Format email tidak valid.'
                    );

                    return;
                }

                if (
                    currentPassword &&
                    !password
                ) {
                    event.preventDefault();

                    window.alert(
                        'Masukkan password baru terlebih dahulu.'
                    );

                    return;
                }

                if (
                    password &&
                    !currentPassword
                ) {
                    event.preventDefault();

                    window.alert(
                        'Password saat ini wajib diisi untuk mengganti password.'
                    );

                    return;
                }

                if (
                    password &&
                    password.length < 8
                ) {
                    event.preventDefault();

                    window.alert(
                        'Password baru minimal 8 karakter.'
                    );

                    return;
                }

                if (
                    password !== confirmation
                ) {
                    event.preventDefault();

                    window.alert(
                        'Konfirmasi password baru tidak sesuai.'
                    );

                    return;
                }

                const profileChanged =
                    name !== originalName ||
                    email.toLowerCase() !== originalEmail;

                const passwordChanged =
                    password.length > 0;

                if (
                    !profileChanged &&
                    !passwordChanged
                ) {
                    event.preventDefault();

                    window.alert(
                        'Tidak ada perubahan data yang dapat disimpan.'
                    );
                }
            });
        });
    </script>
@endpush
