<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* =========================================================
   PAGINATION CONFIG (GLOBAL)
========================================================= */
$limit = 9; // 3x3 grid

/* =========================================================
   ADD TO CART HANDLER
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
      'pack' => $pack,
      'qty' => $qty,
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
   WISHLIST TOGGLE HANDLER
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
    // remove
    $_SESSION['wishlist'] = array_values(
      array_diff($_SESSION['wishlist'], [$id])
    );
    $state = 'removed';
  } else {
    // add
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
   AJAX FILTER / SEARCH / SORT / PAGINATION
========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
  header('Content-Type: application/json');

  $page     = max(1, (int)($_POST['page'] ?? 1));
  $offset   = ($page - 1) * $limit;

  $search   = trim($_POST['search'] ?? '');
  $category = $_POST['category'] ?? 'all';
  $sort     = $_POST['sort'] ?? 'new';

$where = "WHERE is_active = 1";
  $params = [];
  $types  = "";

  if ($category !== 'all') {
    $where .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
  }

  if ($search !== '') {
    $where .= " AND (name LIKE ? OR short_description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
  }

  $order = "ORDER BY created_at DESC";
  if ($sort === "price_low")  $order = "ORDER BY base_price ASC";
  if ($sort === "price_high") $order = "ORDER BY base_price DESC";

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
    $packs = json_decode($product['pack_sizes'], true) ?? [2,5,10,30];
    $firstPack = (int)$packs[0];
  ?>

<div
  class="product-card group reveal
         border border-gray-200
         rounded-2xl
         bg-white
         overflow-hidden
         flex flex-col
         w-full
         transition-all duration-300
         <?= !$product['in_stock'] ? '' : 'hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300' ?>"
  data-id="<?= (int)$product['id'] ?>"
  data-slug="<?= htmlspecialchars($product['slug']) ?>"
  data-stock="<?= (int)$product['in_stock'] ?>">

  <div class="relative w-full h-[200px] overflow-hidden rounded-t-3xl bg-gray-50">

    <?php if (!$product['in_stock']): ?>
      <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 pointer-events-none">
        <span class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">
          Out of Stock
        </span>
      </div>
    <?php endif; ?>

    <button
      class="wishlist-btn absolute top-3 right-3
             w-9 h-9 rounded-full
             bg-white/90
             flex items-center justify-center
             shadow"
      data-id="<?= (int)$product['id'] ?>">
      <i class="fa-regular fa-heart"></i>
    </button>

    <img
      src="/IndusAgrii/uploads/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>"
      alt="<?= htmlspecialchars($product['name']) ?>"
      class="absolute inset-0 w-full h-full object-cover
       transition-transform duration-500 ease-out
       <?= !$product['in_stock']
         ? 'grayscale opacity-80'
         : 'group-hover:scale-105'
       ?>">
  </div>


<div class="p-3 flex flex-col gap-2">

  <!-- GREY CONTENT AREA -->
  <div class="<?= !$product['in_stock'] ? 'grayscale opacity-60' : '' ?>">

    <h2 class="text-base font-semibold leading-tight">
      <?= htmlspecialchars($product['name']) ?>
    </h2>

    <p class="text-gray-600 text-xs line-clamp-2">
      <?= htmlspecialchars($product['short_description']) ?>
    </p>

    <div class="flex gap-2">

      <!-- PACK -->
      <div class="flex-1">
        <select class="pack-size w-full h-9 border rounded-lg px-2 text-xs">
          <?php foreach ($packs as $p): ?>
            <option value="<?= (int)$p ?>"><?= (int)$p ?> Kg</option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- QTY -->
      <div class="flex-1 h-9 flex items-center justify-between border rounded-lg px-2">
        <button type="button" class="qty-minus text-sm">−</button>
        <input type="number" value="1" min="1"
               class="qty-input w-8 text-center text-xs">
        <button type="button" class="qty-plus text-sm">+</button>
      </div>

    </div>

    <p class="product-price text-base font-bold mt-1"
       data-base-price="<?= (float)$product['base_price'] ?>">
      ₹<?= number_format($product['base_price'] * $firstPack, 2) ?>
    </p>

  </div>
  <!-- END GREY CONTENT -->

  <!-- ACTIONS (NO GREY EVER) -->
  <div class="pt-2">

<?php if ((int)$product['in_stock'] === 1): ?>

    <div class="flex gap-2">
      <button class="add-to-cart flex-1 bg-black text-white py-1.5 rounded-lg text-xs">
        Add
      </button>
      <button class="buy-now flex-1 bg-black text-white py-1.5 rounded-lg text-xs">
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
             active:scale-95
             transition"
      data-id="<?= (int)$product['id'] ?>"
      data-name="<?= htmlspecialchars($product['name']) ?>">
      <i class="fa-solid fa-bell mr-1"></i> Notify Me
    </button>

<?php endif; ?>

  </div>
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
   PAGE LOAD (SEO SAFE FIRST PAGE)
========================================================= */
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$category = $_GET['category'] ?? 'all';
$slug = in_array($category, ['rice', 'millets']) ? $category : 'products';

$pageTitle = ucfirst($slug) . " Products | Indus Agrii";
$pageDescription = "Buy premium quality $slug sourced directly from Indian farms.";

$where = "WHERE is_active = 1";
$params = [];
$types = "";

if (in_array($category, ['rice','millets'])) {
  $where .= " AND category = ?";
  $params[] = $category;
  $types .= "s";
}

$sql = "
  SELECT *
  FROM products
  $where
  ORDER BY created_at DESC
  LIMIT ? OFFSET ?
";

$types .= "ii";
$params[] = $limit;
$params[] = $offset;

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();

include __DIR__ . "/../includes/header.php";
?>

<?php
$banner = [
  "title" => "Our Products",
  "desc"  => "Premium quality grains sourced directly from Indian farms.",
  "image" => "/IndusAgrii/assets/images/banners/shopbanner.jpg"
];

if ($category === "rice") {
  $banner = [
    "title" => "Rice Collection",
    "desc"  => "Finest rice varieties cultivated with care and tradition.",
    "image" => "/IndusAgrii/assets/images/banners/shopbanner1.jpg"
  ];
}

if ($category === "millets") {
  $banner = [
    "title" => "Millets Collection",
    "desc"  => "Nutritious, ancient grains for a healthier lifestyle.",
    "image" => "/IndusAgrii/assets/images/banners/shopbanner2.jpg"
  ];
}
?>

<section
  class="relative w-full
         min-h-[260px] md:min-h-[320px] lg:min-h-[380px]
         overflow-hidden
         pb-6 md:pb-0
         pt-[96px] md:pt-[112px]">

  <!-- Background -->
<img
  src="<?= $banner['image'] ?>"
  alt="<?= htmlspecialchars($banner['title']) ?>"
  class="absolute inset-0 w-full h-full object-cover">

  <div class="absolute inset-0 bg-black/40"></div>

  <!-- Content -->
<div class="relative h-full flex items-start md:flex items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 w-full">

      <!-- TEXT -->
      <div class="max-w-xl mb-6 md:mb-8 mt-2 md:mt-0">
        <h1
          class="text-white
                 text-3xl md:text-4xl lg:text-5xl
                 font-bold mb-3">
          <?= htmlspecialchars($banner['title']) ?>
        </h1>
        <p class="text-white/90 text-sm md:text-base">
          <?= htmlspecialchars($banner['desc']) ?>
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
            Search products…
          </span>
          <i
            class="fa fa-search absolute right-4 top-1/2
                   -translate-y-1/2 text-gray-400"></i>
        </div>

        <!-- CATEGORY -->
        <select
          id="filterCategory"
          class="h-11 rounded-xl border border-gray-300
                 px-4 text-sm bg-white text-gray-700
                 w-full md:w-auto">
          <option value="all">All</option>
          <option value="rice">Rice</option>
          <option value="millets">Millets</option>
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


<section class="max-w-7xl mx-auto px-4 pt-10 pb-12">

  <?php if (in_array($category, ['rice', 'millets'])): ?>
  <h1 class="text-3xl font-bold mb-8 capitalize">
    <?= htmlspecialchars($category) ?> Products
  </h1>
<?php endif; ?>


  <?php if ($products->num_rows === 0): ?>
    <p class="text-gray-600">No products available.</p>
  <?php else: ?>

  <!-- REQUIRED BY main.js -->
<div
  id="productsGrid"
  class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">

<?php while ($product = $products->fetch_assoc()):
  $packs = json_decode($product['pack_sizes'], true) ?? [2,5,10,30];
  $firstPack = (int)$packs[0];
?>

<div
  class="product-card group
         cursor-pointer
         border border-gray-200
         rounded-2xl
         bg-white
         overflow-hidden
         flex flex-col
         w-full
         transition-all duration-300 ease-out
         <?= !$product['in_stock']
           ? ''
           : 'hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300'
         ?>"
  data-id="<?= (int)$product['id'] ?>"
  data-slug="<?= htmlspecialchars($product['slug']) ?>"
  data-stock="<?= (int)$product['in_stock'] ?>"
  data-name="<?= htmlspecialchars($product['name']) ?>">

<div
  class="relative w-full aspect-[4/3]
         overflow-hidden
         rounded-t-2xl
         bg-gray-100">

<?php if (!$product['in_stock']): ?>
  <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 pointer-events-none">
    <span class="bg-red-600 text-white
                 px-4 py-2 rounded-xl
                 text-sm font-bold">
      Out of Stock
    </span>
  </div>
<?php endif; ?>


  <?php
$isWishlisted = in_array(
  (int)$product['id'],
  $_SESSION['wishlist'] ?? []
);
?>

<button
  class="wishlist-btn absolute top-3 right-3
         w-9 h-9 rounded-full
         bg-white/90
         flex items-center justify-center
         shadow z-10"
  data-id="<?= (int)$product['id'] ?>"
  aria-label="Wishlist">

  <i class="<?= $isWishlisted
    ? 'fa-solid fa-heart text-emerald-600'
    : 'fa-regular fa-heart text-gray-700' ?>"></i>
</button>


  <img
  src="/IndusAgrii/uploads/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>"
  alt="<?= htmlspecialchars($product['name']) ?>"
  class="w-full h-full object-cover
         transition-transform duration-500 ease-out
         <?= !$product['in_stock']
           ? 'grayscale opacity-80'
           : 'group-hover:scale-105'
         ?>">

</div>

<div class="p-3 flex flex-col gap-2">

<div class="space-y-2">


  <h2 class="text-base font-semibold leading-tight">
    <?= htmlspecialchars($product['name']) ?>
  </h2>

  <p class="text-gray-600 text-xs line-clamp-2">
    <?= htmlspecialchars($product['short_description']) ?>
  </p>

  <!-- PACK + QTY (SIDE BY SIDE) -->
<div class="flex gap-2">

  <!-- PACK -->
  <div class="flex-1">
    <select
      class="pack-size w-full h-9
             border rounded-lg
             px-2 text-xs">
      <?php foreach ($packs as $p): ?>
        <option value="<?= (int)$p ?>"><?= (int)$p ?> Kg</option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- QTY -->
  <div
    class="flex-1 h-9
           flex items-center justify-between
           border rounded-lg px-2">

    <button
      type="button"
      class="qty-minus text-sm leading-none">−</button>

    <input
      type="number"
      value="1"
      min="1"
      class="qty-input w-8 text-center text-xs outline-none">

    <button
      type="button"
      class="qty-plus text-sm leading-none">+</button>
  </div>

</div>


  <!-- PRICE -->
  <p
    class="product-price text-base font-bold mt-1"
    data-base-price="<?= (float)$product['base_price'] ?>">
    ₹<?= number_format($product['base_price'] * $firstPack, 2) ?>
  </p>

      </div>


<!-- ACTIONS -->
<div class="flex gap-2 pt-1">

<?php if ((int)$product['in_stock'] === 1): ?>

  <!-- ADD TO CART -->
  <button
    class="add-to-cart flex-1
           bg-black text-white
           py-1.5 rounded-lg text-xs
           flex items-center justify-center gap-1
           transition-all duration-200
           hover:bg-emerald-700
           active:scale-95"
    data-id="<?= (int)$product['id'] ?>">
    <i class="fa-solid fa-bag-shopping text-xs"></i>
    Add
  </button>

  <!-- BUY NOW -->
  <button
    type="button"
    class="buy-now flex-1
           bg-black text-white
           py-1.5 rounded-lg text-xs
           flex items-center justify-center gap-1
           transition-all duration-200
           hover:bg-gray-800
           active:scale-95">
    <i class="fa fa-bolt text-xs"></i>
    Buy
  </button>

<?php else: ?>

  <!-- NOTIFY ME -->
    <button
  type="button"
  class="notify-btn
         relative z-20
         w-full
         bg-emerald-600
         text-white
         py-2
         rounded-lg
         text-sm
         font-semibold
         shadow-md
         hover:bg-emerald-700
         active:scale-95
         transition"
  data-id="<?= (int)$product['id'] ?>"
  data-name="<?= htmlspecialchars($product['name']) ?>"
>
  <i class="fa-solid fa-bell mr-1"></i> Notify Me
</button>


<?php endif; ?>

</div>
</div>
</div>

<?php endwhile; ?>

  </div>
  <?php endif; ?>
</section>


<?php include __DIR__ . "/../includes/footer.php"; ?>
