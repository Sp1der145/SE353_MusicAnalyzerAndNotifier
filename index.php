<?php
session_start();

$errors = [
    'access_denied'  => 'Authorization was cancelled.',
    'state_mismatch' => 'Login could not be verified. Please try again.',
    'token_failed'   => 'Could not connect. Please try again later.',
];
$error = $errors[$_GET['error'] ?? ''] ?? null;
$spotifyConnected = !empty($_SESSION['spotify']);
$appleConnected   = !empty($_SESSION['apple_music']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MAaN - Music Analyzer and Notifier</title>
  <style>
    body     { margin: 0; font-family: Arial, sans-serif; background: #f6f6f4; }
    nav      { padding: 15px 25px; background: #1a1a1a; }
    nav a    { color: white; text-decoration: none; margin-right: 20px; }
    main     { max-width: 500px; margin: 50px auto; padding: 0 15px; text-align: center; }
    .btn     { display: block; padding: 15px; margin: 12px 0; border-radius: 8px;
               font-size: 18px; font-weight: bold; text-decoration: none; }
    .spotify { background: #1DB954; color: black; }
    .apple   { background: #FA243C; color: white; }
    .error   { padding: 10px; border-radius: 6px; background: #fde8e8; color: #8a1c1c; }
    .success { padding: 10px; border-radius: 6px; background: #e6f2ea; color: #1d5c2e; }
  </style>
</head>
<body>

  <nav>
    <a href="index.php">Home</a>
    <a href="information.php">Information</a>
    <a href="contact.php">Contact</a>
  </nav>

  <main>
    <h1>Music Analyzer and Notifier</h1>
    <p>Choose your streaming service:</p>

    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($spotifyConnected): ?>
    <p class="success">
        Spotify Connected
    </p>
    <?php endif; ?>

    <?php if ($appleConnected): ?>
    <p class="success">
        Apple Music Connected
    </p>
    <?php endif; ?>

    <a class="btn spotify" href="login.php">Continue with Spotify</a>
    <a class="btn apple" href="apple_login.php">Continue with Apple Music</a>
  </main>

</body>
</html>
