<?php

session_start();

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
            $_SESSION['apple_music']['developer_token'],

            'Music-User-Token: ' .
            $_SESSION['apple_music']['music_user_token']
        ]
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function getAppleTopSongs()
{
    return appleRequest('me/recent/played/tracks');
}

function getAppleLibrarySongs()
{
    return appleRequest('me/library/songs');
}

function getAppleLibraryAlbums()
{
    return appleRequest('me/library/albums');
}

function getAppleLibraryArtists()
{
    return appleRequest('me/library/artists');
}