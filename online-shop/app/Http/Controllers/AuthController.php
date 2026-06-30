<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}


    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $mode  = 'login';
        $next  = $request->query('next', '/');
        $error = $request->query('error', '');

        return view('auth', compact('mode', 'next', 'error'));
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $mode  = 'register';
        $next  = $request->query('next', '/');
        $error = $request->query('error', '');

        return view('auth', compact('mode', 'next', 'error'));
    }

    public function handleLogin(Request $request)
    {
        $guestId = $request->cookie('guest_cart_id');
        $result  = $this->authService->login($request->all(), $guestId);
        $next    = $result['next'] ?? '/';

        if ($result['success']) {
            return redirect($next);
        }

        return redirect('/auth/login?' . http_build_query([
                'next'  => $next,
                'error' => $result['message'],
            ]));
    }

    public function handleRegister(Request $request)
    {
        $guestId = $request->cookie('guest_cart_id');
        $result  = $this->authService->register($request->all(), $guestId);
        $next    = $result['next'] ?? '/';

        if ($result['success']) {
            return redirect($next);
        }

        return redirect('/auth/register?' . http_build_query([
                'next'  => $next,
                'error' => $result['message'],
            ]));
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
        $result  = $this->authService->login($request->all(), $guestId);

        if (! $result['success']) {
            return response()->json(['error' => $result['message']], 401);
        }

        return response()->json(['data' => Auth::user()]);
    }

    public function apiRegister(Request $request)
    {
        $guestId = $request->cookie('guest_cart_id');
        $result  = $this->authService->register($request->all(), $guestId);

        if (! $result['success']) {
            return response()->json(['error' => $result['message']], 422);
        }

        return response()->json(['data' => Auth::user()], 201);
    }

    public function apiLogout(Request $request)
    {
        $this->authService->logout();
        $request->session()->invalidate();

        return response()->json(['data' => null])
            ->withCookie(cookie()->forget('guest_cart_id'));
    }
}
