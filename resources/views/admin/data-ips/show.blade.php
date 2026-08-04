@extends('layouts.admin')

@section('title','Detail Data IPS')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-[10px] text-slate-400">
                <a href="{{route('admin.dashboard')}}" class="hover:text-blue-600">
                    Dashboard
                </a>
                <span>/</span>
                <a href="{{route('admin.data-ips.index')}}" class="hover:text-blue-600">
                    Data IPS
                </a>
                <span>/</span>
                <span>Detail</span>
            </div>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Detail Data IPS
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Informasi akademik {{$dataIps->mahasiswa->nama}}.
            </p>
        </div>

        <div class="flex gap-2">
            <a
                href="{{route('admin.data-ips.index')}}"
                class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
            >
                Kembali
            </a>

            <a
                href="{{route('admin.data-ips.edit',$dataIps)}}"
                class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
                Edit Data
            </a>
        </div>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-6">
            <div class="flex items-center gap-4">
                <div class="grid h-14 w-14 place-items-center rounded-full bg-blue-50 text-lg font-bold text-blue-600">
                    {{strtoupper(substr($dataIps->mahasiswa->nama,0,1))}}
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        {{$dataIps->mahasiswa->nama}}
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        NIM {{$dataIps->mahasiswa->nim}}
                        · Angkatan {{$dataIps->mahasiswa->angkatan}}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-px bg-slate-100 sm:grid-cols-5">
            @foreach([
                'Semester 1'=>$dataIps->ips_1,
                'Semester 2'=>$dataIps->ips_2,
                'Semester 3'=>$dataIps->ips_3,
                'Semester 4'=>$dataIps->ips_4,
                'Semester 5'=>$dataIps->ips_5
            ] as $label=>$value)
                <div class="bg-white p-6 text-center">
                    <p class="text-xs text-slate-400">
                        {{$label}}
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-800">
                        {{number_format((float)$value,2)}}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 border-t border-slate-100 p-6 sm:grid-cols-3">
            <div class="rounded-xl bg-blue-50 p-4">
                <p class="text-xs text-blue-500">
                    Rata-rata IPS
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-700">
                    {{number_format($dataIps->averageIps(),3)}}
                </p>
            </div>

            <div class="rounded-xl bg-violet-50 p-4">
                <p class="text-xs text-violet-500">
                    IPK Akhir Aktual
                </p>

                <p class="mt-2 text-2xl font-bold text-violet-700">
                    {{$dataIps->ipk_akhir_aktual!==null
                        ?number_format((float)$dataIps->ipk_akhir_aktual,3)
                        :'-'}}
                </p>
            </div>

            <div class="rounded-xl bg-emerald-50 p-4">
                <p class="text-xs text-emerald-500">
                    Status Data
                </p>

                <p class="mt-2 text-lg font-bold text-emerald-700">
                    Siap Diprediksi
                </p>
            </div>
        </div>

        @if($dataIps->data_source || $dataIps->is_estimated)
            <div class="border-t border-slate-100 p-6">
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sumber Data</p>
                        <p class="mt-2 text-sm font-semibold text-slate-700">{{ $dataIps->data_source ?? 'Input manual' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Metode Preprocessing</p>
                        <p class="mt-2 text-sm font-semibold text-slate-700">{{ $dataIps->preprocessing_method ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Periode Sumber</p>
                        <p class="mt-2 text-sm font-semibold text-slate-700">{{ implode(', ', $dataIps->source_terms ?? []) ?: '-' }}</p>
                    </div>
                </div>

                @if($dataIps->is_estimated)
                    <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                        IPS Semester 1 dan Semester 2 merupakan nilai estimasi kompatibilitas dari dataset sumber.
                        Ganti dengan nilai asli jika data semester awal sudah tersedia.
                    </div>
                @endif
            </div>
        @endif

        @if($dataIps->catatan)
            <div class="border-t border-slate-100 p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Catatan
                </p>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{$dataIps->catatan}}
                </p>
            </div>
        @endif
    </section>
</div>
@endsection