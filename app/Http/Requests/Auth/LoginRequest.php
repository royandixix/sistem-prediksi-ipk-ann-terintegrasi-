<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.min' => 'Username minimal terdiri dari 4 karakter.',
            'username.max' => 'Username maksimal terdiri dari 50 karakter.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan tanda hubung.',

            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.max' => 'Password maksimal terdiri dari 255 karakter.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $username = Str::lower(
            trim((string) $this->input('username'))
        );

        $password = (string) $this->input('password');

        $credentials = [
            'username' => $username,
            'password' => $password,
            'is_active' => 1,
        ];

        if (! Auth::attempt(
            $credentials,
            $this->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => 'Username atau password yang Anda masukkan tidak benar.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts(
            $this->throttleKey(),
            5
        )) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn(
            $this->throttleKey()
        );

        throw ValidationException::withMessages([
            'username' => "Terlalu banyak percobaan login. Silakan coba kembali dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        $username = Str::lower(
            trim((string) $this->input('username'))
        );

        return Str::transliterate(
            $username.'|'.$this->ip()
        );
    }
}