<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email=?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_assoc();

  if ($res && password_verify($password, $res['password'])) {
    $_SESSION['admin_id'] = $res['id'];
    header("Location: /IndusAgrii/admin/adminIndex.php");
    exit;
  } else {
    $error = "Invalid login credentials";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | Indus Agrii</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
<div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">

  <h1 class="text-2xl font-extrabold text-center text-emerald-800 mb-2">
    Indus Agrii Admin
  </h1>

  <p class="text-sm text-center text-gray-500 mb-6">
    Secure administrator access
  </p>

  <?php if ($error): ?>
    <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="post" class="space-y-4">
    <input type="email" name="email" autocomplete="username" placeholder="Admin Email" required
      class="w-full rounded-xl border px-4 py-3 focus:ring-emerald-700">
    <input type="password" name="password" autocomplete="current-password" placeholder="Password" required
      class="w-full rounded-xl border px-4 py-3 focus:ring-emerald-700">
    <button class="w-full bg-emerald-800 text-white font-bold py-3 rounded-xl">
      Login
    </button>
  </form>
</div>
</body>
</html>
