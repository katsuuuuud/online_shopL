<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Services\ProfileService;
use App\Services\ProfileUpdateDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService) {}

    public function show(Request $request): View
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

    public function apiUpdate(Request $request): JsonResponse
    {
        $dto = ProfileUpdateDTO::fromArray($request->all());
        $this->profileService->updateProfile($dto, Auth::user());

        return response()->json(['data' => Auth::user()]);
    }
}
