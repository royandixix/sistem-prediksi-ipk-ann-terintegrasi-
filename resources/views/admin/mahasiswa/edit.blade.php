@extends('layouts.admin')

@section('title', 'Edit Mahasiswa')

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
                <span>Edit</span>
            </div>

            <h1 class="mt-3 text-xl font-semibold text-slate-800">
                Edit Mahasiswa
            </h1>
            <p class="mt-1 text-xs text-slate-400">
                Perbarui informasi mahasiswa.
            </p>
        </div>

        <div class="mx-auto max-w-4xl overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-700">
                    Form Edit Mahasiswa
                </h2>
                <p class="mt-1 text-[10px] text-slate-400">
                    NIM {{ $mahasiswa->nim }}
                </p>
            </div>

            <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST" class="p-5 sm:p-6">
                @csrf
                @method('PUT')

                @include('admin.mahasiswa._form', [
                    'submitLabel' => 'Simpan Perubahan',
                ])
            </form>
        </div>
    </div>
@endsection
