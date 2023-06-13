<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\User;
use Illuminate\Support\Facades\Log;
use Discord\Interaction;
use Discord\InteractionType;
use Discord\InteractionResponseType;

class DiscordController extends Controller
{

    const URL_VERIFICATION = "url_verification";
    const COMMAND_IAM = "iam";
    const COMMAND_REGISTER = "register";

    public function command()
    {
        if (!$this->isVerified()) {
            return response()->json(
                ['error' => 'invalid request signature'],
                Response::HTTP_UNAUTHORIZED
            );
        }
        $type = request()->get('type');
        switch ($type) {
            case InteractionType::PING:
                return response()->json([
                    'type' => InteractionResponseType::PONG
                ]);
            case InteractionType::APPLICATION_COMMAND:
                Log::info(request()->getContent());
                return response();
            default:
                return response()->json(
                    ['error' => 'unknown interaction type'],
                    Response::HTTP_BAD_REQUEST
                );
        }
    }

    private function isVerified()
    {
        $content = request()->getContent();
        $signature = request()->headers->get('X-Signature-Ed25519');
        $timestamp = request()->headers->get('X-Signature-Timestamp');
        $key = config('const.DISCORD_PUBLIC_KEY');
        return Interaction::verifyKey($content, $signature, $timestamp, $key);
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
}
