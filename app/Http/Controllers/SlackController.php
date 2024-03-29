<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

use App\User;

class SlackController extends Controller
{

    const URL_VERIFICATION = "url_verification";
    const COMMAND_IAM = "iam";
    const COMMAND_REGISTER = "register";

    public function command()
    {
        if ($this->verifyToken(request('token'))) {
            $args = preg_split('/[[:blank:]]/', request('text'));
            switch ($args[0]) {
                case self::COMMAND_IAM:
                    return $this->iam(request('user_id'));
                case self::COMMAND_REGISTER:
                    return $this->register(request('user_id'));
                default:
                    return $this->help();
            }
        } else {
            return response()->json(
                ['error' => 'token invalid'],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    private function iam($slack_id)
    {
        $user = User::where('slack_id', $slack_id)->first();
        if ($user) {
            $message = "Hi, {$user->name}! あなたのEn2::Webアカウントは既にSlackと連携済です。";
        } else {
            $message = "En2::Webに未登録、もしくはSlackアカウントとEn2::Webの連携が未完了です。";
        }
        return response()->json(['text' => $message]);
    }

    private function register($slack_id)
    {
        $user = User::where('slack_id', $slack_id)->first();
        $registerController = app()->make('App\Http\Controllers\Api\Auth\RegisterController');
        if ($user) {
            if ($user->status == 1) {
                $message = "既にEn2::Webに登録済みです。";
            } else {
                $message = $registerController->inboxRegisterUrl($user);
            }
        } else {
            $message = $registerController->preRegister($slack_id);
        }
        return response()->json(['text' => $message]);
    }

    private function help()
    {
        $texts = [
            "[Command List]",
            "iam",
            "  - Check your slack account is synced to En2::Web or not.",
            "register",
            "  - Sign up for En2::Web. You cannot use this command if you already registered."
        ];
        return response()->json(
            ['text' => implode("\n", $texts)]
        );
    }

    public function response()
    {
        if ($this->verifyToken(request('token'))) {
            switch (request('type')) {
                case self::URL_VERIFICATION:
                    return response()->json(
                        ['challenge' => request('challenge')]
                    );
                default:
                    return 'piyopiyo';
            }
        } else {
            return response()->json(
                ['error' => 'token invalid'],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    private function verifyToken($incomingToken)
    {
        return $incomingToken && $incomingToken === config('const.SLACK_VERIFICATION_TOKEN');
    }
}
