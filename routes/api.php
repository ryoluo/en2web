<?php

use Illuminate\Http\Request;
use App\Facades\Slack;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->post('/', function (Request $request) {
//     return $request->user();
// });

// Route::get('/send/line/message', function() {
//     $url = 'https://api.line.me/v2/bot/message/push';
//     $channelToken = 'sv/RKt1C3qskQg0Uh5Xdll9aXWvy42rty+y9gdYtQjzQ5AOMMKOgPPU6yTAuxkoRsTXsWVSmv648F5wXHJzpvWPCkUSnfdjuxj91YZIr7Np4rGPlgFFbsAFeyuL6I8nUFSYZCQvvEZkfHngYPfAtUgdB04t89/1O/w1cDnyilFU=';
//     $headers = [
//         'Authorization: Bearer ' . $channelToken,
//         'Content-Type: application/json; charset=utf-8',
//     ];
//     $content = json_encode([
//         'to' => 'C41cf84a10b0bb1c546d925d74e295b60',
//         'messages' => [
//             ['type' => 'text', 'text' => '送信テストおおおおおおおおおおおお'],
//         ]
//     ]);
//     $options = array (
//         'http' => array (
//             'method' => 'POST',
//             'header' => $headers,
//             'content' => $content,
//             'ignore_errors' => true,
//             'protocol_version' => '1.1'
//             ),
//         'ssl' => array (
//             'verify_peer' => false,
//             'verify_peer_name' => false
//             )
//         );
//     $response = file_get_contents($url, false, stream_context_create($options));
//     return $response;
// });

Route::post('/webhook/line', 'LineApiController@response');
// function(Request $request) {
//     http_response_code();
//     echo '200 {}';
//     $json_string = $request->getContent();
//     $json_object = json_decode($json_string, true);
//     Log::info($json_object);
//     Log::info('LINE API works!');
    // $replyToken = $json->events[0]->replyToken;
    // // JSONデータから送られてきたメッセージを取得
    // $message = $json->events[0]->message->text;

    // // HTTPヘッダを設定
    // $channelToken = 'sv/RKt1C3qskQg0Uh5Xdll9aXWvy42rty+y9gdYtQjzQ5AOMMKOgPPU6yTAuxkoRsTXsWVSmv648F5wXHJzpvWPCkUSnfdjuxj91YZIr7Np4rGPlgFFbsAFeyuL6I8nUFSYZCQvvEZkfHngYPfAtUgdB04t89/1O/w1cDnyilFU=';
    // $headers = [
    //     'Authorization: Bearer ' . $channelToken,
    //     'Content-Type: application/json; charset=utf-8',
    // ];

    // // POSTデータを設定してJSONにエンコード
    // $post = [
    //     'replyToken' => $replyToken,
    //     'messages' => [
    //         [
    //             'type' => 'text',
    //             'text' => '「' . $message . '」',
    //         ],
    //     ],
    // ];
    // $post = json_encode($post);

    // // HTTPリクエストを設定
    // $ch = curl_init('https://api.line.me/v2/bot/message/reply');
    // $options = [
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_HTTPHEADER => $headers,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_BINARYTRANSFER => true,
    //     CURLOPT_HEADER => true,
    //     CURLOPT_POSTFIELDS => $post,
    // ];

    // // 実行
    // curl_setopt_array($ch, $options);

    // // エラーチェック
    // $result = curl_exec($ch);
    // $errno = curl_errno($ch);
    // if ($errno) {
    //     return;
    // }

    // // HTTPステータスを取得
    // $info = curl_getinfo($ch);
    // $httpStatus = $info['http_code'];

    // $responseHeaderSize = $info['header_size'];
    // $body = substr($result, $responseHeaderSize);

    // // 200 だったら OK
    // echo $httpStatus . ' ' . $body;
// });