<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use App\Services\ProfileUpdateDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService) {}

    public function show(Request $request)
    {
        $tab       = $request->query('tab', 'info');
        $error     = $request->query('error', '');
        $success   = $request->query('success', '');
        $user      = Auth::user();
        $data      = $this->profileService->getProfileData($user);
        $orders    = $data->orders;
        $urlUpdate = route('profile.update');

        return view('profile', compact('tab', 'error', 'success', 'user', 'orders', 'urlUpdate'));
    }

    public function apiUpdate(Request $request)
    {
        $dto    = ProfileUpdateDTO::fromArray($request->all());
        $result = $this->profileService->updateProfile($dto, Auth::user());

        if (! $result->success) {
            return response()->json(['error' => $result->message], 422);
        }

        return response()->json(['data' => Auth::user()]);
    }
}
