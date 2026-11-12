<?php
session_start();
$config = require 'config.php';

// User clicked Cancel on Spotify
if (isset($_GET['error'])) {
    header('Location: index.php?error=access_denied');
    exit;
}

// State must match the one saved in login.php
$expected = $_SESSION['oauth_state'] ?? '';
unset($_SESSION['oauth_state']);
if ($expected === '' || !hash_equals($expected, $_GET['state'] ?? '')) {
    header('Location: index.php?error=state_mismatch');
    exit;
}

// Trade the code for an access token
$ch = curl_init('https://accounts.spotify.com/api/token');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'grant_type'   => 'authorization_code',
        'code'         => $_GET['code'] ?? '',
        'redirect_uri' => $config['redirect_uri'],
    ]),
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' . base64_encode($config['client_id'] . ':' . $config['client_secret']),
        'Content-Type: application/x-www-form-urlencoded',
    ],
]);
$tokens = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($tokens['access_token'])) {
    header('Location: index.php?error=token_failed');
    exit;
}

// Keep tokens in the session only
$_SESSION['spotify'] = [
    'access_token' => $tokens['access_token'],
    'refresh_token' => $tokens['refresh_token'] ?? null,
    'expires_at' => time() + $tokens['expires_in'],
    'display_name' => $me['display_name']
];

$_SESSION['apple_music'] = [
    'developer_token' => $developerToken,
    'music_user_token' => $musicUserToken,
    'display_name' => $appleUserName
];

// Test the token: get the user's name
$ch = curl_init('https://api.spotify.com/v1/me');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $_SESSION['access_token']],
]);
$me = json_decode(curl_exec($ch), true);
curl_close($ch);

$_SESSION['display_name'] = $me['display_name'] ?? 'Spotify user';

header('Location: index.php');
exit;
