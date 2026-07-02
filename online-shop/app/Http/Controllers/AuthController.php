<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\LoginDTO;
use App\Services\RegisterDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}


    public function showLogin(Request $request)
    {
        $mode  = 'login';
        $next  = $request->query('next', '/');
        $error = $request->query('error', '');

        return view('auth', compact('mode', 'next', 'error'));
    }

    public function showRegister(Request $request)
    {
        $mode  = 'register';
        $next  = $request->query('next', '/');
        $error = $request->query('error', '');

        return view('auth', compact('mode', 'next', 'error'));
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->withCookie(cookie()->forget('guest_cart_id'));
    }


    public function apiLogin(Request $request)
    {
        $guestId = $request->cookie('guest_cart_id');
        $this->authService->login(LoginDTO::fromArray($request->all()), $guestId);

        return response()->json(['data' => Auth::user()]);
    }

    public function apiRegister(Request $request)
    {
        $guestId = $request->cookie('guest_cart_id');
        $this->authService->register(RegisterDTO::fromArray($request->all()), $guestId);

        return response()->json(['data' => Auth::user()], 201);
    }
}
