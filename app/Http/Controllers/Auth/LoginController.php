<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if(Auth::check()){
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user=$request->user();

        $user->forceFill([
            'last_login_at'=>now(),
            'last_login_ip'=>$request->ip(),
        ])->save();

        return $this->redirectByRole($user)
            ->with('success',"Selamat datang, {$user->name}.");
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success','Anda berhasil keluar dari sistem.');
    }

    private function redirectByRole(object $user): RedirectResponse
    {
        $role=$user->role instanceof UserRole
            ?$user->role->value
            :(string)$user->role;

        $route=match($role){
            'admin'=>'admin.dashboard',
            'operator'=>'operator.dashboard',
            default=>null,
        };

        if($route===null){
            Auth::logout();

            return redirect()
                ->route('login')
                ->with('error','Role akun tidak dikenali. Hubungi administrator.');
        }

        return redirect()->intended(route($route));
    }
}