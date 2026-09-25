<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(StoreRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->validated())) {
            return redirect()->back()->with('error', 'Username atau password salah!');
        }

        $request->session()->regenerate();

        $redirectTo = Auth::user()->role === 'admin' ? '/admin' : '/';

        return redirect($redirectTo);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
