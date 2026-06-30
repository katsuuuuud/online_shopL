<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService) {}


    public function show(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/auth/login?next=/profile');
        }

        $tab     = $request->query('tab', 'info');
        $error   = $request->query('error', '');
        $success = $request->query('success', '');
        $user    = Auth::user();
        $data    = $this->profileService->getProfileData($user);
        $orders  = $data['orders'];

        return view('profile', compact('tab', 'error', 'success', 'user', 'orders'));
    }

    public function handleUpdate(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('auth.login.form', ['next' => '/profile']);
        }

        $tab    = $request->input('tab', 'info');
        $result = $this->profileService->updateProfile($request->all(), Auth::user());

        if (! $result['success']) {
            return redirect('/profile?tab=' . urlencode($tab) . '&error=' . urlencode($result['message']));
        }

        return redirect('/profile?tab=' . urlencode($tab) . '&success=' . urlencode($result['message']));
    }


    public function apiUpdate(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Требуется авторизация'], 401);
        }

        $result = $this->profileService->updateProfile($request->all(), Auth::user());

        if (! $result['success']) {
            return response()->json(['error' => $result['message']], 422);
        }

        return response()->json(['data' => Auth::user()]);
    }
}
