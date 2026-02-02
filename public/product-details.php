<?php
require_once __DIR__ . "/../config/database.php";

/* ================= FETCH PRODUCT ================= */
$slug = trim($_GET['slug'] ?? '');
if (!$slug) {
  http_response_code(404);
  include __DIR__ . "/../404.php";
  exit;
}

$stmt = $conn->prepare("
  SELECT *
  FROM products
  WHERE slug = ? AND is_active = 1
  LIMIT 1
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  http_response_code(404);
  include __DIR__ . "/../404.php";
  exit;
}

/* ================= DATA ================= */
$packSizes = json_decode($product['pack_sizes'] ?? '[]', true) ?: [2,5,10,30];
$gallery   = json_decode($product['gallery_images'] ?? '[]', true) ?: [];

$rating      = (float)($product['rating'] ?? 4.8);
$ratingCount = (int)($product['rating_count'] ?? 120);

$shortTitle       = $product['short_title'] ?? '';
$shortDescription = $product['short_description'] ?? '';
$longDescription  = $product['long_description'] ?? '';

$pageTitle       = $product['seo_title'] ?: $product['name'] . " | Indus Agrii";
$pageDescription = $product['seo_description'] ?: $shortDescription;

/* 🔒 Static header only for this page */
$bodyClass = "product-details-page";

include __DIR__ . "/../includes/header.php";

/* ================= STAR RENDER ================= */
function renderStars($rating) {
  $full = floor($rating);
  $half = ($rating - $full) >= 0.5;
  $empty = 5 - $full - ($half ? 1 : 0);

  for ($i=0; $i<$full; $i++) echo '<i class="fa-solid fa-star text-amber-400"></i>';
  if ($half) echo '<i class="fa-solid fa-star-half-stroke text-amber-400"></i>';
  for ($i=0; $i<$empty; $i++) echo '<i class="fa-regular fa-star text-amber-400"></i>';
}
/* ================= RECOMMENDED PRODUCTS ================= */
$recStmt = $conn->prepare("
  SELECT *
  FROM products
  WHERE category = ?
    AND is_active = 1
    AND id != ?
  ORDER BY RAND()
  LIMIT 8
");
$recStmt->bind_param("si", $product['category'], $product['id']);
$recStmt->execute();
$recommendedProducts = $recStmt->get_result();
?>

<!-- ================= SEO PRODUCT SCHEMA ================= -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?= htmlspecialchars($product['name']) ?>",
  "image": "<?= htmlspecialchars($product['main_image']) ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?= number_format($rating,1) ?>",
    "reviewCount": "<?= $ratingCount ?>"
  }
}
</script>

<main id="productDetails"
  class="max-w-7xl mx-auto px-4 pt-28 pb-24"
  data-id="<?= (int)$product['id'] ?>"
  data-base-price="<?= (float)$product['base_price'] ?>">

  <!-- ================= BREADCRUMB ================= -->
  <nav class="text-sm text-gray-500 mb-10">
    <a href="/IndusAgrii/" class="hover:text-emerald-700">Home</a> /
    <a href="/IndusAgrii/public/products.php?category=<?= htmlspecialchars($product['category']) ?>"
       class="hover:text-emerald-700 capitalize">
      <?= htmlspecialchars($product['category']) ?>
    </a> /
    <span class="text-gray-800 font-medium">
      <?= htmlspecialchars($product['name']) ?>
    </span>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-[440px_1fr] gap-14 items-start">

    <!-- ================= LEFT : IMAGE ================= -->
    <div>
      <div class="relative overflow-hidden rounded-3xl border bg-gray-50">
        <img
          id="mainProductImage"
          src="/IndusAgrii/uploads/<?= htmlspecialchars($product['main_image']) ?>"
          class="w-full aspect-square object-cover">
      </div>

      <?php if ($gallery): ?>
      <div class="grid grid-cols-4 gap-3 mt-4">
        <?php foreach ($gallery as $img): ?>
          <img
            src="/IndusAgrii/uploads/<?= htmlspecialchars($img) ?>"
            class="aspect-square object-cover rounded-xl border cursor-pointer hover:border-emerald-700"
            onclick="document.getElementById('mainProductImage').src=this.src">
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- ================= RIGHT : CONTENT ================= -->
    <div class="space-y-6">

      <!-- TITLE -->
      <h1 class="text-4xl font-bold text-gray-900 leading-tight">
        <?= htmlspecialchars($product['name']) ?>
      </h1>

      <!-- SHORT TITLE -->
      <?php if ($shortTitle): ?>
        <p class="text-gray-500 text-base">
          <?= htmlspecialchars($shortTitle) ?>
        </p>
      <?php endif; ?>

      <!-- RATING + QUALITY -->
      <div class="flex items-center gap-3 text-sm">
        <div class="flex gap-1"><?php renderStars($rating); ?></div>
        <span class="text-gray-700 font-medium">
          <?= number_format($rating,1) ?>
        </span>
        <span class="text-gray-400">
          (<?= $ratingCount ?> ratings)
        </span>
        <span class="text-emerald-700 font-medium">
          Premium Quality
        </span>
      </div>

      <!-- PRICE -->
      <div>
        <p id="productPrice"
          class="text-3xl font-extrabold text-emerald-700">
          ₹<?= number_format($product['base_price'],2) ?>
        </p>

        <p class="text-base font-medium text-gray-600">
          <span id="priceUnit">/ kg</span>
        </p>

        <p class="text-sm text-gray-500 mt-1">
          MRP inclusive of all taxes
        </p>
      </div>

      <!-- INTRO -->
      <?php if ($shortDescription): ?>
        <p class="text-gray-700 leading-relaxed">
          <?= nl2br(htmlspecialchars($shortDescription)) ?>
        </p>
      <?php endif; ?>

      <!-- STORY -->
      <?php if ($longDescription): ?>
        <div class="space-y-4 text-gray-700 leading-relaxed">
          <?= nl2br(htmlspecialchars($longDescription)) ?>
        </div>
      <?php endif; ?>

      <!-- PACK SIZE -->
      <div class="pt-2">
        <p class="font-semibold mb-2">Pack Size</p>
        <div class="flex gap-2 flex-wrap">
          <?php foreach ($packSizes as $i => $kg): ?>
            <button
              class="pack-size px-5 py-2 rounded-full border font-semibold
                     <?= $i === 0 ? 'bg-emerald-800 text-white' : '' ?>"
              value="<?= (int)$kg ?>">
              <?= (int)$kg ?> kg
            </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- QUANTITY -->
      <div>
        <p class="font-semibold mb-2">Quantity</p>
        <div class="flex items-center gap-3">
          <button class="qty-minus w-10 h-10 border rounded-xl">−</button>
          <input class="qty-input w-14 h-10 border rounded-xl text-center" value="1">
          <button class="qty-plus w-10 h-10 border rounded-xl">+</button>
        </div>
      </div>

      <!-- CTA -->
      <div class="flex gap-4 pt-2">
        <button class="add-to-cart flex-1 bg-emerald-700 hover:bg-emerald-800 text-white py-3 rounded-xl font-semibold">
          <i class="fa-solid fa-bag-shopping mr-2"></i>Add to Cart
        </button>

        <button class="buy-now flex-1 border border-emerald-700 text-emerald-700 hover:bg-emerald-50 py-3 rounded-xl font-semibold">
          Buy Now
        </button>
      </div>

    </div>
  </div>

  <!-- ================= COOKING TIPS & FAQ ================= -->
  <section class="mt-16 space-y-4">
    <h2 class="text-2xl font-bold">Cooking Tips & FAQs</h2>

    <details class="border rounded-xl p-4">
      <summary class="font-semibold cursor-pointer">
        How to cook this grain?
      </summary>
      <p class="mt-2 text-gray-600">
        Rinse thoroughly, soak for 20–30 minutes, then cook with twice the water for best texture.
      </p>
    </details>

    <details class="border rounded-xl p-4">
      <summary class="font-semibold cursor-pointer">
        Is it gluten free?
      </summary>
      <p class="mt-2 text-gray-600">
        Yes, this product is naturally gluten-free.
      </p>
    </details>
  </section>

</main>


<script>
(function () {

  const page = document.getElementById("productDetails");
  if (!page) return;

  const addBtn     = page.querySelector(".add-to-cart");
  const buyBtn     = page.querySelector(".buy-now");
  const qtyInput   = page.querySelector(".qty-input");
  const minusBtn   = page.querySelector(".qty-minus");
  const plusBtn    = page.querySelector(".qty-plus");
  const priceEl    = document.getElementById("productPrice");
  const unitEl     = document.getElementById("priceUnit");

  const basePrice = parseFloat(page.dataset.basePrice);

  /* ================= PACK SIZE ================= */
  const packButtons = page.querySelectorAll(".pack-size");

  function getSelectedPack() {
    const active = page.querySelector(".pack-size.bg-emerald-800");
    return active ? parseInt(active.value) : 1;
  }

  packButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      packButtons.forEach(b => {
        b.classList.remove("bg-emerald-800", "text-white");
      });
      btn.classList.add("bg-emerald-800", "text-white");
      updatePrice();
    });
  });

  /* ================= QUANTITY ================= */
  function getQty() {
    return Math.max(1, parseInt(qtyInput.value) || 1);
  }

  minusBtn.addEventListener("click", () => {
    qtyInput.value = getQty() - 1;
    updatePrice();
  });

  plusBtn.addEventListener("click", () => {
    qtyInput.value = getQty() + 1;
    updatePrice();
  });

  qtyInput.addEventListener("input", updatePrice);

  /* ================= PRICE CALC ================= */
  function updatePrice() {
    const pack = getSelectedPack();
    const qty  = getQty();

    const total = basePrice * pack * qty;

    priceEl.textContent = "₹" + total.toFixed(2);
    unitEl.textContent = `(${pack} kg × ${qty})`;
  }

  // Initial calculation
  updatePrice();

  /* ================= DATA FOR CART ================= */
  function getData() {
    return {
      id: page.dataset.id,
      pack: getSelectedPack(),
      qty: getQty(),
      price: basePrice * getSelectedPack() * getQty()
    };
  }

  /* ================= ADD TO CART ================= */
  addBtn.addEventListener("click", () => {
    const d = getData();

    fetch("/IndusAgrii/public/products.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        add_to_cart: "1",
        id: d.id,
        pack: d.pack,
        qty: d.qty,
        price: d.price
      })
    })
    .then(r => r.json())
    .then(res => {
      if (!res || !res.success) return;

      addBtn.classList.add("opacity-70");
      addBtn.textContent = "Added ✓";

      setTimeout(() => {
        addBtn.classList.remove("opacity-70");
        addBtn.innerHTML =
          `<i class="fa-solid fa-bag-shopping mr-2"></i>Add to Cart`;
      }, 1200);

      const badge = document.getElementById("cartCount");
      if (badge) {
        badge.textContent = res.count;
        badge.classList.remove("hidden");
      }

      if (typeof showToast === "function") {
        showToast("Added to Cart", "Product added successfully");
      }
    });
  });

  /* ================= BUY NOW ================= */
  buyBtn.addEventListener("click", () => {
    const d = getData();

    fetch("/IndusAgrii/public/products.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        add_to_cart: "1",
        id: d.id,
        pack: d.pack,
        qty: d.qty,
        price: d.price
      })
    })
    .then(() => {
      window.location.href = "/IndusAgrii/public/cart.php";
    });
  });

})();
</script>


<?php include __DIR__ . "/../includes/footer.php"; ?>

