<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/includes/admin-auth.php";
require_once __DIR__ . "/../config/database.php";

$pageTitle = "User Details | Indus Agrii";

/* =========================================================
   FETCH USERS
========================================================= */
$sql = "
  SELECT
    u.id,
    u.name,
    u.email,
    u.created_at,

    (SELECT COUNT(*) FROM notify_requests n WHERE n.user_id = u.id) AS notify_count

  FROM users u
  ORDER BY u.created_at DESC
";

$users = $conn->query($sql);
?>

<?php include __DIR__ . "/includes/admin-header.php"; ?>
<?php include __DIR__ . "/includes/admin-sidebar.php"; ?>

<div class="space-y-6">

  <!-- PAGE TITLE -->
  <div>
    <h1 class="text-2xl font-extrabold text-gray-900">Users</h1>
    <p class="text-sm text-gray-500 mt-1">
      Registered customers on the platform
    </p>
  </div>

  <!-- USERS TABLE -->
  <div class="bg-white rounded-2xl shadow overflow-x-auto">

    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="px-5 py-4 text-left">User</th>
          <th class="px-5 py-4 text-left">Email</th>
          <th class="px-5 py-4 text-center">Notify Requests</th>
          <th class="px-5 py-4 text-left">Joined</th>
        </tr>
      </thead>

      <tbody class="divide-y">
        <?php if ($users && $users->num_rows > 0): ?>
          <?php while ($u = $users->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50 transition">

              <!-- USER -->
              <td class="px-5 py-4 font-semibold">
                <?= htmlspecialchars($u['name']) ?>
                <div class="text-xs text-gray-500">
                  ID: <?= (int)$u['id'] ?>
                </div>
              </td>

              <!-- EMAIL -->
              <td class="px-5 py-4">
                <?= htmlspecialchars($u['email']) ?>
              </td>

              <!-- NOTIFY -->
              <td class="px-5 py-4 text-center">
                <span class="px-2 py-1 rounded-full
                             bg-amber-100 text-amber-700 font-semibold">
                  <?= (int)$u['notify_count'] ?>
                </span>
              </td>

              <!-- JOINED -->
              <td class="px-5 py-4 text-gray-600">
                <?= date("d M Y", strtotime($u['created_at'])) ?>
              </td>

            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
              No users registered yet.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>

    </table>

  </div>

</div>

<?php include __DIR__ . "/includes/admin-footer.php"; ?>
