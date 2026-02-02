<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/includes/admin-auth.php";
require_once __DIR__ . "/../config/database.php";

/* ================= PAGINATION ================= */
$limit  = 10;
$page   = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

/* TOTAL COUNT */
$totalRes = $conn->query("
  SELECT COUNT(*) AS total
  FROM contact_enquiries
  WHERE is_deleted = 0
");
$totalRows  = (int) $totalRes->fetch_assoc()['total'];
$totalPages = max(1, ceil($totalRows / $limit));

$pageTitle = "Contact Enquiries | Indus Agrii";

/* ======================================================
   SINGLE ACTION HANDLERS
====================================================== */
if (isset($_GET['action'], $_GET['id'])) {
  $id = (int) $_GET['id'];

  if ($_GET['action'] === 'mark_read') {
    $conn->query("UPDATE contact_enquiries SET is_read = 1 WHERE id = $id");
  }

  if ($_GET['action'] === 'mark_unread') {
    $conn->query("UPDATE contact_enquiries SET is_read = 0 WHERE id = $id");
  }

  if ($_GET['action'] === 'delete') {
    $conn->query("UPDATE contact_enquiries SET is_deleted = 1 WHERE id = $id");
  }

  header("Location: admin_enquires.php?page=$page");
  exit;
}

/* ======================================================
   BULK ACTION HANDLER
====================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action'], $_POST['ids'])) {

  $ids = array_map('intval', $_POST['ids']);
  $affected = count($ids);

  if (!empty($ids)) {
    $idList = implode(',', $ids);

    if ($_POST['bulk_action'] === 'mark_read') {
      $conn->query("UPDATE contact_enquiries SET is_read = 1 WHERE id IN ($idList)");
      $_SESSION['toast'] = "$affected enquiries marked as read.";
    }

    if ($_POST['bulk_action'] === 'mark_unread') {
      $conn->query("UPDATE contact_enquiries SET is_read = 0 WHERE id IN ($idList)");
      $_SESSION['toast'] = "$affected enquiries marked as unread.";
    }

    if ($_POST['bulk_action'] === 'delete') {
      $conn->query("UPDATE contact_enquiries SET is_deleted = 1 WHERE id IN ($idList)");
      $_SESSION['toast'] = "$affected enquiries deleted successfully.";
    }
  }

  header("Location: admin_enquires.php?page=$page");
  exit;
}


/* ======================================================
   FETCH ENQUIRIES
====================================================== */
$enquiries = $conn->query("
  SELECT id, name, email, phone, message, created_at, is_read
  FROM contact_enquiries
  WHERE is_deleted = 0
  ORDER BY is_read ASC, created_at DESC
  LIMIT $limit OFFSET $offset
");
?>

<?php include __DIR__ . "/includes/admin-header.php"; ?>
<?php include __DIR__ . "/includes/admin-sidebar.php"; ?>

<div class="space-y-8">

  <!-- PAGE HEADER -->
  <div>
    <h1 class="text-2xl font-extrabold text-gray-900">Contact Enquiries</h1>
    <p class="text-sm text-gray-500">
      Messages submitted from the Contact page
    </p>
  </div>

  <!-- BULK ACTION BAR -->
  <form method="post" id="bulkForm" class="flex flex-wrap gap-3 items-center">

    <select name="bulk_action"
            id="bulkAction"
            required
            class="px-4 py-2 rounded-xl border text-sm">
      <option value="">Bulk Actions</option>
      <option value="mark_read">Mark as Read</option>
      <option value="mark_unread">Mark as Unread</option>
      <option value="delete">Delete</option>
    </select>

    <button type="button"
            onclick="openBulkModal()"
            class="px-4 py-2 rounded-xl bg-emerald-700 text-white text-sm font-semibold">
      Apply
    </button>

    <span class="text-sm text-gray-500">
      Select enquiries using checkboxes below
    </span>

  <!-- ENQUIRIES TABLE -->
  <div class="bg-white rounded-2xl shadow overflow-x-auto w-full">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-left">
        <tr>
          <th class="px-4 py-3">
            <input type="checkbox" id="selectAll">
          </th>
          <th class="px-4 py-3">Name</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Phone</th>
          <th class="px-4 py-3">Message</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Received On</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php if ($enquiries && $enquiries->num_rows > 0): ?>
          <?php while ($e = $enquiries->fetch_assoc()): ?>

            <tr class="border-t <?= !$e['is_read'] ? 'bg-emerald-50/40' : '' ?>">

              <td class="px-4 py-3">
                <input type="checkbox" name="ids[]"
                       value="<?= $e['id'] ?>"
                       class="rowCheck">
              </td>

              <td class="px-4 py-3 font-semibold">
                <?= htmlspecialchars($e['name']) ?>
              </td>

              <td class="px-4 py-3">
                <?= htmlspecialchars($e['email']) ?>
              </td>

              <td class="px-4 py-3">
                <?= $e['phone'] ? htmlspecialchars($e['phone']) : '-' ?>
              </td>

              <td class="px-4 py-3 max-w-lg">
                <p class="line-clamp-3 text-gray-600">
                  <?= nl2br(htmlspecialchars($e['message'])) ?>
                </p>
              </td>

              <td class="px-4 py-3">
                <?php if (!$e['is_read']): ?>
                  <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                               bg-emerald-100 text-emerald-800">
                    Unread
                  </span>
                <?php else: ?>
                  <span class="text-xs text-gray-400">Read</span>
                <?php endif; ?>
              </td>

              <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                <?= date('d M Y, h:i A', strtotime($e['created_at'])) ?>
              </td>

              <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                <?php if (!$e['is_read']): ?>
                  <a href="?action=mark_read&id=<?= $e['id'] ?>&page=<?= $page ?>"
                     class="px-3 py-1 rounded-lg bg-emerald-600 text-white text-xs font-semibold">
                    Mark Read
                  </a>
                <?php else: ?>
                  <a href="?action=mark_unread&id=<?= $e['id'] ?>&page=<?= $page ?>"
                     class="px-3 py-1 rounded-lg bg-gray-200 text-gray-800 text-xs font-semibold">
                    Mark Unread
                  </a>
                <?php endif; ?>

                <a href="?action=delete&id=<?= $e['id'] ?>&page=<?= $page ?>"
                   onclick="return confirm('Delete this enquiry?')"
                   class="px-3 py-1 rounded-lg bg-red-600 text-white text-xs font-semibold">
                  Delete
                </a>
              </td>
            </tr>

          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
              No enquiries received yet.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  </form>

  <!-- PAGINATION -->
  <?php if ($totalPages > 1): ?>
    <div class="flex justify-center gap-2 pt-6">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>"
           class="px-4 py-2 rounded-xl text-sm font-semibold
           <?= $i === $page ? 'bg-emerald-700 text-white' : 'bg-gray-100 hover:bg-gray-200' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>

</div>

<!-- BULK CONFIRM MODAL -->
<div id="bulkModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">

  <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
    <h3 class="text-lg font-bold text-gray-900">
      Confirm Bulk Action
    </h3>

    <p class="mt-2 text-sm text-gray-600">
      This action will be applied to all selected enquiries.
      Are you sure you want to continue?
    </p>

    <div class="mt-6 flex justify-end gap-3">
      <button type="button"
              onclick="closeBulkModal()"
              class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold">
        Cancel
      </button>

      <button type="button"
              onclick="submitBulkForm()"
              class="px-4 py-2 rounded-xl bg-emerald-700 text-white font-semibold">
        Yes, Apply
      </button>
    </div>
  </div>
</div>

<script>
  document.getElementById('selectAll')?.addEventListener('change', function () {
    document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = this.checked);
  });

  function openBulkModal() {
    const action = document.getElementById('bulkAction').value;
    const checked = document.querySelectorAll('.rowCheck:checked').length;

    if (!action || checked === 0) {
      alert('Please select an action and at least one enquiry.');
      return;
    }

    document.getElementById('bulkModal').classList.remove('hidden');
    document.getElementById('bulkModal').classList.add('flex');
  }

  function closeBulkModal() {
    document.getElementById('bulkModal').classList.add('hidden');
    document.getElementById('bulkModal').classList.remove('flex');
  }

  function submitBulkForm() {
    document.getElementById('bulkForm').submit();
  }
</script>
<?php if (!empty($_SESSION['toast'])): ?>
<div id="adminToast"
     class="fixed bottom-6 right-6 z-50 flex items-start gap-3
            rounded-2xl bg-white border border-emerald-200
            px-5 py-4 shadow-xl
            transition-all duration-300">

  <i class="fa-solid fa-circle-check text-emerald-600 text-xl mt-0.5"></i>

  <div class="text-sm">
    <p class="font-semibold text-gray-900">
      Action Successful
    </p>
    <p class="text-gray-600">
      <?= htmlspecialchars($_SESSION['toast']) ?>
    </p>
  </div>
</div>

<script>
  setTimeout(() => {
    const toast = document.getElementById('adminToast');
    if (toast) toast.classList.add('opacity-0', 'translate-y-2');
  }, 3200);

  setTimeout(() => {
    const toast = document.getElementById('adminToast');
    if (toast) toast.remove();
  }, 4000);
</script>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<?php include __DIR__ . "/includes/admin-footer.php"; ?>
