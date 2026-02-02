<?php
session_start();
require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: /IndusAgrii/public/index.php");
  exit;
}

$userId = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
  SELECT id, order_number, total_amount, status, created_at
  FROM orders
  WHERE user_id = ?
  ORDER BY created_at DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result();

$bodyClass = "orders-page bg-gray-50";
include "../includes/header.php";
?>

<section class="pt-28 pb-16">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <!-- PAGE HEADER -->
    <div class="mb-10">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-brand-dark">
        My Orders
      </h1>
      <p class="mt-1 text-sm text-brand-muted">
        Track your previous purchases
      </p>
    </div>

    <?php if ($orders->num_rows === 0): ?>

      <!-- EMPTY STATE -->
      <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">
        <div class="mx-auto mb-4 w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
          <i class="fa-solid fa-receipt text-gray-400"></i>
        </div>

        <p class="text-lg font-semibold text-brand-dark">
          No orders yet
        </p>

        <p class="mt-2 text-sm text-brand-muted max-w-sm mx-auto">
          You haven’t placed any orders yet. Start shopping to see your orders here.
        </p>

        <a href="/IndusAgrii/public/products.php"
           class="mt-6 inline-flex items-center gap-2
                  rounded-full bg-black text-white
                  px-6 py-2.5
                  text-sm font-semibold">
          Start Shopping →
        </a>
      </div>

    <?php else: ?>

      <!-- DESKTOP TABLE -->
      <div class="hidden md:block bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-left">
            <tr>
              <th class="px-6 py-4 font-semibold">Order</th>
              <th class="px-6 py-4 font-semibold">Date</th>
              <th class="px-6 py-4 font-semibold">Status</th>
              <th class="px-6 py-4 font-semibold text-right">Amount</th>
              <th class="px-6 py-4 font-semibold text-right"></th>
            </tr>
          </thead>

          <tbody class="divide-y">
            <?php while ($order = $orders->fetch_assoc()): ?>
              <tr
                class="cursor-pointer hover:bg-gray-50 transition"
                onclick="window.location.href='/IndusAgrii/public/order-details.php?order=<?= urlencode($order['order_number']) ?>'">

                <td class="px-6 py-4 font-medium">
                  #<?= htmlspecialchars($order['order_number']) ?>
                </td>

                <td class="px-6 py-4 text-gray-600">
                  <?= date("d M Y", strtotime($order['created_at'])) ?>
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                               bg-emerald-50 text-emerald-700">
                    <?= htmlspecialchars(ucfirst($order['status'])) ?>
                  </span>
                </td>

                <td class="px-6 py-4 text-right font-semibold">
                  ₹<?= number_format($order['total_amount'], 2) ?>
                </td>

                <td class="px-6 py-4 text-right text-gray-400">
                  View →
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- MOBILE CARDS -->
      <div class="md:hidden space-y-4">
        <?php
        mysqli_data_seek($orders, 0);
        while ($order = $orders->fetch_assoc()):
        ?>
          <div
            class="bg-white border border-gray-200 rounded-2xl p-5 space-y-2
                   cursor-pointer hover:border-gray-300 transition"
            onclick="window.location.href='/IndusAgrii/public/order-details.php?order=<?= urlencode($order['order_number']) ?>'">

            <div class="flex justify-between items-center">
              <p class="font-semibold">
                #<?= htmlspecialchars($order['order_number']) ?>
              </p>

              <span class="text-xs font-semibold
                           bg-emerald-50 text-emerald-700
                           px-3 py-1 rounded-full">
                <?= htmlspecialchars(ucfirst($order['status'])) ?>
              </span>
            </div>

            <p class="text-sm text-brand-muted">
              <?= date("d M Y", strtotime($order['created_at'])) ?>
            </p>

            <p class="font-semibold">
              ₹<?= number_format($order['total_amount'], 2) ?>
            </p>

            <div class="pt-2 text-sm font-semibold text-gray-500 flex justify-end">
              View Details →
            </div>
          </div>
        <?php endwhile; ?>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php include "../includes/footer.php"; ?>
