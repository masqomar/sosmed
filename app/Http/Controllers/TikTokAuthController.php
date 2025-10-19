<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TikTok\Authentication\Authentication;
use TikTok\User\User as TikTokUser;
use TikTok\Request\Params;
use TikTok\Request\Fields;
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
        // Request full scopes needed for user info fields (profile + stats) and video list
        $scope = ['user.info.basic', 'user.info.profile', 'user.info.stats', 'video.list'];

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

        // If no token yet, prompt user to connect with necessary scopes
        if (!$token) {
            $auth = new Authentication([
                'client_key' => config('services.tiktok.client_key'),
                'client_secret' => config('services.tiktok.client_secret'),
                'graph_version' => config('services.tiktok.graph_version', 'v2'),
            ]);

            $redirectUri = config('services.tiktok.redirect_uri');
            $scope = ['user.info.basic', 'user.info.profile', 'user.info.stats', 'video.list'];

            $authUrl = $auth->getAuthenticationUrl($redirectUri, $scope, csrf_token());
            return redirect()->away($authUrl);
        }

        $user = new TikTokUser([
            'access_token' => $token,
        ]);

        // Required fields for TikTok /user/info/ endpoint
        $fields = [
            Fields::OPEN_ID,
            Fields::UNION_ID,
            Fields::DISPLAY_NAME,
            Fields::AVATAR_URL,
            Fields::AVATAR_URL_100,
            Fields::FOLLOWER_COUNT,
            Fields::FOLLOWING_COUNT,
            Fields::LIKES_COUNT,
            Fields::VIDEO_COUNT,
            Fields::PROFILE_DEEP_LINK,
            Fields::IS_VERIFIED,
            Fields::BIO_DESCRIPTION,
        ];

        // Pass fields as query params
        $userInfo = $user->getSelf(Params::getFieldsParam($fields));

        // If scope is missing, prompt re-auth with required scopes
        if (isset($userInfo['error']['code']) && $userInfo['error']['code'] === 'scope_not_authorized') {
            $auth = new Authentication([
                'client_key' => config('services.tiktok.client_key'),
                'client_secret' => config('services.tiktok.client_secret'),
                'graph_version' => config('services.tiktok.graph_version', 'v2'),
            ]);

            $redirectUri = config('services.tiktok.redirect_uri');
            $scope = ['user.info.basic', 'user.info.profile', 'user.info.stats', 'video.list'];

            $authUrl = $auth->getAuthenticationUrl($redirectUri, $scope, csrf_token());
            return redirect()->away($authUrl);
        }

        return response()->json($userInfo);
    }
}
