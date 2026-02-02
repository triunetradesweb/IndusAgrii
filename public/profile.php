<?php
session_start();
require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: /IndusAgrii/public/index.php");
  exit;
}

$userId = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
  SELECT name, email, created_at
  FROM users
  WHERE id = ?
  LIMIT 1
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$bodyClass = "profile-page bg-gray-50";
include "../includes/header.php";
?>


<section class="pt-28 pb-16">
  <div class="max-w-5xl mx-auto px-6">

    <!-- PAGE HEADER -->
    <div class="mb-10">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-brand-dark">
        My Profile
      </h1>
      <p class="mt-1 text-sm text-brand-muted">
        Manage your personal information
      </p>
    </div>

    <!-- PROFILE CARD -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8">

      <div class="flex flex-col sm:flex-row sm:items-center gap-6">

        <!-- AVATAR -->
        <div class="shrink-0">
          <div
            class="w-20 h-20 rounded-full
                   bg-gradient-to-br from-emerald-500 to-emerald-700
                   flex items-center justify-center
                   text-white text-2xl font-bold">
            <?= strtoupper(substr($user['name'], 0, 1)) ?>
          </div>
        </div>

        <!-- USER INFO -->
        <div class="flex-1 space-y-2">
          <p class="text-lg font-semibold text-brand-dark">
            <?= htmlspecialchars($user['name']) ?>
          </p>

          <p class="text-sm text-brand-muted">
            <?= htmlspecialchars($user['email']) ?>
          </p>

          <p class="text-xs text-gray-500">
            Member since <?= date("F Y", strtotime($user['created_at'])) ?>
          </p>
        </div>

      </div>

      <!-- DIVIDER -->
      <div class="my-8 h-px bg-gray-200"></div>

      <!-- ACTIONS -->
      <div class="flex flex-col sm:flex-row gap-3">

        <a href="/IndusAgrii/public/orders.php"
           class="inline-flex items-center justify-center
                  rounded-full
                  bg-black text-white
                  px-6 py-2.5
                  text-sm font-semibold">
          View Orders
        </a>

        <button
          disabled
          class="inline-flex items-center justify-center
                 rounded-full
                 border border-gray-300
                 px-6 py-2.5
                 text-sm font-semibold
                 text-gray-400 cursor-not-allowed">
          Edit Profile (Coming Soon)
        </button>

      </div>

    </div>
  </div>
</section>

<?php include "../includes/footer.php"; ?>
