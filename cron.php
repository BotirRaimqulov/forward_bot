<?php

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

$mediaGroupFolder = "media_group";
$jsonFiles = glob("$mediaGroupFolder/*.json");

foreach ($jsonFiles as $jsonFile) {
    $mediaGroupId = pathinfo($jsonFile, PATHINFO_FILENAME);
    $content = file_get_contents($jsonFile);
    $data = json_decode($content, true);
    bot('forwardMessages', [
        'chat_id' => "@bots_offers",
        'from_chat_id' => -1001970544234,
        'message_ids' => $content,
    ]);
    unlink($jsonFile);
}
exit;
