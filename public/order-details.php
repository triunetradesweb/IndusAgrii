<?php
session_start();
require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: /IndusAgrii/public/index.php");
  exit;
}

$userId = (int)$_SESSION['user_id'];
$orderNumber = $_GET['order'] ?? '';

if (!$orderNumber) {
  header("Location: /IndusAgrii/public/orders.php");
  exit;
}

/* ================= FETCH ORDER ================= */
$stmt = $conn->prepare("
  SELECT id, order_number, total_amount, status, created_at
  FROM orders
  WHERE order_number = ? AND user_id = ?
  LIMIT 1
");
$stmt->bind_param("si", $orderNumber, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
  header("Location: /IndusAgrii/public/orders.php");
  exit;
}

/* ================= FETCH ORDER ITEMS ================= */
$stmt = $conn->prepare("
  SELECT product_id, product_name, pack_size, quantity, price
  FROM order_items
  WHERE order_id = ?
");
$stmt->bind_param("i", $order['id']);
$stmt->execute();
$items = $stmt->get_result();

$bodyClass = "orders-page bg-gray-50";
include "../includes/header.php";
?>

<section class="pt-28 pb-16">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-8">

    <!-- HEADER -->
    <div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-brand-dark">
        Order #<?= htmlspecialchars($order['order_number']) ?>
      </h1>
      <p class="mt-1 text-sm text-brand-muted">
        Placed on <?= date("d M Y", strtotime($order['created_at'])) ?>
      </p>
    </div>

    <!-- STATUS + TOTAL -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6
                flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

      <span class="inline-flex rounded-full px-4 py-1.5 text-sm font-semibold
                   bg-emerald-50 text-emerald-700 w-max">
        <?= ucfirst($order['status']) ?>
      </span>

      <p class="text-xl font-extrabold">
        ₹<?= number_format($order['total_amount'], 2) ?>
      </p>
    </div>

    <!-- ITEMS -->
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">

      <!-- DESKTOP -->
      <table class="hidden sm:table w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-left font-semibold">Product</th>
            <th class="px-6 py-4 font-semibold">Pack</th>
            <th class="px-6 py-4 font-semibold">Qty</th>
            <th class="px-6 py-4 text-right font-semibold">Price</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          <?php while ($item = $items->fetch_assoc()): ?>
            <tr>
              <td class="px-6 py-4 font-medium">
                <?= htmlspecialchars($item['product_name']) ?>
              </td>
              <td class="px-6 py-4"><?= (int)$item['pack_size'] ?> Kg</td>
              <td class="px-6 py-4"><?= (int)$item['quantity'] ?></td>
              <td class="px-6 py-4 text-right font-semibold">
                ₹<?= number_format($item['price'], 2) ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- MOBILE -->
      <div class="sm:hidden divide-y">
        <?php
        mysqli_data_seek($items, 0);
        while ($item = $items->fetch_assoc()):
        ?>
          <div class="p-5 space-y-1">
            <p class="font-semibold"><?= htmlspecialchars($item['product_name']) ?></p>
            <p class="text-sm text-brand-muted">
              <?= (int)$item['pack_size'] ?> Kg × <?= (int)$item['quantity'] ?>
            </p>
            <p class="font-semibold">
              ₹<?= number_format($item['price'], 2) ?>
            </p>
          </div>
        <?php endwhile; ?>
      </div>

    </div>

    <!-- ACTIONS -->
    <div class="flex flex-col sm:flex-row gap-3">

      <button
        id="reorderBtn"
        data-order-id="<?= (int)$order['id'] ?>"
        class="inline-flex items-center justify-center gap-2
               rounded-full bg-black text-white
               px-6 py-2.5 text-sm font-semibold
               disabled:opacity-60 disabled:cursor-not-allowed">
        <span class="btn-text">Reorder</span>
        <span class="btn-spinner hidden w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
      </button>

      <a href="/IndusAgrii/public/orders.php"
         class="inline-flex items-center justify-center
                rounded-full border border-gray-300
                px-6 py-2.5 text-sm font-semibold">
        Back to Orders
      </a>

    </div>

  </div>
</section>

<script>
const reorderBtn = document.getElementById("reorderBtn");

reorderBtn?.addEventListener("click", async () => {
  if (reorderBtn.disabled) return;

  const orderId = reorderBtn.dataset.orderId;

  // UI lock
  reorderBtn.disabled = true;
  reorderBtn.querySelector(".btn-text").textContent = "Adding items...";
  reorderBtn.querySelector(".btn-spinner").classList.remove("hidden");

  try {
    const res = await fetch("/IndusAgrii/public/reorder.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ order_id: orderId })
    });

    const data = await res.json();

    if (data.success) {
      showToast(
        "Items Added to Cart",
        "You can review them before checkout.",
        "success"
      );
    } else {
      throw new Error();
    }
  } catch {
    showToast(
      "Unable to Reorder",
      "Please try again in a moment.",
      "warning"
    );
  } finally {
    reorderBtn.disabled = false;
    reorderBtn.querySelector(".btn-text").textContent = "Reorder";
    reorderBtn.querySelector(".btn-spinner").classList.add("hidden");
  }
});
</script>

<?php include "../includes/footer.php"; ?>
