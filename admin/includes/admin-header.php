<?php
// admin/includes/admin-header.php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($pageTitle)) {
  $pageTitle = "Admin Panel | Indus Agrii";
}

/* HANDLE LOGOUT FROM HERE ONLY */
if (isset($_GET['logout']) && $_GET['logout'] === '1') {
  session_unset();
  session_destroy();
  header("Location: includes/adminLogin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="bg-gray-100 text-gray-900">

<!-- TOP NAV -->
<header class="fixed top-0 inset-x-0 h-16 bg-white shadow z-50 flex items-center justify-between px-4 md:px-6">

  <!-- LEFT -->
  <div class="flex items-center gap-3">
<button
  class="md:hidden text-xl"
  onclick="
    const s = document.getElementById('adminSidebar');
    s.classList.toggle('-translate-x-full');
    s.classList.toggle('pointer-events-none');
  ">
  ☰
</button>



    <span class="font-bold text-lg tracking-wide text-emerald-800">
      Indus Agrii Admin
    </span>
  </div>

  <!-- RIGHT -->
  <div class="flex items-center gap-4">
    <span class="hidden sm:block text-sm text-gray-600">
      Welcome, Admin
    </span>

    <a href="?logout=1"
   class="text-sm font-semibold text-red-600">
  Logout
</a>
  </div>
</header>

<!-- LAYOUT WRAPPER -->
<div class="flex pt-16">

