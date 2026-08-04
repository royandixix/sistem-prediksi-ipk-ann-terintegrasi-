@php
    $user = auth()->user();
    $role = $user?->role;

    $roleLabel = $role instanceof \App\Enums\UserRole
        ? $role->label()
        : ucfirst((string) $role);

    $menuItems = [
        [
            'label' => 'Dashboard',
            'route' => 'operator.dashboard',
            'active' => 'operator.dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'Input IPS',
            'route' => 'operator.data-ips.create',
            'active' => 'operator.data-ips.*',
            'icon' => 'ips',
        ],
        [
            'label' => 'Prediksi IPK',
            'route' => 'operator.prediksi-ipk.create',
            'active' => 'operator.prediksi-ipk.*',
            'icon' => 'prediction',
        ],
        [
            'label' => 'Hasil',
            'route' => 'operator.hasil-prediksi.index',
            'active' => 'operator.hasil-prediksi.*',
            'icon' => 'result',
        ],
        [
            'label' => 'Profil',
            'route' => 'operator.profil.edit',
            'active' => 'operator.profil.*',
            'icon' => 'profile',
        ],
    ];
@endphp

@once
    <style>
        .operator-sidebar {
            background: linear-gradient(
                180deg,
                #263956 0%,
                #23344f 48%,
                #1f2f48 100%
            );
            border-right: 1px solid rgb(15 23 42 / 0.18);
            box-shadow: 8px 0 30px -24px rgb(15 23 42 / 0.75);
        }

        .operator-sidebar-brand {
            background: linear-gradient(
                135deg,
                #2563eb 0%,
                #1d4ed8 55%,
                #1e40af 100%
            );
            box-shadow: inset 0 -1px 0 rgb(30 64 175 / 0.3);
        }

        .operator-sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgb(71 85 105) transparent;
        }

        .operator-sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .operator-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .operator-sidebar-scroll::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgb(71 85 105);
        }

        .operator-sidebar-profile {
            background: linear-gradient(
                180deg,
                rgb(255 255 255 / 0.04),
                rgb(255 255 255 / 0.012)
            );
            transition:
                background-color 0.18s ease,
                border-color 0.18s ease;
        }

        .operator-sidebar-profile:hover {
            background: rgb(255 255 255 / 0.065);
        }

        .operator-sidebar-avatar {
            box-shadow:
                0 0 0 3px rgb(255 255 255 / 0.08),
                0 8px 18px -13px rgb(0 0 0 / 0.8);
            transition:
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .operator-sidebar-profile:hover .operator-sidebar-avatar {
            transform: translateY(-1px);
            box-shadow:
                0 0 0 4px rgb(96 165 250 / 0.14),
                0 10px 22px -14px rgb(0 0 0 / 0.85);
        }

        .operator-sidebar-online {
            position: relative;
        }

        .operator-sidebar-online::after {
            position: absolute;
            inset: -3px;
            border: 1px solid rgb(52 211 153 / 0.25);
            border-radius: 999px;
            content: '';
        }

        .operator-sidebar-link {
            position: relative;
            display: flex;
            min-height: 3rem;
            align-items: center;
            gap: 0.75rem;
            overflow: hidden;
            border-left: 3px solid transparent;
            padding: 0.65rem 1rem;
            color: rgb(148 163 184);
            font-size: 0.8125rem;
            font-weight: 500;
            transition:
                color 0.18s ease,
                background-color 0.18s ease,
                border-color 0.18s ease;
        }

        .operator-sidebar-link:hover {
            border-left-color: rgb(96 165 250 / 0.5);
            background: rgb(15 23 42 / 0.22);
            color: rgb(241 245 249);
        }

        .operator-sidebar-link.is-active {
            border-left-color: #60a5fa;
            background: linear-gradient(
                90deg,
                rgb(30 64 175 / 0.48),
                rgb(15 23 42 / 0.18)
            );
            color: white;
            box-shadow:
                inset 0 1px 0 rgb(255 255 255 / 0.03),
                inset 0 -1px 0 rgb(15 23 42 / 0.18);
        }

        .operator-sidebar-link.is-active::before {
            position: absolute;
            top: 50%;
            left: -3px;
            width: 3px;
            height: 1.8rem;
            border-radius: 0 999px 999px 0;
            content: '';
            background: #93c5fd;
            transform: translateY(-50%);
            box-shadow: 0 0 10px rgb(147 197 253 / 0.5);
        }

        .operator-sidebar-icon {
            display: grid;
            width: 2.05rem;
            height: 2.05rem;
            flex-shrink: 0;
            place-items: center;
            border-radius: 0.6rem;
            color: rgb(148 163 184);
            transition:
                color 0.18s ease,
                background-color 0.18s ease,
                transform 0.18s ease;
        }

        .operator-sidebar-link:hover .operator-sidebar-icon {
            color: #93c5fd;
            transform: translateY(-1px);
        }

        .operator-sidebar-link.is-active .operator-sidebar-icon {
            background: rgb(96 165 250 / 0.14);
            color: #bfdbfe;
            box-shadow: inset 0 0 0 1px rgb(147 197 253 / 0.12);
        }

        .operator-sidebar-indicator {
            margin-left: auto;
            width: 0.35rem;
            height: 0.35rem;
            border-radius: 999px;
            background: transparent;
            transition:
                background-color 0.18s ease,
                box-shadow 0.18s ease;
        }

        .operator-sidebar-link:hover .operator-sidebar-indicator {
            background: rgb(148 163 184 / 0.4);
        }

        .operator-sidebar-link.is-active .operator-sidebar-indicator {
            background: #93c5fd;
            box-shadow: 0 0 8px rgb(147 197 253 / 0.65);
        }

        .operator-sidebar-close {
            transition:
                color 0.18s ease,
                background-color 0.18s ease,
                transform 0.18s ease;
        }

        .operator-sidebar-close:hover {
            background: rgb(255 255 255 / 0.12);
            color: white;
            transform: rotate(90deg);
        }

        .operator-sidebar-status {
            background: rgb(15 23 42 / 0.2);
            box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.025);
        }

        @media (min-width: 1024px) {
            .operator-sidebar-profile {
                padding-top: 0.85rem;
                padding-bottom: 0.85rem;
            }

            .operator-sidebar-avatar {
                width: 2.5rem;
                height: 2.5rem;
            }

            .operator-sidebar-link {
                min-height: 4.25rem;
                flex-direction: column;
                justify-content: center;
                gap: 0.2rem;
                padding: 0.4rem 0.15rem;
                text-align: center;
                font-size: 0.625rem;
                font-weight: 600;
                line-height: 0.85rem;
            }

            .operator-sidebar-icon {
                width: 1.9rem;
                height: 1.9rem;
                border-radius: 0.55rem;
            }

            .operator-sidebar-icon svg {
                width: 1.05rem;
                height: 1.05rem;
            }

            .operator-sidebar-indicator {
                position: absolute;
                right: 0.3rem;
                margin-left: 0;
            }
        }

        @media (min-width: 1280px) {
            .operator-sidebar-link {
                font-size: 0.65rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .operator-sidebar-link,
            .operator-sidebar-icon,
            .operator-sidebar-indicator,
            .operator-sidebar-avatar,
            .operator-sidebar-profile,
            .operator-sidebar-close {
                transition: none;
            }
        }
    </style>
@endonce

<aside
    data-sidebar
    class="operator-sidebar fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-hidden transition-transform duration-300 ease-out lg:w-24 lg:translate-x-0 xl:w-28"
>
    <div class="operator-sidebar-brand flex h-16 shrink-0 items-center px-4 lg:justify-center lg:px-2">
        <a
            href="{{ route('operator.dashboard') }}"
            class="flex min-w-0 items-center gap-3 text-white lg:block lg:text-center"
        >
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg border border-white/20 bg-white/10 text-xs font-black shadow-sm lg:mx-auto lg:h-9 lg:w-9">
                OP
            </div>

            <div class="min-w-0 lg:mt-1">
                <p class="truncate text-sm font-extrabold tracking-wide lg:text-[10px]">
                    <span class="lg:hidden">
                        PREDIKSI IPK
                    </span>

                    <span class="hidden lg:inline">
                        OPERATOR
                    </span>
                </p>

                <p class="mt-0.5 truncate text-[9px] font-semibold uppercase tracking-[0.13em] text-white/75 lg:hidden">
                    Panel Operator
                </p>
            </div>
        </a>

        <button
            type="button"
            data-sidebar-close
            class="operator-sidebar-close ml-auto grid h-9 w-9 place-items-center rounded-lg text-white/75 lg:hidden"
            aria-label="Tutup sidebar"
        >
            <svg
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="m6 6 12 12"></path>
                <path d="m18 6-12 12"></path>
            </svg>
        </button>
    </div>

    <a
        href="{{ route('operator.profil.edit') }}"
        class="operator-sidebar-profile group shrink-0 border-b border-white/[0.055] px-4 py-4 lg:px-2"
    >
        <div class="flex items-center gap-3 lg:flex-col lg:gap-2">
            <div class="relative shrink-0">
                <div class="operator-sidebar-avatar grid h-12 w-12 place-items-center rounded-full border-2 border-white/20 bg-slate-100 text-sm font-black text-slate-700">
                    {{ strtoupper(substr($user?->name ?? 'O', 0, 1)) }}
                </div>

                <span class="operator-sidebar-online absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-[#263956] bg-emerald-400"></span>
            </div>

            <div class="min-w-0 lg:w-full lg:text-center">
                <p class="truncate text-sm font-bold text-white lg:text-[10px]">
                    {{ $user?->name ?? 'Operator' }}
                </p>

                <p class="mt-0.5 truncate text-[10px] font-medium text-slate-400 lg:text-[8px]">
                    {{ $roleLabel }}
                </p>
            </div>
        </div>
    </a>

    <div class="operator-sidebar-scroll flex-1 overflow-y-auto py-2">
        <nav>
            @foreach ($menuItems as $item)
                @php
                    $active = request()->routeIs($item['active']);
                @endphp

                <a
                    href="{{ route($item['route']) }}"
                    class="operator-sidebar-link {{ $active ? 'is-active' : '' }}"
                    title="{{ $item['label'] }}"
                    @if ($active) aria-current="page" @endif
                >
                    <span class="operator-sidebar-icon">
                        @switch($item['icon'])
                            @case('dashboard')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                    <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                    <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                    <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                </svg>
                                @break

                            @case('ips')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M5 3h14v18H5Z"></path>
                                    <path d="M8 7h8"></path>
                                    <path d="M8 11h8"></path>
                                    <path d="M8 15h5"></path>
                                </svg>
                                @break

                            @case('prediction')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 19V5"></path>
                                    <path d="M4 19h16"></path>
                                    <path d="m7 15 4-4 3 2 5-7"></path>
                                </svg>
                                @break

                            @case('result')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"></path>
                                    <path d="m9 10 2 2 4-4"></path>
                                </svg>
                                @break

                            @case('profile')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path d="M4 21a8 8 0 0 1 16 0"></path>
                                </svg>
                                @break
                        @endswitch
                    </span>

                    <span class="min-w-0 truncate">
                        {{ $item['label'] }}
                    </span>

                    <span class="operator-sidebar-indicator"></span>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="shrink-0 border-t border-white/[0.055] px-3 py-3 lg:px-2">
        <div class="operator-sidebar-status flex items-center gap-2 rounded-lg px-3 py-2 lg:flex-col lg:px-1 lg:text-center">
            <span class="operator-sidebar-online h-2 w-2 shrink-0 rounded-full bg-emerald-400"></span>

            <div class="min-w-0">
                <p class="truncate text-[10px] font-semibold text-slate-300 lg:text-[8px]">
                    Operator aktif
                </p>

                <p class="mt-0.5 truncate text-[9px] text-slate-500 lg:hidden">
                    ANN Prediction System
                </p>
            </div>
        </div>
    </div>
</aside>