<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Provider\GenericProvider;

class LoginController extends Controller
{
    private $provider;

    public function __construct()
    {
        $this->provider = new GenericProvider([
            'clientId'                => env('OAUTH_APP_ID'),
            'clientSecret'            => env('OAUTH_APP_SECRET'),
            'redirectUri'             => env('OAUTH_REDIRECT_URI'),
            'urlAuthorize'            => env('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken'          => env('OAUTH_TOKEN_ENDPOINT'),
            'scopes'                  => env('OAUTH_SCOPES'),
            'urlResourceOwnerDetails' => '',
        ]);
    }

    public function login()
    {
        $this->provider->authorize();
    }

    public function logout()
    {
        Auth::guard('profile')->logout();
        Auth::guard('post')->logout();

        return redirect('home');
    }

    public function callback(Request $request)
    {
        $request->validate(['code' => ['required', 'alpha_dash']]);
        $code  = $request->input('code');

        try
        {
            $token = $this->provider->getAccessToken('authorization_code', ['code' => $code]);

            $response  = $this->provider->getAuthenticatedRequest('GET', 'https://graph.facebook.com/v9.0/me?fields=name,picture', $token);
            $contents = $this->provider->getParsedResponse($response);

            $user = User::find($contents['id']);

            if (empty($user))
            {
                $user = new User();
                $user->id       = $contents['id'];
                $user->name     = $contents['name'];
                $user->picture  = $contents['picture']['data']['url'];

            } else {
                $user->name     = $contents['name'];
                $user->picture  = $contents['picture']['data']['url'];
            }

            $user->save();

            Auth::guard('profile')->login($user, true);
            Auth::guard('post')->login($user, true);

            $id = $user->id;
            $name = $user->name;

            $signature = hash_hmac('sha256', $id.$name, env('HASH_SECRET_KEY'));
            $json = json_encode(['id' => $id, 'name' => $name, 'sig' => $signature]);

            $token = base64_encode($json);

            return view('dashboard', ['name' => $user->name, 'picture' => $user->picture, 'code' => $token]);
        }
        catch (\Exception $e)
        {
            error_log($e->getMessage());
            return view('error', ['message' => 'Sorry, we were unable to authenticate you at this time.']);
        }
    }
}
