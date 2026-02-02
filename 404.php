<?php
http_response_code(404);

/* ✅ FORCE STATIC HEADER BEHAVIOR */
$bodyClass = "products-page bg-white text-brand-dark antialiased";

$pageTitle = "404 – Page Not Found | Indus Agrii";
$pageDescription = "The page you are looking for does not exist. Explore premium rice and millets at Indus Agrii.";

require_once __DIR__ . "/includes/header.php";
?>

<script>
  document.body.classList.add("products-page");
</script>


<main class="min-h-[70vh] flex items-center justify-center px-4 bg-gradient-to-br from-green-50 via-white to-green-100">
  <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

    <!-- Illustration / Product-style visual -->
    <div class="flex justify-center">
      <div class="relative w-72 h-72 bg-white rounded-2xl shadow-xl flex items-center justify-center">
        <img
          src="/IndusAgrii/assets/images/products/rice/ProductIndrayaniRice.png"
          alt="Product not found"
          class="max-w max-h object-contain"
        />
        <span class="absolute -top-4 -right-4 bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow">
          Not Found
        </span>
      </div>
    </div>

    <!-- Content -->
    <div class="text-center md:text-left">
      <h1 class="text-[72px] leading-none font-extrabold text-green-600">404</h1>

      <h2 class="mt-4 text-2xl md:text-3xl font-semibold text-gray-800">
        This page is out of stock
      </h2>

      <p class="mt-3 text-gray-600 text-base md:text-lg max-w-md">
        The page you’re trying to access doesn’t exist anymore or was moved.
        Don’t worry — our best products are still waiting for you.
      </p>

      <!-- Actions -->
      <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <a
          href="/IndusAgrii/public/products.php"
          class="inline-flex items-center justify-center rounded-xl bg-green-600 px-6 py-3 text-white font-medium shadow-md transition-transform duration-200 hover:scale-[1.02]"
        >
          Browse Products
        </a>

        <a
          href="/IndusAgrii/public/index.php"
          class="inline-flex items-center justify-center rounded-xl border border-green-600 px-6 py-3 text-green-700 font-medium transition-transform duration-200 hover:scale-[1.02]"
        >
          Go to Homepage
        </a>
      </div>
    </div>

  </div>
</main>

<?php
require_once __DIR__ . "/includes/footer.php";
?>
