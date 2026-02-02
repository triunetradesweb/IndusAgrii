<?php
session_start();

/* Destroy all session data */
$_SESSION = [];
session_destroy();

/* Redirect to homepage (index) */
header("Location: /IndusAgrii/public/index.php");
exit;
