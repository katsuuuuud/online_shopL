<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\LoginDTO;
use App\Services\RegisterDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function handleLogin(LoginRequest $request): RedirectResponse
    {
        $guestId = $request->cookie('guest_cart_id');
        $next    = (string) $request->query('next', '/');

        $this->authService->login(LoginDTO::fromArray($request->validated()), $guestId);

        return redirect($next);
    }

    public function handleRegister(RegisterRequest $request): RedirectResponse
    {
        $guestId = $request->cookie('guest_cart_id');
        $next    = (string) $request->query('next', '/');

        $this->authService->register(RegisterDTO::fromArray($request->validated()), $guestId);

        return redirect($next);
    }


    public function showLogin(Request $request): View
    {
        $mode  = 'login';
        $next  = (string) $request->query('next', '/');
        $error = (string) $request->query('error', '');

        return view('auth', compact('mode', 'next', 'error'));
    }

    public function showRegister(Request $request): View
    {
        $mode  = 'register';
        $next  = (string) $request->query('next', '/');
        $error = (string) $request->query('error', '');

        return view('auth', compact('mode', 'next', 'error'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->withCookie(cookie()->forget('guest_cart_id'));
    }


    public function apiLogin(LoginRequest $request): JsonResponse
    {
        $guestId = $request->cookie('guest_cart_id');
        $this->authService->login(LoginDTO::fromArray($request->validated()), $guestId);

        return response()->json(['data' => Auth::user()]);
    }

    public function apiRegister(RegisterRequest $request): JsonResponse
    {
        $guestId = $request->cookie('guest_cart_id');
        $this->authService->register(RegisterDTO::fromArray($request->validated()), $guestId);

        return response()->json(['data' => Auth::user()], 201);
    }
}
