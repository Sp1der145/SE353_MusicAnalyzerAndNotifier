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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Music Analyzer and Notifier</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="topbar">
        <span class="brand">Music Analyzer</span>

        <nav>
            <a href="index.php" aria-current="page">Home</a>
            <a href="information.html">Information</a>
            <a href="contact.html">Contact</a>
        </nav>
    </header>

    <main>

        <section class="panel">

            <div class="title">
                <h1>Music Analyzer and Notifier</h1>
                <p>
                    Connect your music streaming account to analyze your listening habits,
                    favorite artists, top songs, and albums.
                </p>
            </div>

            <?php if ($error): ?>
                <p class="error">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <?php if ($spotifyConnected): ?>
                <p class="success">
                    ✓ Spotify Connected
                </p>
            <?php endif; ?>

            <?php if ($appleConnected): ?>
                <p class="success">
                    ✓ Apple Music Connected
                </p>
            <?php endif; ?>

            <div class="services">

                <a href="login.php"
                   class="service spotify"
                   style="--accent:#1DB954;">

                    <span class="dot"></span>

                    Spotify

                    <small>
                        Connect your Spotify account and view your top songs, artists, and albums.
                    </small>

                </a>

                <a href="apple_login.php"
                   class="service apple"
                   style="--accent:#FA243C;">

                    <span class="dot"></span>

                    Apple Music

                    <small>
                        Connect your Apple Music account and analyze your listening trends.
                    </small>

                </a>

            </div>

            <p class="notice">
                Your music data is used only for analysis and notification features.
                <strong>No streaming service passwords are stored.</strong>
            </p>

        </section>

    </main>

    <footer>
        <span>SE 353 01 : Software Engineering</span>
        <span>Group : Sego, Upton, Fisher</span>
    </footer>

</body>

</html>