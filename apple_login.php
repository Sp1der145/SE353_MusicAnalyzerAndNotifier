<?php
session_start();

$_SESSION['apple_pending'] = true;

header('Location: index.php?apple=WIP');
exit;