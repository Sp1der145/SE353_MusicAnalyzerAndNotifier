<?php
session_start();

$_SESSION['apple_pending'] = true;

header('Location: index.php?apple=coming_soon');
exit;