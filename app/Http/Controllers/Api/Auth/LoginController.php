<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 10;
    protected $decayMinutes = 5;

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'check']]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials, request('remember'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return response()->json(['message' => 'Successfully logged in.']);
        } else {
            $this->incrementLoginAttempts($request);

            return response()->json(
                ['error' => 'Unauthorized'],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    public function check()
    {
        return new JsonResponse(['isLoggedIn' => Auth::check()]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->loggedOut($request);
    }

    protected function loggedOut()
    {
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'favNotes' => $user->favNotes()->pluck('note_id')
        ]);
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json([
            "error" => [Lang::get('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
            "remain_seconds" => $seconds,
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }
}
