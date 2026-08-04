@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <div class="space-y-5">
        <div>
            <div class="flex items-center gap-2 text-[10px] text-slate-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">
                    Dashboard
                </a>
                <span>/</span>
                <a href="{{ route('admin.mahasiswa.index') }}" class="hover:text-blue-600">
                    Data Mahasiswa
                </a>
                <span>/</span>
                <span>Tambah</span>
            </div>

            <h1 class="mt-3 text-xl font-semibold text-slate-800">
                Tambah Mahasiswa
            </h1>
            <p class="mt-1 text-xs text-slate-400">
                Masukkan data identitas mahasiswa.
            </p>
        </div>

        <div class="mx-auto max-w-4xl overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-700">
                    Form Data Mahasiswa
                </h2>
                <p class="mt-1 text-[10px] text-slate-400">
                    Kolom bertanda bintang wajib diisi.
                </p>
            </div>

            <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="p-5 sm:p-6">
                @csrf

                @include('admin.mahasiswa._form', [
                    'submitLabel' => 'Simpan Mahasiswa',
                ])
            </form>
        </div>
    </div>
@endsection
