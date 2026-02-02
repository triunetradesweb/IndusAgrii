<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/* SINGLE SOURCE OF TRUTH */
if (!isset($_SESSION['admin_id'])) {
  header("Location: includes/adminLogin.php");
  exit;
}
