<?php
session_start();
$config = require 'config.php';
require 'spotify.php';

if (empty($_SESSION['access_token'])) {
    header('Location: index.php');
    exit;
}

$period = $_GET['period'] ?? 'month';
if (!isset(SPOTIFY_PERIODS[$period])) {
    $period = 'month';
}

$length = (int) ($_GET['length'] ?? 10);
if (!in_array($length, SPOTIFY_LENGTHS, true)) {
    $length = 10;
}

$tracks  = [];
$artists = [];
$error   = null;

try {
    if ($period === 'week') {
        ['tracks' => $tracks, 'artists' => $artists] = spotify_week($config, $length);
    } else {
        $tracks  = spotify_top($config, 'tracks', SPOTIFY_RANGES[$period], $length);
        $artists = spotify_top($config, 'artists', SPOTIFY_RANGES[$period], $length);
    }
} catch (RuntimeException $e) {
    if ($e->getCode() === 401) {
        spotify_forget();
        header('Location: index.php?error=session_expired');
        exit;
    }
    $error = 'Could not load your Spotify data. Please try again later.';
}

$sections = ['Top Songs' => $tracks, 'Top Artists' => $artists];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Your Summary - MAaN</title>
  <style>
    body     { margin: 0; font-family: Arial, sans-serif; background: #f6f6f4; }
    nav      { padding: 15px 25px; background: #1a1a1a; }
    nav a    { color: white; text-decoration: none; margin-right: 20px; }
    main     { max-width: 900px; margin: 40px auto; padding: 0 15px; }
    h1       { text-align: center; }
    form     { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; align-items: end; margin-bottom: 25px; }
    label    { display: flex; flex-direction: column; font-size: 14px; font-weight: bold; gap: 4px; }
    select   { padding: 8px; border-radius: 6px; border: 1px solid #ccc; font-size: 16px; }
    button   { padding: 9px 18px; border: 0; border-radius: 6px; background: #1DB954; font-size: 16px; font-weight: bold; cursor: pointer; }
    .lists   { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; }
    .list    { background: white; border-radius: 8px; padding: 15px 20px; }
    ol       { padding-left: 0; list-style: none; counter-reset: rank; margin: 0; }
    li       { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid #eee; counter-increment: rank; }
    li::before { content: counter(rank); width: 28px; text-align: right; font-weight: bold; color: #777; }
    li img, .blank { width: 44px; height: 44px; border-radius: 4px; object-fit: cover; background: #ddd; flex-shrink: 0; }
    .name    { font-weight: bold; color: #1a1a1a; text-decoration: none; }
    .detail  { font-size: 13px; color: #666; }
    .plays   { margin-left: auto; font-size: 13px; color: #666; white-space: nowrap; }
    .note    { text-align: center; font-size: 14px; color: #666; }
    .error   { padding: 10px; border-radius: 6px; background: #fde8e8; color: #8a1c1c; text-align: center; }
    .logout  { display: block; text-align: center; margin-top: 30px; color: #8a1c1c; }
  </style>
</head>
<body>

  <nav>
    <a href="index.php">Home</a>
    <a href="summary.php">My Summary</a>
    <a href="information.php">Information</a>
    <a href="contact.php">Contact</a>
  </nav>

  <main>
    <h1><?= htmlspecialchars($_SESSION['display_name'] ?? 'Spotify user') ?>'s Summary</h1>

    <form method="get" action="summary.php">
      <label>Time period
        <select name="period">
          <?php foreach (SPOTIFY_PERIODS as $value => $label): ?>
            <option value="<?= $value ?>" <?= $value === $period ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Summary length
        <select name="length">
          <?php foreach (SPOTIFY_LENGTHS as $value): ?>
            <option value="<?= $value ?>" <?= $value === $length ? 'selected' : '' ?>>Top <?= $value ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <button type="submit">Update</button>
    </form>

    <?php if ($period === 'week'): ?>
      <p class="note">Weekly results are counted from your 50 most recent plays, the most Spotify shares.</p>
    <?php endif; ?>

    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>
      <div class="lists">
        <?php foreach ($sections as $title => $items): ?>
          <section class="list">
            <h2><?= $title ?></h2>
            <?php if (!$items): ?>
              <p class="note">No listening data for this period yet.</p>
            <?php else: ?>
              <?php if (count($items) < $length): ?>
                <p class="note">Only <?= count($items) ?> available for this period.</p>
              <?php endif; ?>
              <ol>
                <?php foreach ($items as $item): ?>
                  <li>
                    <?php if ($item['image']): ?>
                      <img src="<?= htmlspecialchars($item['image']) ?>" alt="">
                    <?php else: ?>
                      <span class="blank"></span>
                    <?php endif; ?>
                    <div>
                      <?php if ($item['url']): ?>
                        <a class="name" href="<?= htmlspecialchars($item['url']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($item['name']) ?></a>
                      <?php else: ?>
                        <span class="name"><?= htmlspecialchars($item['name']) ?></span>
                      <?php endif; ?>
                      <?php if ($item['detail'] !== ''): ?>
                        <div class="detail"><?= htmlspecialchars($item['detail']) ?></div>
                      <?php endif; ?>
                    </div>
                    <?php if ($item['plays'] !== null): ?>
                      <span class="plays"><?= $item['plays'] ?> <?= $item['plays'] === 1 ? 'play' : 'plays' ?></span>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ol>
            <?php endif; ?>
          </section>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <a class="logout" href="logout.php">Disconnect Spotify and clear my session</a>
  </main>

</body>
</html>