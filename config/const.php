<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Const
    |--------------------------------------------------------------------------
    */

    // 0:仮登録 1:本登録 2:メール認証済 9:退会済
    'USER_STATUS' => [
        'PRE_REGISTER' => '0',
        'REGISTER' => '1',
        'MAIL_AUTHED' => '2',
        'DEACTIVE' => '9',
    ],
    'LINE_CHANNEL_TOKEN' => env('LINE_CHANNEL_TOKEN'),
    'LINE_MY_USER_ID' => env('LINE_MY_USER_ID'),
    'LINE_EN2_KYOYU_GROUP_ID' => env('LINE_EN2_KYOYU_GROUP_ID'),
    'LINE_EN2_GROUP_ID' => env('LINE_EN2_GROUP_ID'),
    'SLACK_WEBHOOK_URI' => env('SLACK_WEBHOOK_URI'),
    'GOOGLE_CALENDAR_ID' => env('GOOGLE_CALENDAR_ID'),
    'SLACK_VERIFICATION_TOKEN' => env('SLACK_VERIFICATION_TOKEN'),
    'SLACK_BOT_OAUTH_TOKEN' => env('SLACK_BOT_OAUTH_TOKEN'),
    'SLACK_CLIENT_ID' => env('SLACK_CLIENT_ID'),
    'SLACK_CLIENT_SECRET' => env('SLACK_CLIENT_SECRET'),
    'SLACK_RYO_KOBAYASHI' => "US5GTG60K",
];
