<?php
session_start();

/*
Future Apple callback handling:

1. Verify developer token
2. Receive user authorization
3. Generate music user token
4. Store token
5. Fetch Apple profile information
*/

header('Location: index.php');
exit;