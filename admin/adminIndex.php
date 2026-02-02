<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/includes/admin-auth.php";
require_once __DIR__ . "/../config/database.php";

$pageTitle = "Dashboard | Indus Agrii";

/* =========================================================
   DASHBOARD STATS (SYNCED WITH CURRENT SCHEMA)
========================================================= */

// Total products
$totalProducts = $conn
  ->query("SELECT COUNT(*) FROM products")
  ->fetch_row()[0];

// Rice products (via slug)
$riceProducts = $conn
  ->query("SELECT COUNT(*) FROM products WHERE slug='rice'")
  ->fetch_row()[0];

// Millets products (via slug)
$milletProducts = $conn
  ->query("SELECT COUNT(*) FROM products WHERE slug='millets'")
  ->fetch_row()[0];
?>

<?php include __DIR__ . "/includes/admin-header.php"; ?>
<?php include __DIR__ . "/includes/admin-sidebar.php"; ?>

<div class="space-y-8">

  <!-- PAGE TITLE -->
  <div>
    <h1 class="text-2xl font-extrabold text-gray-900">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">
      Overview of your store
    </p>
  </div>

  <!-- STATS GRID -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="bg-white rounded-2xl shadow p-6">
      <p class="text-sm text-gray-500">Total Products</p>
      <h3 class="text-3xl font-bold text-emerald-800 mt-2">
        <?= $totalProducts ?>
      </h3>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
      <p class="text-sm text-gray-500">Rice Products</p>
      <h3 class="text-3xl font-bold text-emerald-800 mt-2">
        <?= $riceProducts ?>
      </h3>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
      <p class="text-sm text-gray-500">Millets Products</p>
      <h3 class="text-3xl font-bold text-emerald-800 mt-2">
        <?= $milletProducts ?>
      </h3>
    </div>

  </div>

  <!-- QUICK ACTIONS -->
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>

    <div class="flex flex-wrap gap-4">
      <a href="/IndusAgrii/admin/adminProducts.php"
         class="px-5 py-3 rounded-xl bg-emerald-800 text-white font-semibold">
        Manage Products
      </a>

      <a href="/IndusAgrii/"
         target="_blank"
         class="px-5 py-3 rounded-xl bg-gray-100 font-semibold">
        Visit Website
      </a>
    </div>
  </div>

</div>

<?php include __DIR__ . "/includes/admin-footer.php"; ?>
