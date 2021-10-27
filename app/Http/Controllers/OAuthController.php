<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;

class OAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->client_id = config('const.SLACK_CLIENT_ID');
        $this->client_secret = config('const.SLACK_CLIENT_SECRET');
        $this->redirect_uri = app()->isLocal()
            ? 'https://dev.en2ynu.com/login/slack'
            : 'https://en2ynu.com/login/slack';
    }

    public function redirect()
    {
        $token = csrf_token();
        $nonce = uniqid(true);
        request()->session()->put('nonce', $nonce);
        $to = "https://slack.com/openid/connect/authorize" .
            "?response_type=code" .
            "&scope=openid" .
            "&state=$token" .
            "&nonce=$nonce" .
            "&client_id=$this->client_id" .
            "&redirect_uri=$this->redirect_uri";
        return redirect($to);
    }

    public function login()
    {
        // validate state
        if (csrf_token() !== request('state')) {
            abort(401);
        }
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', "https://slack.com/api/openid.connect.token", [
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'code' => request('code'),
                'redirect_uri' => $this->redirect_uri
            ]
        ]);
        $status = $res->getStatusCode();
        if ($status !== 200) {
            abort(401);
        }
        $contents = json_decode($res->getBody()->getContents());
        if (!$contents->ok) {
            abort(401);
        }
        $payload = $this->extractJWTPayload($contents->id_token);

        // validate nonce
        $session_nonce = request()->session()->pull('nonce');
        if ($session_nonce !== $payload->nonce) {
            abort(401);
        }
        $slack_id = $payload->{'https://slack.com/user_id'};
        $user = User::where('slack_id', $slack_id)->first();
        if (!$user) {
            return redirect('/error/slack');
        }
        Auth::login($user, true);
        request()->session()->regenerate();
        return view('app');
    }

    private function extractJWTPayload($id_token)
    {
        $id_token = explode('.', $id_token);
        return json_decode(base64_decode($id_token[1]));
    }
}
