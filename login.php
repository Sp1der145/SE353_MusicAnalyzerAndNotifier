<?php
session_start();
$config = require 'config.php';

$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

header('Location: https://accounts.spotify.com/authorize?' . http_build_query([
    'response_type' => 'code',
    'client_id'     => $config['client_id'],
    'scope'         => $config['scope'],
    'redirect_uri'  => $config['redirect_uri'],
    'state'         => $state,
]));
exit;
