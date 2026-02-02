<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* ================= CART INIT ================= */
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
if (!isset($_SESSION['saved'])) {
  $_SESSION['saved'] = [];
}

/* ================= MOVE BACK FROM SAVED ================= */
if (isset($_GET['add_back'])) {
  $key = $_GET['add_back'];
  if (isset($_SESSION['saved'][$key])) {
    $_SESSION['cart'][$key] = $_SESSION['saved'][$key];
    unset($_SESSION['saved'][$key]);
  }
  header("Location: cart.php");
  exit;
}

/* ================= REMOVE ITEM ================= */
if (isset($_GET['remove'])) {
  unset($_SESSION['cart'][$_GET['remove']]);
  header("Location: cart.php");
  exit;
}

/* ================= SAVE FOR LATER ================= */
if (isset($_GET['save'])) {
  $key = $_GET['save'];
  if (isset($_SESSION['cart'][$key])) {
    $_SESSION['saved'][$key] = $_SESSION['cart'][$key];
    unset($_SESSION['cart'][$key]);
  }
  header("Location: cart.php");
  exit;
}

/* ================= QTY AJAX ================= */
if (isset($_POST['action'], $_POST['key'])) {
  $key = $_POST['key'];

  if (!isset($_SESSION['cart'][$key])) {
    echo json_encode(['success' => false]);
    exit;
  }

  if ($_POST['action'] === 'plus') {
    $_SESSION['cart'][$key]['qty']++;
  }

  if ($_POST['action'] === 'minus') {
    $_SESSION['cart'][$key]['qty']--;
    if ($_SESSION['cart'][$key]['qty'] <= 0) {
      unset($_SESSION['cart'][$key]);
    }
  }

  echo json_encode(['success' => true]);
  exit;
}

/* ================= BUILD CART DATA ================= */
$cartItems = [];
$subtotal = 0;

foreach ($_SESSION['cart'] as $key => $item) {
  $stmt = $conn->prepare(
    "SELECT name, slug, main_image FROM products WHERE id = ? LIMIT 1"
  );
  $stmt->bind_param("i", $item['product_id']);
  $stmt->execute();
  $product = $stmt->get_result()->fetch_assoc();
  if (!$product) continue;

  $cartItems[$key] = [
    'name'  => $product['name'],
    'slug'  => $product['slug'],
    'image' => $product['main_image'],
    'size'  => $item['pack'],
    'price' => $item['price'],
    'qty'   => $item['qty']
  ];

  $subtotal += $item['price'] * $item['qty'];
}

$shipping = $subtotal > 999 ? 0 : 80;

/* ================= PAGE META ================= */
$pageTitle = "Your Cart | Indus Agrii";
$pageDescription = "Review your cart and proceed to checkout.";

include __DIR__ . "/../includes/header.php";
echo '<script>document.body.classList.add("cart-page","products-page");</script>';
?>

<!-- ================= PAGE ================= -->
<section class="bg-gray-50 pt-28 lg:pt-32 pb-24">

  <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- ================= LEFT : CART ================= -->
    <div class="lg:col-span-2 space-y-6 min-h-[420px]">

      <!-- HEADER -->
      <div class="bg-white rounded-2xl px-6 py-4 shadow-sm">
        <h1 class="text-xl font-semibold text-gray-900">Shopping Cart</h1>
        <div class="mt-3 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
      </div>

      <?php if (empty($cartItems) && empty($_SESSION['saved'])): ?>

        <!-- EMPTY CART -->
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm max-w-xl">
          <svg width="120" height="120" viewBox="0 0 24 24" fill="none" class="mx-auto text-gray-300 mb-4">
            <path d="M6 6h15l-1.5 9h-12z" stroke="currentColor" stroke-width="1.5"/>
            <circle cx="9" cy="21" r="1" fill="currentColor"/>
            <circle cx="18" cy="21" r="1" fill="currentColor"/>
            <path d="M6 6l-2-4H1" stroke="currentColor" stroke-width="1.5"/>
          </svg>

          <h3 class="text-lg font-semibold text-gray-800">Your cart is empty</h3>
          <p class="text-sm text-gray-500 mt-2">
            Looks like you haven’t added anything yet.
          </p>

          <a href="/IndusAgrii/public/products.php"
             class="inline-block mt-4 px-6 py-3 bg-emerald-700 text-white rounded-xl">
            Continue Shopping
          </a>
        </div>

      <?php else: ?>

        <!-- CART ITEMS -->
        <div class="space-y-4">
          <?php foreach ($cartItems as $key => $item): ?>
            <div
              class="bg-white rounded-xl p-5 shadow-sm flex gap-6 items-center"
              data-key="<?= $key ?>"
              data-price="<?= $item['price'] ?>"
            >

              <!-- PRODUCT -->
          <a
            href="/IndusAgrii/public/product-details.php?slug=<?= urlencode($item['slug']) ?>"
            class="flex gap-4 items-center flex-1"
          >
            <img
              src="/IndusAgrii/uploads/<?= htmlspecialchars($item['image'] ?? 'placeholder.png') ?>"
              class="w-24 h-24 object-contain rounded-xl"
            >
            <div>
              <p class="font-semibold text-gray-800">
                <?= htmlspecialchars($item['name']) ?>
              </p>
              <p class="text-sm text-gray-500">
                <?= $item['size'] ?> Kg · ₹<?= number_format($item['price']) ?>
              </p>
            </div>
          </a>

              <!-- QTY / PRICE -->
              <div class="flex flex-col items-end gap-2">
                <div class="flex items-center border rounded-full bg-gray-50">
                  <button
                    onclick="updateQty('minus','<?= $key ?>')"
                    class="px-3 py-1"
                    <?= $item['qty'] <= 1 ? 'disabled' : '' ?>
                  >−</button>

                  <span class="px-4 qty"><?= $item['qty'] ?></span>

                  <button
                    onclick="updateQty('plus','<?= $key ?>')"
                    class="px-3 py-1"
                  >+</button>
                </div>

                <p class="font-semibold item-total">
                  ₹<?= number_format($item['price'] * $item['qty']) ?>
                </p>

                <div class="text-xs flex gap-3">
                  <a href="?save=<?= urlencode($key) ?>" class="text-gray-500">
                    Save for later
                  </a>
                  <a href="?remove=<?= urlencode($key) ?>" class="text-red-600">
                    Remove
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- SAVED FOR LATER -->
<?php if (!empty($_SESSION['saved'])): ?>
  <div class="mt-10">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">
      Saved for later
    </h3>

    <div class="space-y-3">
      <?php foreach ($_SESSION['saved'] as $skey => $sitem): ?>
        <div class="bg-white rounded-xl p-4 flex justify-between items-center shadow-sm">
          <span class="text-sm text-gray-700">
            <?= $sitem['pack'] ?> Kg · ₹<?= number_format($sitem['price']) ?>
          </span>

          <a href="?add_back=<?= urlencode($skey) ?>"
             class="text-sm font-medium text-emerald-700 hover:underline">
            Move to cart
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>


      <?php endif; ?>
    </div>

    <!-- ================= RIGHT : ORDER SUMMARY ================= -->
    <aside class="bg-white rounded-2xl p-6 h-fit lg:sticky lg:top-32 shadow-sm">
      <h2 class="text-lg font-semibold mb-4">Order Summary</h2>

      <div class="flex justify-between text-sm mb-2">
        <span>Subtotal</span>
        <span id="subtotal">₹<?= number_format($subtotal) ?></span>
      </div>

      <div class="flex justify-between text-sm mb-2">
        <span>Shipping</span>
        <span id="shipping"><?= $shipping ? '₹'.$shipping : 'Free' ?></span>
      </div>

      <hr class="my-3">

      <div class="flex justify-between font-bold text-lg">
        <span>Total</span>
        <span id="total">₹<?= number_format($subtotal + $shipping) ?></span>
      </div>

      <button class="w-full mt-4 bg-emerald-800 text-white py-3 rounded-xl text-lg">
        Proceed to Checkout
      </button>

      <p class="text-xs text-gray-500 text-center mt-2">
        Free shipping on orders above ₹999
      </p>
    </aside>

  </div>
</section>

<!-- ================= MOBILE STICKY ================= -->
<div class="fixed bottom-0 inset-x-0 bg-white border-t px-4 py-3 flex items-center justify-between lg:hidden">
  <div>
    <p class="text-xs text-gray-500">Total</p>
    <p class="font-semibold text-lg" id="mobileTotal">
      ₹<?= number_format($subtotal + $shipping) ?>
    </p>
  </div>
  <button class="bg-emerald-800 text-white px-6 py-3 rounded-xl text-sm">
    Checkout
  </button>
</div>


<?php include __DIR__ . "/../includes/footer.php"; ?>


<script>
function updateQty(action, key) {
  fetch('cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=${action}&key=${key}`
  })
  .then(res => res.json())
  .then(data => {
    if (!data || data.success === false) return;

    const row = document.querySelector(`[data-key="${key}"]`);
    if (!row) return;

    const qtyEl = row.querySelector('.qty');
    const price = parseFloat(row.dataset.price);
    const minusBtn = row.querySelector('.minus-btn');

    let qty = parseInt(qtyEl.innerText, 10);

    if (action === 'plus') qty++;
    if (action === 'minus') qty--;

    // If qty becomes zero → remove row
    if (qty <= 0) {
      row.remove();
      recalcTotals();
      return;
    }

    // Update quantity text
    qtyEl.innerText = qty;
    qtyEl.classList.remove('qty-pop'); // reset
    void qtyEl.offsetWidth;            // force reflow
    qtyEl.classList.add('qty-pop');


    // Update item total
    row.querySelector('.item-total').innerText = '₹' + (price * qty);

    // Disable / enable minus button
    if (qty <= 1) {
      minusBtn.disabled = true;
      minusBtn.classList.add('opacity-40', 'cursor-not-allowed');
    } else {
      minusBtn.disabled = false;
      minusBtn.classList.remove('opacity-40', 'cursor-not-allowed');
    }

    recalcTotals();
  })
  .catch(() => {
    // fail silently (no UX break)
  });
}

function recalcTotals() {
  let subtotal = 0;

  document.querySelectorAll('[data-price]').forEach(row => {
    const price = parseFloat(row.dataset.price);
    const qtyEl = row.querySelector('.qty');
    if (!qtyEl) return;

    const qty = parseInt(qtyEl.innerText, 10);
    subtotal += price * qty;
  });

  const shipping = subtotal > 999 ? 0 : 80;
  const total = subtotal + shipping;

  const subtotalEl = document.getElementById('subtotal');
  const shippingEl = document.getElementById('shipping');
  const totalEl = document.getElementById('total');
  const mobileTotalEl = document.getElementById('mobileTotal');

  if (subtotalEl) subtotalEl.innerText = '₹' + subtotal;
  if (shippingEl) shippingEl.innerText = shipping ? '₹' + shipping : 'Free';
  if (totalEl) totalEl.innerText = '₹' + total;
  if (mobileTotalEl) mobileTotalEl.innerText = '₹' + total;
}
</script>


<?php include __DIR__ . "/../includes/footer.php"; ?>
