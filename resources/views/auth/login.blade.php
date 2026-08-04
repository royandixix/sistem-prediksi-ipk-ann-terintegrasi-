@extends('layouts.guest')
@section('title', 'Login | Sistem Prediksi IPK')
@section('content')
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#0f6b5b] px-4 py-10 sm:px-6">
        <div
            class="pointer-events-none absolute -left-40 -top-40 h-[32rem] w-[32rem] rounded-full bg-emerald-300/10 blur-3xl">
        </div>
        <div
            class="pointer-events-none absolute -bottom-48 -right-40 h-[36rem] w-[36rem] rounded-full bg-teal-950/20 blur-3xl">
        </div>
        <div
            class="pointer-events-none absolute left-1/2 top-1/2 h-80 w-80 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/5 blur-3xl">
        </div>
        <div class="relative z-10 w-full max-w-[380px]">
            <div class="mb-7 text-center text-white">
                <div
                    class="mx-auto grid h-16 w-16 place-items-center rounded-2xl border border-white/20 bg-white text-xl font-bold text-[#0f6b5b] shadow-xl shadow-teal-950/20">
                    U</div>
                <h1 class="mt-4 text-xl font-semibold tracking-tight">Sistem Prediksi IPK</h1>
                <p class="mt-1 text-sm text-emerald-50/70">Universitas Dipa Makassar</p>
            </div>
            <div class="overflow-hidden shadow-[0_25px_60px_rgba(0,0,0,0.28)]">
                <div class="flex min-h-14 items-center justify-between bg-[#202020] px-6 text-white">
                    <h2 class="text-sm font-medium uppercase tracking-[0.08em]">Login Sistem</h2>
                    <button id="login-help" type="button"
                        class="grid h-8 w-8 place-items-center rounded-full border border-white/20 text-sm font-semibold text-white/75 transition hover:border-white/40 hover:bg-white/10 hover:text-white"
                        aria-label="Petunjuk login">?</button>
                </div>
                <div class="bg-[#e7ebef] px-6 pb-8 pt-6">
                    <div class="mb-6 border-l-4 border-[#0f6b5b] bg-white/70 px-4 py-3">
                        <p class="text-sm font-semibold text-slate-700">Selamat datang</p>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Masukkan username dan password yang diberikan
                            administrator untuk mengakses sistem.</p>
                    </div>
                    <form action="{{ route('login.store') }}" method="POST" data-login-form novalidate class="space-y-5">
                        @csrf
                        <div>
                            <label for="username"
                                class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-600">Username</label>
                            <div class="relative">
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex w-12 items-center justify-center text-[#676767]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm-8 9a8 8 0 0 1 16 0H4Z" />
                                    </svg>
                                </div>
                                <input id="username" type="text" name="username" value="{{ old('username') }}"
                                    placeholder="Masukkan username" autocomplete="username" autocapitalize="none"
                                    autocorrect="off" spellcheck="false" autofocus maxlength="50"
                                    class="h-12 w-full border border-white bg-white pl-12 pr-4 text-[15px] text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15">
                            </div>
                        </div>
                        <div>
                            <label for="password"
                                class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-600">Password</label>
                            <div class="relative">
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex w-12 items-center justify-center text-[#676767]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2Zm-7-2a2 2 0 0 1 4 0v2h-4V6Zm3 9.73V18h-2v-2.27a2 2 0 1 1 2 0Z" />
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" placeholder="Masukkan password"
                                    autocomplete="current-password" maxlength="255"
                                    class="h-12 w-full border border-white bg-white pl-12 pr-12 text-[15px] text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15">
                                <button id="toggle-password" type="button"
                                    class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition hover:text-slate-700"
                                    aria-label="Tampilkan password">
                                    <svg id="eye-open" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                                        <circle cx="12" cy="12" r="2.5"></circle>
                                    </svg>
                                    <svg id="eye-closed" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path d="m3 3 18 18"></path>
                                        <path d="M10.6 6.2A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a17 17 0 0 1-2.1 2.8"></path>
                                        <path d="M6.2 6.2C3.8 8 2.5 12 2.5 12s3.5 6 9.5 6a9.8 9.8 0 0 0 3.3-.6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 text-xs text-[#666666]">
                            <label for="remember" class="flex cursor-pointer items-center gap-2">
                                <input id="remember" type="checkbox" name="remember" value="1"
                                    @checked(old('remember'))
                                    class="h-4 w-4 rounded-sm border-slate-300 text-[#0f6b5b] focus:ring-[#0f6b5b]">
                                <span>Ingat saya</span>
                            </label>
                            <button id="account-help" type="button"
                                class="font-medium text-[#0f6b5b] transition hover:text-[#0b584b]">Tidak dapat
                                masuk?</button>
                        </div>
                        <button type="submit" data-login-submit
                            class="flex h-12 w-full items-center justify-center gap-2 bg-[#f25555] text-sm font-medium uppercase tracking-[0.06em] text-white transition hover:bg-[#e84949] focus:outline-none focus:ring-4 focus:ring-red-400/20 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-70">
                            <svg data-login-spinner class="hidden h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z">
                                </path>
                            </svg>
                            <span data-login-text>Masuk</span>
                        </button>
                    </form>
                </div>
                <div class="flex min-h-24 items-center justify-between gap-4 bg-[#f0f2f4] px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold text-[#555555]">Akses terbatas</p>
                        <p class="mt-1 text-[11px] leading-4 text-[#777777]">Akun dibuat dan dikelola oleh administrator.
                        </p>
                    </div>
                    <button id="contact-admin" type="button"
                        class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 bg-[#0f6b5b] px-4 text-xs font-medium text-white transition hover:bg-[#0b584b]">Hubungi
                        Admin</button>
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-emerald-50/60">&copy; {{ date('Y') }} Universitas Dipa Makassar</p>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-login-form]');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            const submitButton = document.querySelector('[data-login-submit]');
            const submitText = document.querySelector('[data-login-text]');
            const submitSpinner = document.querySelector('[data-login-spinner]');
            const loginHelp = document.getElementById('login-help');
            const accountHelp = document.getElementById('account-help');
            const contactAdmin = document.getElementById('contact-admin');
            const serverErrors = @json($errors->all());
            const successMessage = @json(session('success'));
            const errorMessage = @json(session('error'));
            const warningMessage = @json(session('warning'));

            const escapeHtml = value => {
                const element = document.createElement('div');
                element.textContent = String(value);
                return element.innerHTML;
            };

            const showErrors = (title, errors, icon = 'warning') => {
                const items = errors.map(error => `<li style="margin-bottom:6px">${escapeHtml(error)}</li>`)
                    .join('');
                return Swal.fire({
                    icon,
                    title,
                    html: `<ul style="margin:0;padding-left:20px;text-align:left;font-size:14px;line-height:1.5">${items}</ul>`,
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#0f6b5b',
                    allowOutsideClick: false
                });
            };

            if (serverErrors.length > 0) {
                showErrors('Login Tidak Berhasil', serverErrors, 'error');
            } else if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Proses Gagal',
                    text: errorMessage,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#0f6b5b'
                });
            } else if (warningMessage) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: warningMessage,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#0f6b5b'
                });
            } else if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonText: 'Lanjutkan',
                    confirmButtonColor: '#0f6b5b',
                    timer: 2200,
                    timerProgressBar: true
                });
            }

            toggleButton?.addEventListener('click', () => {
                const isVisible = passwordInput.type === 'text';
                passwordInput.type = isVisible ? 'password' : 'text';
                eyeOpen.classList.toggle('hidden', !isVisible);
                eyeClosed.classList.toggle('hidden', isVisible);
                toggleButton.setAttribute('aria-label', isVisible ? 'Tampilkan password' :
                    'Sembunyikan password');
            });

            loginHelp?.addEventListener('click', () => {
                Swal.fire({
                    icon: 'info',
                    title: 'Petunjuk Login',
                    html: '<div style="text-align:left;font-size:14px;line-height:1.7"><p>Gunakan username dan password yang diberikan administrator.</p><p>Username admin adalah <strong>admin</strong>, bukan alamat email.</p><p>Pastikan tidak terdapat spasi tambahan.</p><p>Role pengguna ditentukan otomatis oleh sistem.</p></div>',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#0f6b5b'
                });
            });

            const showAdminInformation = () => {
                Swal.fire({
                    icon: 'info',
                    title: 'Hubungi Administrator',
                    text: 'Pembuatan akun, aktivasi akun, dan perubahan password hanya dapat dilakukan oleh administrator sistem.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#0f6b5b'
                });
            };

            accountHelp?.addEventListener('click', showAdminInformation);
            contactAdmin?.addEventListener('click', showAdminInformation);

            form?.addEventListener('submit', event => {
                let username = usernameInput.value;
                const password = passwordInput.value;
                const errors = [];

                username = username.normalize('NFKC');
                username = username.replace(/[\u200B-\u200D\uFEFF]/g, '');
                username = username.trim().toLowerCase();
                usernameInput.value = username;

                if (username === '') {
                    errors.push('Username wajib diisi.');
                } else {
                    if (username.length < 4) errors.push('Username minimal terdiri dari 4 karakter.');
                    if (username.length > 50) errors.push('Username maksimal terdiri dari 50 karakter.');
                    if (!/^[a-z0-9._-]+$/.test(username)) errors.push(
                        'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan tanda hubung.'
                        );
                }

                if (password === '') {
                    errors.push('Password wajib diisi.');
                } else {
                    if (password.length < 8) errors.push('Password minimal terdiri dari 8 karakter.');
                    if (password.length > 255) errors.push('Password maksimal terdiri dari 255 karakter.');
                }

                if (errors.length > 0) {
                    event.preventDefault();
                    showErrors('Periksa Input Login', errors).then(() => {
                        if (username === '') usernameInput.focus();
                        else passwordInput.focus();
                    });
                    return;
                }

                submitButton.disabled = true;
                submitSpinner.classList.remove('hidden');
                submitText.textContent = 'Memproses...';
            });
        });
    </script>
@endpush
