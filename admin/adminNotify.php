<?php
require_once __DIR__ . "/includes/admin-auth.php";
require_once __DIR__ . "/../config/database.php";

$requests = $conn->query("
  SELECT *
  FROM notify_requests
  ORDER BY created_at DESC
");
?>

<?php include __DIR__ . "/includes/admin-header.php"; ?>
<?php include __DIR__ . "/includes/admin-sidebar.php"; ?>

<div class="space-y-6">

  <h1 class="text-2xl font-extrabold">Notify Requests</h1>

  <div class="bg-white rounded-2xl shadow overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 text-left">Product</th>
          <th class="p-3 text-left">Phone</th>
          <th class="p-3 text-left">Message</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = $requests->fetch_assoc()): ?>
          <tr class="border-t">
            <td class="p-3 font-semibold"><?= htmlspecialchars($r['product_name']) ?></td>
            <td class="p-3"><?= htmlspecialchars($r['phone']) ?></td>
            <td class="p-3 text-gray-600"><?= htmlspecialchars($r['message']) ?></td>
            <td class="p-3">
              <?= $r['is_notified']
                ? '<span class="text-emerald-700 font-semibold">Notified</span>'
                : '<span class="text-red-600 font-semibold">Pending</span>' ?>
            </td>
            <td class="p-3 text-gray-500">
              <?= date("d M Y", strtotime($r['created_at'])) ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>

<?php include __DIR__ . "/includes/admin-footer.php"; ?>
