<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function edit(Request $request): View
    {
        return view('operator.profil.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'current_password' => [
                'nullable',
                'required_with:password,password_confirmation',
                'current_password',
            ],
            'password' => [
                'nullable',
                'required_with:current_password,password_confirmation',
                'string',
                'min:8',
                'confirmed',
            ],
            'password_confirmation' => [
                'nullable',
                'required_with:password,current_password',
                'string',
                'min:8',
                'same:password',
            ],
        ], [
            'name.required' => 'Nama operator wajib diisi.',
            'name.string' => 'Nama operator harus berupa teks.',
            'name.max' => 'Nama operator maksimal 100 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.string' => 'Alamat email harus berupa teks.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email maksimal 150 karakter.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'current_password.required_with' => 'Password saat ini wajib diisi untuk mengganti password.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required_with' => 'Password baru wajib diisi.',
            'password.string' => 'Password baru harus berupa teks.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
            'password_confirmation.required_with' => 'Konfirmasi password baru wajib diisi.',
            'password_confirmation.string' => 'Konfirmasi password harus berupa teks.',
            'password_confirmation.min' => 'Konfirmasi password minimal 8 karakter.',
            'password_confirmation.same' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $name = trim($validated['name']);
        $email = strtolower(trim($validated['email']));
        $newPassword = $validated['password'] ?? null;

        $user->name = $name;
        $user->email = $email;

        if (filled($newPassword)) {
            $user->password = Hash::make($newPassword);
        }

        if (! $user->isDirty()) {
            return redirect()
                ->route('operator.profil.edit')
                ->with(
                    'warning',
                    'Tidak ada perubahan data yang dapat disimpan.'
                );
        }

        $user->save();

        return redirect()
            ->route('operator.profil.edit')
            ->with(
                'success',
                'Profil operator berhasil diperbarui.'
            );
    }
}