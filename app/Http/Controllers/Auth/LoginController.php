<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->authService->getRedirectRoute(Auth::user()));
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->authService->login($request)) {
            $user = Auth::user();

            return redirect()->intended($this->authService->getRedirectRoute($user));
        }

        return back()->withErrors([
            'nip' => 'NIP atau Password tidak sesuai.',
        ])->onlyInput('nip');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return redirect('/login');
    }
}
