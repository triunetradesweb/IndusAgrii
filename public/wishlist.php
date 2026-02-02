<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* =========================================================
   WISHLIST TOGGLE HANDLER (AJAX)
   Used by ❤️ button from ANY page
========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_wishlist'])) {
  header('Content-Type: application/json');

  $id = (int)($_POST['id'] ?? 0);
  if ($id <= 0) {
    echo json_encode(['success' => false]);
    exit;
  }

  $_SESSION['wishlist'] ??= [];

  if (in_array($id, $_SESSION['wishlist'])) {
    // REMOVE
    $_SESSION['wishlist'] = array_values(
      array_diff($_SESSION['wishlist'], [$id])
    );
    $state = 'removed';
  } else {
    // ADD
    $_SESSION['wishlist'][] = $id;
    $state = 'added';
  }

  echo json_encode([
    'success' => true,
    'state'   => $state,
    'count'   => count($_SESSION['wishlist'])
  ]);
  exit;
}

/* =========================================================
   PAGE DATA
========================================================= */
$pageTitle = "My Wishlist | Indus Agrii";
$bodyClass = "wishlist-page";

$wishlist = $_SESSION['wishlist'] ?? [];
$products = null;

if (!empty($wishlist)) {
  $ids = implode(',', array_map('intval', $wishlist));

  $sql = "
    SELECT *
    FROM products
    WHERE is_active = 1
    AND id IN ($ids)
    ORDER BY created_at DESC
  ";

  $products = $conn->query($sql);
}

/* =========================================================
   HEADER
========================================================= */
include __DIR__ . "/../includes/header.php";
?>

<!-- =======================================================
     PAGE CONTENT
======================================================= -->
<main class="pt-28 pb-20 px-6">

  <h1 class="text-3xl font-bold text-emerald-800 mb-8 text-center">
    My Wishlist
  </h1>

  <div class="max-w-7xl mx-auto
              grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4
              gap-6">

    <?php if ($products && $products->num_rows > 0): ?>
      <?php while ($product = $products->fetch_assoc()):
        $packs = json_decode($product['pack_sizes'], true) ?? [2,5,10,30];
        $firstPack = (int)$packs[0];
      ?>

      <!-- PRODUCT CARD (SAME AS INDEX.PHP) -->
<div
  class="product-card group
       <?= (int)$product['in_stock'] ? 'cursor-pointer' : '' ?>
         border border-gray-200
         rounded-2xl
         bg-white
         overflow-hidden
         flex flex-col
         transition-all duration-300
         <?= (int)$product['in_stock']
        ? 'hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300'
        : 'opacity-95'
      ?>"
  data-id="<?= (int)$product['id'] ?>"
  data-slug="<?= htmlspecialchars($product['slug']) ?>"
  data-stock="<?= (int)$product['in_stock'] ?>">


<div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">

      <?php if (!(int)$product['in_stock']): ?>
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 pointer-events-none">
          <span class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">
            Out of Stock
          </span>
        </div>
      <?php endif; ?>

          <button
            class="wishlist-btn absolute top-3 right-3
                   w-9 h-9 rounded-full bg-white/90
                   flex items-center justify-center shadow z-10"
            data-id="<?= (int)$product['id'] ?>">

            <!-- ALWAYS FILLED ON WISHLIST PAGE -->
            <i class="fa-solid fa-heart text-emerald-600"></i>
          </button>

          <img
            src="/IndusAgrii/uploads/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>"
            alt="<?= htmlspecialchars($product['name']) ?>"
            class="w-full h-full object-cover
            transition-transform duration-500
            <?= !(int)$product['in_stock']
                ? 'grayscale opacity-80'
                : 'group-hover:scale-105'
            ?>">
        </div>

        <div class="p-3 flex flex-col gap-2">

  <div class="<?= !(int)$product['in_stock'] ? 'grayscale opacity-70' : '' ?>">

            
          <h2 class="text-base font-semibold leading-tight">
            <?= htmlspecialchars($product['name']) ?>
          </h2>

          <p class="text-gray-600 text-xs line-clamp-2">
            <?= htmlspecialchars($product['short_description']) ?>
          </p>

          <div class="flex gap-2">

            <select class="pack-size flex-1 h-9 border rounded-lg px-2 text-xs">
              <?php foreach ($packs as $p): ?>
                <option value="<?= (int)$p ?>"><?= (int)$p ?> Kg</option>
              <?php endforeach; ?>
            </select>

            <div class="flex-1 h-9 flex items-center justify-between border rounded-lg px-2">
              <button type="button" class="qty-minus text-sm">−</button>
              <input type="number" value="1" min="1"
                     class="qty-input w-8 text-center text-xs outline-none">
              <button type="button" class="qty-plus text-sm">+</button>
            </div>

          </div>

          <p class="product-price text-base font-bold mt-1"
             data-base-price="<?= (float)$product['base_price'] ?>">
            ₹<?= number_format($product['base_price'] * $firstPack, 2) ?>
          </p>
              </div>


<div class="pt-2">

<?php if ((int)$product['in_stock'] === 1): ?>

  <div class="flex gap-2">
    <button
      class="add-to-cart flex-1 bg-black text-white py-1.5 rounded-lg text-xs
             flex items-center justify-center gap-1 active:scale-95"
      data-id="<?= (int)$product['id'] ?>">
      <i class="fa-solid fa-bag-shopping text-xs"></i>
      Add
    </button>

    <button
      class="buy-now flex-1 bg-black text-white py-1.5 rounded-lg text-xs
             flex items-center justify-center gap-1 active:scale-95">
      <i class="fa fa-bolt text-xs"></i>
      Buy
    </button>
  </div>

<?php else: ?>

  <button
    type="button"
    class="notify-btn
           w-full
           bg-emerald-600
           text-white
           py-2
           rounded-lg
           text-sm
           font-semibold
           hover:bg-emerald-700
           transition"
    data-id="<?= (int)$product['id'] ?>"
    data-name="<?= htmlspecialchars($product['name']) ?>">
    <i class="fa-solid fa-bell mr-1"></i> Notify Me
  </button>

<?php endif; ?>

</div>


        </div>
      </div>

      <?php endwhile; ?>
    <?php else: ?>
      <p class="col-span-full text-center text-gray-500">
        Your wishlist is empty.
      </p>
    <?php endif; ?>

  </div>
</main>

<?php
/* =========================================================
   FOOTER
========================================================= */
include __DIR__ . "/../includes/footer.php";
?>
