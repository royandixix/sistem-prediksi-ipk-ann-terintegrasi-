@php
    $user = auth()->user();
    $role = $user?->role;

    $roleLabel = $role instanceof \App\Enums\UserRole
        ? $role->label()
        : ucfirst((string) $role);

    $menuItems = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'Profil',
            'route' => 'admin.profil.edit',
            'active' => 'admin.profil.*',
            'icon' => 'profile',
        ],
        [
            'label' => 'Mahasiswa',
            'route' => 'admin.mahasiswa.index',
            'active' => 'admin.mahasiswa.*',
            'icon' => 'students',
        ],
        [
            'label' => 'Data IPS',
            'route' => 'admin.data-ips.index',
            'active' => 'admin.data-ips.*',
            'icon' => 'ips',
        ],
        [
            'label' => 'Dataset',
            'route' => 'admin.dataset.index',
            'active' => 'admin.dataset.*',
            'icon' => 'dataset',
        ],
        [
            'label' => 'Model ANN',
            'route' => 'admin.model-ann.index',
            'active' => 'admin.model-ann.*',
            'icon' => 'ann',
        ],
        [
            'label' => 'Prediksi IPK',
            'route' => 'admin.prediksi-ipk.index',
            'active' => 'admin.prediksi-ipk.*',
            'icon' => 'prediction',
        ],
        [
            'label' => 'Hasil',
            'route' => 'admin.hasil-prediksi.index',
            'active' => 'admin.hasil-prediksi.*',
            'icon' => 'result',
        ],
        [
            'label' => 'Grafik',
            'route' => 'admin.grafik.index',
            'active' => 'admin.grafik.*',
            'icon' => 'chart',
        ],
        [
            'label' => 'Laporan',
            'route' => 'admin.laporan.index',
            'active' => 'admin.laporan.*',
            'icon' => 'report',
        ],
    ];
@endphp

@once
    <style>
        .modern-sidebar {
            background: linear-gradient(
                180deg,
                #293b59 0%,
                #263752 48%,
                #22314b 100%
            );
            border-right: 1px solid rgb(15 23 42 / 0.18);
            box-shadow: 8px 0 30px -24px rgb(15 23 42 / 0.75);
        }

        .modern-sidebar-brand {
            background: linear-gradient(
                135deg,
                #2fc3b2 0%,
                #25b7a7 55%,
                #20a99b 100%
            );
            box-shadow: inset 0 -1px 0 rgb(15 118 110 / 0.22);
        }

        .modern-sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgb(71 85 105) transparent;
        }

        .modern-sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .modern-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .modern-sidebar-scroll::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgb(71 85 105);
        }

        .modern-sidebar-profile {
            background: linear-gradient(
                180deg,
                rgb(255 255 255 / 0.035),
                rgb(255 255 255 / 0.012)
            );
            transition:
                background-color 0.18s ease,
                border-color 0.18s ease;
        }

        .modern-sidebar-profile:hover {
            background: rgb(255 255 255 / 0.055);
        }

        .modern-sidebar-avatar {
            box-shadow:
                0 0 0 3px rgb(255 255 255 / 0.08),
                0 8px 18px -13px rgb(0 0 0 / 0.8);
            transition:
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .modern-sidebar-profile:hover .modern-sidebar-avatar {
            transform: translateY(-1px);
            box-shadow:
                0 0 0 4px rgb(47 195 178 / 0.12),
                0 10px 22px -14px rgb(0 0 0 / 0.85);
        }

        .modern-sidebar-online {
            position: relative;
        }

        .modern-sidebar-online::after {
            position: absolute;
            inset: -3px;
            border: 1px solid rgb(52 211 153 / 0.25);
            border-radius: 999px;
            content: '';
        }

        .modern-sidebar-link {
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

        .modern-sidebar-link:hover {
            border-left-color: rgb(47 195 178 / 0.45);
            background: rgb(15 23 42 / 0.2);
            color: rgb(241 245 249);
        }

        .modern-sidebar-link.is-active {
            border-left-color: #37cbb9;
            background: linear-gradient(
                90deg,
                rgb(15 23 42 / 0.46),
                rgb(15 23 42 / 0.16)
            );
            color: white;
            box-shadow:
                inset 0 1px 0 rgb(255 255 255 / 0.025),
                inset 0 -1px 0 rgb(15 23 42 / 0.18);
        }

        .modern-sidebar-link.is-active::before {
            position: absolute;
            top: 50%;
            left: -3px;
            width: 3px;
            height: 1.8rem;
            border-radius: 0 999px 999px 0;
            content: '';
            background: #47d7c4;
            transform: translateY(-50%);
            box-shadow: 0 0 10px rgb(71 215 196 / 0.45);
        }

        .modern-sidebar-icon {
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

        .modern-sidebar-link:hover .modern-sidebar-icon {
            color: #62ddcd;
            transform: translateY(-1px);
        }

        .modern-sidebar-link.is-active .modern-sidebar-icon {
            background: rgb(47 195 178 / 0.13);
            color: #62ddcd;
            box-shadow: inset 0 0 0 1px rgb(98 221 205 / 0.1);
        }

        .modern-sidebar-indicator {
            margin-left: auto;
            width: 0.35rem;
            height: 0.35rem;
            border-radius: 999px;
            background: transparent;
            transition:
                background-color 0.18s ease,
                box-shadow 0.18s ease;
        }

        .modern-sidebar-link:hover .modern-sidebar-indicator {
            background: rgb(148 163 184 / 0.4);
        }

        .modern-sidebar-link.is-active .modern-sidebar-indicator {
            background: #62ddcd;
            box-shadow: 0 0 8px rgb(98 221 205 / 0.65);
        }

        .modern-sidebar-mobile-close {
            transition:
                color 0.18s ease,
                background-color 0.18s ease,
                transform 0.18s ease;
        }

        .modern-sidebar-mobile-close:hover {
            background: rgb(255 255 255 / 0.12);
            color: white;
            transform: rotate(90deg);
        }

        .modern-sidebar-status {
            background: rgb(15 23 42 / 0.18);
            box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.025);
        }

        @media (min-width: 1024px) {
            .modern-sidebar-profile {
                padding-top: 0.8rem;
                padding-bottom: 0.8rem;
            }

            .modern-sidebar-avatar {
                width: 2.5rem;
                height: 2.5rem;
            }

            .modern-sidebar-link {
                min-height: 3.5rem;
                flex-direction: column;
                justify-content: center;
                gap: 0.12rem;
                border-left-width: 3px;
                padding: 0.35rem 0.15rem;
                text-align: center;
                font-size: 0.625rem;
                font-weight: 600;
                line-height: 0.82rem;
            }

            .modern-sidebar-icon {
                width: 1.85rem;
                height: 1.85rem;
                border-radius: 0.55rem;
            }

            .modern-sidebar-icon svg {
                width: 1.05rem;
                height: 1.05rem;
            }

            .modern-sidebar-indicator {
                position: absolute;
                right: 0.3rem;
                margin-left: 0;
            }
        }

        @media (min-width: 1280px) {
            .modern-sidebar-link {
                min-height: 3.55rem;
                font-size: 0.65rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .modern-sidebar-link,
            .modern-sidebar-icon,
            .modern-sidebar-indicator,
            .modern-sidebar-avatar,
            .modern-sidebar-profile,
            .modern-sidebar-mobile-close {
                transition: none;
            }
        }
    </style>
@endonce

<aside
    data-sidebar
    class="modern-sidebar fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-hidden transition-transform duration-300 ease-out lg:w-24 lg:translate-x-0 xl:w-28"
>
    <div class="modern-sidebar-brand flex h-16 shrink-0 items-center px-4 lg:justify-center lg:px-2">
        <a
            href="{{ route('admin.dashboard') }}"
            class="flex min-w-0 items-center gap-3 text-white lg:block lg:text-center"
        >
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg border border-white/20 bg-white/10 text-xs font-black shadow-sm lg:mx-auto lg:h-9 lg:w-9">
                SP
            </div>

            <div class="min-w-0 lg:mt-1">
                <p class="truncate text-sm font-extrabold tracking-wide lg:text-[10px]">
                    <span class="lg:hidden">
                        PREDIKSI IPK
                    </span>

                    <span class="hidden lg:inline">
                        SP-IPK
                    </span>
                </p>

                <p class="mt-0.5 truncate text-[9px] font-semibold uppercase tracking-[0.13em] text-white/75 lg:hidden">
                    Administrator
                </p>
            </div>
        </a>

        <button
            type="button"
            data-sidebar-close
            class="modern-sidebar-mobile-close ml-auto grid h-9 w-9 place-items-center rounded-lg text-white/75 lg:hidden"
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
        href="{{ route('admin.profil.edit') }}"
        class="modern-sidebar-profile group shrink-0 border-b border-white/[0.055] px-4 py-4 lg:px-2"
    >
        <div class="flex items-center gap-3 lg:flex-col lg:gap-2">
            <div class="relative shrink-0">
                <div class="modern-sidebar-avatar grid h-12 w-12 place-items-center rounded-full border-2 border-white/20 bg-slate-100 text-sm font-black text-slate-700">
                    {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                </div>

                <span class="modern-sidebar-online absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-[#293b59] bg-emerald-400"></span>
            </div>

            <div class="min-w-0 lg:w-full lg:text-center">
                <p class="truncate text-sm font-bold text-white lg:text-[10px]">
                    {{ $user?->name ?? 'Administrator' }}
                </p>

                <p class="mt-0.5 truncate text-[10px] font-medium text-slate-400 lg:text-[8px]">
                    {{ $roleLabel }}
                </p>
            </div>
        </div>
    </a>

    <div class="modern-sidebar-scroll flex-1 overflow-y-auto py-2">
        <nav>
            @foreach ($menuItems as $item)
                @php
                    $active = request()->routeIs($item['active']);
                @endphp

                <div>
                    <a
                        href="{{ route($item['route']) }}"
                        class="modern-sidebar-link {{ $active ? 'is-active' : '' }}"
                        title="{{ $item['label'] }}"
                        @if ($active) aria-current="page" @endif
                    >
                        <span class="modern-sidebar-icon">
                            @switch($item['icon'])
                                @case('dashboard')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                        <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                        <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                        <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                    </svg>
                                    @break

                                @case('profile')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                                    </svg>
                                    @break

                                @case('students')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle cx="9" cy="8" r="4"></circle>
                                        <path d="M2.5 21a6.5 6.5 0 0 1 13 0"></path>
                                        <path d="M17 11a4 4 0 0 1 4 4v6"></path>
                                    </svg>
                                    @break

                                @case('ips')
                                    <svg
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
                                    @break

                                @case('dataset')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <ellipse cx="12" cy="5" rx="8" ry="3"></ellipse>
                                        <path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5"></path>
                                        <path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"></path>
                                    </svg>
                                    @break

                                @case('ann')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <circle cx="4" cy="6" r="2"></circle>
                                        <circle cx="20" cy="6" r="2"></circle>
                                        <circle cx="4" cy="18" r="2"></circle>
                                        <circle cx="20" cy="18" r="2"></circle>
                                        <path d="m6 7 4 3"></path>
                                        <path d="m18 7-4 3"></path>
                                        <path d="m6 17 4-3"></path>
                                        <path d="m18 17-4-3"></path>
                                    </svg>
                                    @break

                                @case('prediction')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M4 19V5"></path>
                                        <path d="M4 19h16"></path>
                                        <path d="m7 15 4-4 3 2 5-7"></path>
                                    </svg>
                                    @break

                                @case('result')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"></path>
                                        <path d="m9 10 2 2 4-4"></path>
                                    </svg>
                                    @break

                                @case('chart')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M4 20V10"></path>
                                        <path d="M10 20V4"></path>
                                        <path d="M16 20v-7"></path>
                                        <path d="M22 20V7"></path>
                                    </svg>
                                    @break

                                @case('report')
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M6 2h9l5 5v15H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"></path>
                                        <path d="M14 2v6h6"></path>
                                        <path d="M8 13h8"></path>
                                        <path d="M8 17h6"></path>
                                    </svg>
                                    @break
                            @endswitch
                        </span>

                        <span class="min-w-0 truncate">
                            {{ $item['label'] }}
                        </span>

                        <span class="modern-sidebar-indicator"></span>
                    </a>
                </div>
            @endforeach
        </nav>
    </div>

    <div class="shrink-0 border-t border-white/[0.055] px-3 py-3 lg:px-2">
        <div class="modern-sidebar-status flex items-center gap-2 rounded-lg px-3 py-2 lg:flex-col lg:px-1 lg:text-center">
            <span class="modern-sidebar-online h-2 w-2 shrink-0 rounded-full bg-emerald-400"></span>

            <div class="min-w-0">
                <p class="truncate text-[10px] font-semibold text-slate-300 lg:text-[8px]">
                    Sistem aktif
                </p>

                <p class="mt-0.5 truncate text-[9px] text-slate-500 lg:hidden">
                    ANN Prediction System
                </p>
            </div>
        </div>
    </div>
</aside>