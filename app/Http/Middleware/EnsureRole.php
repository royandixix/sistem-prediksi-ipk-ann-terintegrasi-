<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles,
    ): Response|RedirectResponse {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if (! $user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Akun Anda telah dinonaktifkan. Hubungi administrator.'
                );
        }

        $currentRole = $user->role instanceof UserRole
            ? $user->role->value
            : $user->role;

        if (! in_array($currentRole, $roles, true)) {
            abort(
                403,
                'Anda tidak memiliki hak akses ke halaman ini.'
            );
        }

        return $next($request);
    }
}