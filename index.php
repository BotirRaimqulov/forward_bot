<?php
ob_start();
error_reporting(0);
mkdir("media_group");
define("API_KEY", 'TELEGRAM BOT TOKEN');
function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/$method";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);

    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}
$update = json_decode(file_get_contents("php://input"));

if (isset($update->channel_post) && $update->channel_post->sender_chat->id == -1001970544234) {
    $message = $update->channel_post;
    $channel_id = $message->sender_chat->id;
    $message_id = $message->message_id;
    $media_group_id = $message->media_group_id;

    if (isset($media_group_id)) {
        $ids = json_decode(file_get_contents("media_group/{$media_group_id}.json"), true) ?? [];
        $ids[] = $message_id;
        file_put_contents("media_group/{$media_group_id}.json", json_encode($ids));
    } else {
        bot('forwardMessage', [
            'chat_id' => "@bots_offers",
            'from_chat_id' => $channel_id,
            'message_id' => $message_id,
        ]);
    }
}


//echo file_get_contents('https://api.telegram.org/bot' . API_KEY . '/deletewebhook?drop_pending_updates=true');
echo file_get_contents('https://api.telegram.org/bot' . API_KEY . '/setwebhook?url=your_site_url/index.php&allowed_updates=["message","channel_post","edited_channel_post", "callback_query"]');
