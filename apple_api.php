<?php

function appleRequest($endpoint)
{
    $config = require 'apple_config.php';

    $ch = curl_init(
        $config['api_base'] . ltrim($endpoint, '/')
    );

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' .
            $config['developer_token'],
            'Music-User-Token: ' .
            $_SESSION['apple_music']['music_user_token']
        ]
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}