@extends('layouts.admin')

@section('title','Input Data IPS')

@section('content')
<div class="space-y-5">
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
            <span>Input</span>
        </div>

        <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
            Input Data IPS
        </h1>

        <p class="mt-1 text-sm text-slate-400">
            Masukkan nilai IPS Semester 1 sampai Semester 5.
        </p>
    </div>

    <div class="mx-auto max-w-6xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-base font-semibold text-slate-800">
                Form Data IPS Mahasiswa
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                Kolom bertanda bintang wajib diisi.
            </p>
        </div>

        <form
            action="{{route('admin.data-ips.store')}}"
            method="POST"
            class="p-6"
        >
            @csrf

            @include('admin.data-ips._form',[
                'submitLabel'=>'Simpan Data IPS'
            ])
        </form>
    </div>
</div>
@endsection