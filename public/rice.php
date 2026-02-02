<?php
/**
 * RICE.PHP – EXACT CLONE OF products.php UI + LOGIC
 * --------------------------------------------------
 * ✔ Pixel-perfect card layout (same HTML, classes, structure)
 * ✔ Same JS hooks (search typing, filters, sort, infinite scroll)
 * ✔ Same add-to-cart / qty / pack / wishlist / buy-now behaviour
 * ✔ Card click → product-details.php
 * ✔ ONLY rice products
 * ✔ Variety filter auto-generated from DB (Indrayani, Basmati, etc.)
 */

session_start();
require_once __DIR__ . "/../config/database.php";

/* =========================================================
   AUTO REDIRECT: /products?category=rice → /rice
========================================================= */
if (isset($_GET['category']) && $_GET['category'] === 'rice') {
  header("Location: /IndusAgrii/public/rice.php", true, 301);
  exit;
}

/* =========================================================
   PAGE META
========================================================= */
$pageTitle = "Rice Collection | Indus Agrii";
$pageDescription = "Premium Indian rice varieties including Indrayani, Basmati and Sona Masoori.";

/* =========================================================
   PAGINATION (SAME AS products.php)
========================================================= */
$limit = 9;

/* =========================================================
   ADD TO CART – IDENTICAL TO products.php
========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
  header('Content-Type: application/json');

  $id    = (int)($_POST['id'] ?? 0);
  $pack  = (int)($_POST['pack'] ?? 0);
  $qty   = (int)($_POST['qty'] ?? 1);
  $price = (float)($_POST['price'] ?? 0);

  if ($id <= 0 || $pack <= 0 || $qty <= 0 || $price <= 0) {
    echo json_encode(['success' => false]);
    exit;
  }

  $_SESSION['cart'] ??= [];
  $key = $id . '_' . $pack;

  if (!isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key] = [
      'product_id' => $id,
      'pack'  => $pack,
      'qty'   => $qty,
      'price' => $price
    ];
  } else {
    $_SESSION['cart'][$key]['qty'] += $qty;
  }

  echo json_encode([
    'success' => true,
    'count' => array_sum(array_column($_SESSION['cart'], 'qty'))
  ]);
  exit;
}

/* =========================================================
   AJAX FILTER / SEARCH / SORT (CLONED FROM products.php)
   DIFFERENCE: category LOCKED TO rice
========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
  header('Content-Type: application/json');

  $page   = max(1, (int)($_POST['page'] ?? 1));
  $offset = ($page - 1) * $limit;

  $search  = trim($_POST['search'] ?? '');
  $sort    = $_POST['sort'] ?? 'new';
  $variety = $_POST['category'] ?? 'all'; // reuse same JS variable

  $where  = "WHERE is_active = 1
AND category = 'rice'
AND name NOT LIKE '%Millet%'";
  $params = [];
  $types  = "";

  if ($variety !== 'all') {
    $where .= " AND variety = ?";
    $params[] = $variety;
    $types .= "s";
  }

  if ($search !== '') {
    $where .= " AND (name LIKE ? OR short_description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
  }

  $order = "ORDER BY created_at DESC";
  if ($sort === 'price_low')  $order = "ORDER BY base_price ASC";
  if ($sort === 'price_high') $order = "ORDER BY base_price DESC";

  $sql = "
    SELECT SQL_CALC_FOUND_ROWS *
    FROM products
    $where
    $order
    LIMIT ? OFFSET ?
  ";

  $types .= "ii";
  $params[] = $limit;
  $params[] = $offset;

  $stmt = $conn->prepare($sql);
  $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $products = $stmt->get_result();

  $totalRes = $conn->query("SELECT FOUND_ROWS() AS total");
  $total = (int)$totalRes->fetch_assoc()['total'];
  $hasMore = ($page * $limit) < $total;

  ob_start();
  while ($product = $products->fetch_assoc()):
  if ($product['category'] !== 'rice') continue; // 🔒 HARD BLOCK
    $packs = json_decode($product['pack_sizes'], true) ?? [2,5,10,30];
    $firstPack = (int)$packs[0];
    $isWishlisted = in_array((int)$product['id'], $_SESSION['wishlist'] ?? []);
?>

<!--  CARD MARKUP: 100% IDENTICAL TO products.php -->
<div
  class="product-card group cursor-pointer
         border border-gray-200 rounded-2xl bg-white
         overflow-hidden flex flex-col w-full
         transition-all duration-300 ease-out
         <?= !(int)$product['in_stock']
            ? ''
            : 'hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300'
         ?>"
  data-id="<?= (int)$product['id'] ?>"
  data-slug="<?= htmlspecialchars($product['slug']) ?>"
  data-stock="<?= (int)$product['in_stock'] ?>"
  data-name="<?= htmlspecialchars($product['name']) ?>">


  <div class="relative w-full aspect-[4/3] overflow-hidden rounded-t-2xl bg-gray-100">

<?php if (!(int)$product['in_stock']): ?>
  <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 pointer-events-none">
    <span class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">
      Out of Stock
    </span>
  </div>
<?php endif; ?>


    <button
      class="wishlist-btn absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 flex items-center justify-center shadow z-10"
      data-id="<?= (int)$product['id'] ?>">
      <i class="<?= $isWishlisted ? 'fa-solid fa-heart text-emerald-600' : 'fa-regular fa-heart text-gray-700' ?>"></i>
    </button>

    <img
      src="/IndusAgrii/uploads/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>"
      alt="<?= htmlspecialchars($product['name']) ?>"
      class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
  </div>

<div class="p-3 flex flex-col gap-2">

  <!-- GREY CONTENT ONLY -->
  <div class="<?= !(int)$product['in_stock'] ? 'grayscale opacity-60' : '' ?>">

    <h2 class="text-base font-semibold leading-tight">
      <?= htmlspecialchars($product['name']) ?>
    </h2>

    <p class="text-gray-600 text-xs line-clamp-2">
      <?= htmlspecialchars($product['short_description']) ?>
    </p>

    <div class="flex gap-2">
      <div class="flex-1">
        <select class="pack-size w-full h-9 border rounded-lg px-2 text-xs">
          <?php foreach ($packs as $p): ?>
            <option value="<?= (int)$p ?>"><?= (int)$p ?> Kg</option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="flex-1 h-9 flex items-center justify-between border rounded-lg px-2">
        <button type="button" class="qty-minus text-sm">−</button>
        <input type="number" value="1" min="1" class="qty-input w-8 text-center text-xs">
        <button type="button" class="qty-plus text-sm">+</button>
      </div>
    </div>

    <p class="product-price text-base font-bold mt-1"
       data-base-price="<?= (float)$product['base_price'] ?>">
      ₹<?= number_format($product['base_price'] * $firstPack, 2) ?>
    </p>

  </div>
  <!-- END GREY CONTENT -->

  <!-- BUTTON AREA (NO GREYSCALE HERE) -->
  <div class="pt-2">

    <?php if ((int)$product['in_stock'] === 1): ?>

      <div class="flex gap-2">
        <button
          class="add-to-cart flex-1 bg-black text-white py-1.5 rounded-lg text-xs"
          data-id="<?= (int)$product['id'] ?>">
          Add
        </button>

        <button
          type="button"
          class="buy-now flex-1 bg-black text-white py-1.5 rounded-lg text-xs">
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

<?php endwhile;

  echo json_encode([
    'html' => ob_get_clean(),
    'hasMore' => $hasMore
  ]);
  exit;
}

/* =========================================================
   FIRST PAGE LOAD (SEO SAFE)
========================================================= */
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare(
  "SELECT * FROM products WHERE is_active = 1
AND category = 'rice'
AND name NOT LIKE '%Millet%'
 ORDER BY created_at DESC LIMIT ? OFFSET ?"
);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$products = $stmt->get_result();

/* =========================================================
   AUTO VARIETY FILTER OPTIONS (FROM DB)
========================================================= */
$varieties = [];
$vRes = $conn->query("SELECT DISTINCT variety FROM products WHERE category = 'rice' AND variety IS NOT NULL AND variety != ''");
while ($v = $vRes->fetch_assoc()) {
  $varieties[] = $v['variety'];
}
$bodyClass = "rice-page";

include __DIR__ . "/../includes/header.php";
?>

<main class="rice-page">


<section
  class="relative w-full
         min-h-[260px] md:min-h-[320px] lg:min-h-[380px]
         overflow-hidden
         pb-6 md:pb-0
         pt-[96px] md:pt-[112px]">

  <!-- Background -->
  <img
    src="/IndusAgrii/assets/images/banners/shopbanner1.jpg"
    alt="Rice Collection"
    class="absolute inset-0 w-full h-full object-cover">

  <div class="absolute inset-0 bg-black/40"></div>

  <!-- Content -->
  <div class="relative h-full flex items-start md:items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 w-full">

      <!-- TEXT -->
      <div class="max-w-xl mb-6 md:mb-8 mt-2 md:mt-0">
        <h1
          class="text-white
                 text-3xl md:text-4xl lg:text-5xl
                 font-bold mb-3">
          Rice Collection
        </h1>
        <p class="text-white/90 text-sm md:text-base">
          Premium Indian rice sourced directly from farms.
        </p>
      </div>

      <!-- SEARCH + FILTER + SORT -->
      <div
        class="relative
               flex flex-col md:flex-row
               gap-3
               max-w-3xl
               bg-white/95 md:bg-transparent
               rounded-2xl
               p-3 md:p-0
               shadow-md md:shadow-none">

        <!-- SEARCH -->
        <div class="relative flex-1 min-w-0">
          <input
            id="searchInput"
            type="text"
            autocomplete="off"
            class="peer w-full h-11 rounded-xl border border-gray-300
                   px-4 pr-10 text-sm text-gray-700
                   placeholder-transparent
                   bg-white
                   caret-transparent
                   focus:caret-emerald-600
                   hover:border-emerald-400
                   focus:ring-2 focus:ring-emerald-500/40"
          />
          <span
            id="searchPlaceholder"
            class="pointer-events-none absolute left-4 top-1/2
                   -translate-y-1/2 text-sm text-gray-400">
            Search rice…
          </span>
          <i
            class="fa fa-search absolute right-4 top-1/2
                   -translate-y-1/2 text-gray-400"></i>
        </div>

        <!-- VARIETY FILTER -->
        <select
          id="filterCategory"
          class="h-11 rounded-xl border border-gray-300
                 px-4 text-sm bg-white text-gray-700
                 w-full md:w-auto">
          <option value="all">All Varieties</option>
          <?php foreach ($varieties as $v): ?>
            <option value="<?= htmlspecialchars($v) ?>">
              <?= ucwords(str_replace('_', ' ', $v)) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <!-- SORT -->
        <select
          id="sortProducts"
          class="h-11 rounded-xl border border-gray-300
                 px-4 text-sm bg-white text-gray-700
                 w-full md:w-auto">
          <option value="new">Newest</option>
          <option value="price_low">Price: Low → High</option>
          <option value="price_high">Price: High → Low</option>
        </select>

      </div>

      <!-- ACTIVE FILTER CHIPS -->
      <div
        id="activeFilters"
        class="mt-4 flex flex-wrap gap-2 hidden">
      </div>

    </div>
  </div>
</section>


<section class="max-w-7xl mx-auto px-4 pt-6 pb-12">

  <div id="productsGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

<?php while ($product = $products->fetch_assoc()):
  if ($product['category'] !== 'rice') continue; // 🔒 HARD BLOCK
  $packs = json_decode($product['pack_sizes'], true) ?? [2,5,10,30];
  $firstPack = (int)$packs[0];
  $isWishlisted = in_array((int)$product['id'], $_SESSION['wishlist'] ?? []);
?>

<!-- SAME CARD AS AJAX -->
<div
  class="product-card group cursor-pointer
         border border-gray-200 rounded-2xl bg-white
         overflow-hidden flex flex-col w-full
         transition-all duration-300 ease-out
         <?= !(int)$product['in_stock']
            ? ''
            : 'hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300'
         ?>"
  data-id="<?= (int)$product['id'] ?>"
  data-slug="<?= htmlspecialchars($product['slug']) ?>"
  data-stock="<?= (int)$product['in_stock'] ?>"
  data-name="<?= htmlspecialchars($product['name']) ?>">


  <div class="relative w-full aspect-[4/3] overflow-hidden rounded-t-2xl bg-gray-100">

  <?php if (!(int)$product['in_stock']): ?>
  <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 pointer-events-none">
    <span class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">
      Out of Stock
    </span>
  </div>
<?php endif; ?>

    <button class="wishlist-btn absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 flex items-center justify-center shadow z-10" data-id="<?= (int)$product['id'] ?>">
      <i class="<?= $isWishlisted ? 'fa-solid fa-heart text-emerald-600' : 'fa-regular fa-heart text-gray-700' ?>"></i>
    </button>

    <img src="/IndusAgrii/uploads/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($product['name']) ?>"class="w-full h-full object-cover
       transition-transform duration-500 ease-out
       <?= !(int)$product['in_stock']
          ? 'grayscale opacity-80'
          : 'group-hover:scale-105'
       ?>">
  </div>

  <div class="p-3 flex flex-col gap-2">

  <!-- GREY CONTENT ONLY -->
  <div class="<?= !(int)$product['in_stock'] ? 'grayscale opacity-60' : '' ?>">

    <h2 class="text-base font-semibold leading-tight">
      <?= htmlspecialchars($product['name']) ?>
    </h2>

    <p class="text-gray-600 text-xs line-clamp-2">
      <?= htmlspecialchars($product['short_description']) ?>
    </p>

    <div class="flex gap-2">
      <div class="flex-1">
        <select class="pack-size w-full h-9 border rounded-lg px-2 text-xs">
          <?php foreach ($packs as $p): ?>
            <option value="<?= (int)$p ?>"><?= (int)$p ?> Kg</option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="flex-1 h-9 flex items-center justify-between border rounded-lg px-2">
        <button type="button" class="qty-minus text-sm">−</button>
        <input type="number" value="1" min="1" class="qty-input w-8 text-center text-xs">
        <button type="button" class="qty-plus text-sm">+</button>
      </div>
    </div>

    <p class="product-price text-base font-bold mt-1"
       data-base-price="<?= (float)$product['base_price'] ?>">
      ₹<?= number_format($product['base_price'] * $firstPack, 2) ?>
    </p>

  </div>
  <!-- END GREY CONTENT -->

  <!-- BUTTON AREA (NO GREYSCALE HERE) -->
  <div class="pt-2">

    <?php if ((int)$product['in_stock'] === 1): ?>

      <div class="flex gap-2">
        <button
          class="add-to-cart flex-1 bg-black text-white py-1.5 rounded-lg text-xs"
          data-id="<?= (int)$product['id'] ?>">
          Add
        </button>

        <button
          type="button"
          class="buy-now flex-1 bg-black text-white py-1.5 rounded-lg text-xs">
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

  </div>

  <div id="scrollSentinel" class="h-10"></div>
</section>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>