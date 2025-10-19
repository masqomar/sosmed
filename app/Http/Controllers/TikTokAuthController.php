<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TikTok\Authentication\Authentication;
use TikTok\User\User as TikTokUser;
use App\Models\User as AppUser;

class TikTokAuthController extends Controller
{
    public function redirect()
    {
        $auth = new Authentication([
            'client_key' => config('services.tiktok.client_key'),
            'client_secret' => config('services.tiktok.client_secret'),
            'graph_version' => config('services.tiktok.graph_version', 'v2'),
        ]);

        $redirectUri = config('services.tiktok.redirect_uri');
        $scope = ['user.info.basic', 'video.list']; // sesuaikan kebutuhan

        $authUrl = $auth->getAuthenticationUrl($redirectUri, $scope, csrf_token());

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $auth = new Authentication([
            'client_key' => config('services.tiktok.client_key'),
            'client_secret' => config('services.tiktok.client_secret'),
            'graph_version' => config('services.tiktok.graph_version', 'v2'),
        ]);

        $redirectUri = config('services.tiktok.redirect_uri');

        $token = $auth->getAccessTokenFromCode($request->code, $redirectUri);

        // Simpan token di database
        /** @var AppUser $user */
        $user = Auth::user();
        $user->tiktok_token = $token['access_token'] ?? $token['data']['access_token'] ?? null;
        $user->save();

        return redirect()->route('dashboard');
    }

    public function getUserInfo()
    {
        $token = Auth::user()->tiktok_token;

        $user = new TikTokUser([
            'access_token' => $token,
        ]);

        $userInfo = $user->getSelf();

        return response()->json($userInfo);
    }
}
