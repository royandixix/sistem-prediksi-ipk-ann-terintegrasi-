@extends('layouts.admin')

@section('title','Edit Data IPS')

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
            <span>Edit</span>
        </div>

        <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-800">
            Edit Data IPS
        </h1>

        <p class="mt-1 text-sm text-slate-400">
            Perbarui nilai IPS {{$dataIps->mahasiswa->nama}}.
        </p>
    </div>

    <div class="mx-auto max-w-6xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-base font-semibold text-slate-800">
                Form Edit Data IPS
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                NIM {{$dataIps->mahasiswa->nim}}
            </p>
        </div>

        <form
            action="{{route('admin.data-ips.update',$dataIps)}}"
            method="POST"
            class="p-6"
        >
            @csrf
            @method('PUT')

            @include('admin.data-ips._form',[
                'submitLabel'=>'Simpan Perubahan'
            ])
        </form>
    </div>
</div>
@endsection