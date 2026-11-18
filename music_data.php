<?php

require_once 'spotify_api.php';
require_once 'apple_api.php';

function getUserMusicData($provider)
{
    switch ($provider)
    {
        case 'spotify':
            return [
                'songs' => getSpotifyTopSongs(),
                'artists' => getSpotifyTopArtists(),
                'albums' => getSpotifyTopAlbums()
            ];

        case 'apple':
            return [
                'songs' => getAppleLibrarySongs(),
                'artists' => getAppleLibraryArtists(),
                'albums' => getAppleLibraryAlbums()
            ];
    }

    return [];
}