@php
    $user = auth()->user();
    $role = $user?->role;
    $roleLabel = $role instanceof \App\Enums\UserRole ? $role->label() : ucfirst((string) $role);
@endphp

<header class="sticky top-0 z-30 h-16 border-b border-slate-200 bg-white">
    <div class="flex h-full items-center justify-between gap-4 px-4 sm:px-6 lg:px-7">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <button type="button" data-sidebar-toggle
                class="grid h-10 w-10 shrink-0 place-items-center rounded-lg text-slate-500 transition hover:bg-slate-100 lg:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 18h16"></path>
                </svg>
            </button>

            <div class="relative hidden w-full max-w-sm sm:block">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-3.5-3.5"></path>
                </svg>

                <input type="search" placeholder="Cari menu..."
                    class="h-10 w-full rounded-lg border border-transparent bg-slate-100 pl-10 pr-4 text-xs text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-200 focus:bg-white focus:ring-4 focus:ring-blue-50">
            </div>
        </div>

        <div class="relative flex shrink-0 items-center gap-3">
            <button type="button"
                class="relative grid h-10 w-10 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                    <path d="M10 21h4"></path>
                </svg>
                <span class="absolute right-2.5 top-2 h-1.5 w-1.5 rounded-full bg-red-500 ring-2 ring-white"></span>
            </button>

            <div class="h-7 w-px bg-slate-200"></div>

            <button type="button" data-user-menu-toggle
                class="flex items-center gap-3 rounded-lg px-1.5 py-1 transition hover:bg-slate-50">
                <div
                    class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">
                    {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                </div>

                <div class="hidden min-w-0 text-left sm:block">
                    <p class="max-w-36 truncate text-xs font-semibold text-slate-700">
                        {{ $user?->name }}
                    </p>
                    <p class="mt-0.5 text-[10px] text-slate-400">
                        {{ $roleLabel }}
                    </p>
                </div>

                <svg class="hidden h-3.5 w-3.5 text-slate-400 sm:block" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"></path>
                </svg>
            </button>

            <div data-user-menu
                class="absolute right-0 top-full mt-3 hidden w-60 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                <div class="border-b border-slate-100 px-4 py-4">
                    <p class="truncate text-sm font-semibold text-slate-800">
                        {{ $user?->name }}
                    </p>
                    <p class="mt-1 truncate text-xs text-slate-400">
                        {{ $user?->email }}
                    </p>
                </div>

                <div class="p-2">
                    <a href="{{ route('admin.profil.edit') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21a8 8 0 0 1 16 0"></path>
                        </svg>
                        Profil Saya
                    </a>

                    <form action="{{ route('logout') }}" method="POST" data-logout-form>
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-xs font-medium text-red-600 transition hover:bg-red-50">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M10 17l5-5-5-5"></path>
                                <path d="M15 12H3"></path>
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
