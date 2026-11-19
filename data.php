<?php

session_start();
require 'music_data.php';

if (!empty($_SESSION['spotify']))
{
    $provider = 'spotify';
}
elseif (!empty($_SESSION['apple_music']))
{
    $provider = 'apple';
}
else
{
    die('No music account connected.');
}

$data = getUserMusicData($provider);

<h2>Top Songs</h2>

<ul>
<?php foreach ($data['songs']['items'] as $song): ?>
    <li>
        <?= htmlspecialchars($song['name']) ?>
    </li>
<?php endforeach; ?>
</ul>

<?= htmlspecialchars($artist['name']) ?>
<?= htmlspecialchars(
    $artist['attributes']['name']
) ?>

[
  [
     "name" => "Artist Name"
  ]
]

[
    'songs' => [
        ['name' => 'Song 1'],
        ['name' => 'Song 2']
    ],

    'artists' => [
        ['name' => 'Artist 1']
    ],

    'albums' => [
        ['name' => 'Album 1']
    ]
]