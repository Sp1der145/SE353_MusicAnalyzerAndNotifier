<?php

session_start();

function spotifyRequest($endpoint)
{
    $ch = curl_init(
        'https://api.spotify.com/v1/' .
        ltrim($endpoint, '/')
    );

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' .
            $_SESSION['spotify']['access_token']
        ]
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function getSpotifyTopSongs()
{
    return spotifyRequest(
        'me/top/tracks?limit=10'
    );
}

function getSpotifyTopArtists()
{
    return spotifyRequest(
        'me/top/artists?limit=10'
    );
}

function getSpotifyTopAlbums()
{
    $tracks = getSpotifyTopSongs();

    $albums = [];

    foreach ($tracks['items'] as $track)
    {
        $albums[] = $track['album']['name'];
    }

    return array_unique($albums);
}