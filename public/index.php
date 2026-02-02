<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$sql = "
  SELECT *
  FROM products
  WHERE is_active = 1
  ORDER BY created_at DESC
";
$products = $conn->query($sql);

include "../includes/header.php";
?>

<style>
  .testimonial-card {
    background: white;
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
    padding: 1.75rem;
    flex-shrink: 0;
  }

</style>

<!-- ================= HERO SLIDER ================= -->
<section class="hero-slider relative h-screen overflow-hidden">

  <!-- SLIDES WRAPPER -->
  <div id="heroSlides"
       class="absolute inset-0 flex transition-transform duration-1000 ease-in-out">

    <!-- SLIDE 1 -->
    <div class="w-full h-full flex-shrink-0 relative hero-slide">
      <img src="/IndusAgrii/assets/images/banners/hero2.jpg"
           class="absolute inset-0 w-full h-full object-cover"
           alt="Indian farm produce">
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/25"></div>
    </div>

    <!-- SLIDE 2 -->
    <div class="w-full h-full flex-shrink-0 relative hero-slide">
      <img src="/IndusAgrii/assets/images/banners/hero1.jpg"
           class="absolute inset-0 w-full h-full object-cover"
           alt="Reliable grain supply">
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/25"></div>
    </div>

    <!-- CLONE OF SLIDE 1 (FOR SMOOTH LOOP – NOT VISIBLE CHANGE) -->
    <div class="w-full h-full flex-shrink-0 relative hero-slide">
      <img src="/IndusAgrii/assets/images/banners/hero2.jpg"
           class="absolute inset-0 w-full h-full object-cover"
           alt="">
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/25"></div>
    </div>

  </div>

  <!-- SHARED CONTENT -->
  <div class="relative z-10 h-full flex items-start">
    <div class="max-w-7xl px-6
                pt-[calc(7.5rem+env(safe-area-inset-top))]
                sm:pt-[calc(8.5rem+env(safe-area-inset-top))]
                md:pt-[calc(9.5rem+env(safe-area-inset-top))]
                lg:pt-[calc(10.5rem+env(safe-area-inset-top))]
                md:pl-12 lg:pl-20 text-left">

      <span class="inline-block rounded-full bg-white/20 px-4 py-1
                   text-sm font-semibold text-white backdrop-blur hero-badge">
        Farm Direct • Consistent Quality
      </span>

      <h1 class="mt-5 max-w-3xl
                 text-3xl sm:text-4xl md:text-5xl lg:text-6xl
                 font-extrabold text-white leading-tight hero-title">
        Reliable Grains from<br>
        <span class="text-green-300">Indian Farms</span>
        <span class="block sm:inline"> for Everyday Cooking</span>
      </h1>

      <p class="mt-5 max-w-3xl
                text-lg sm:text-xl lg:text-2xl
                leading-relaxed text-white/95 hero-desc">
        Premium rice and millets trusted by households across Maharashtra.
      </p>

<!-- CTA BUTTONS -->
<div class="mt-6 sm:mt-8
            grid grid-cols-1 sm:grid-cols-2
            gap-3
            w-full max-w-sm hero-actions">

  <a href="/IndusAgrii/public/rice.php"
     class="w-full text-center
            rounded-full bg-brand-primary
            px-5 py-2.5 sm:px-6 sm:py-3
            text-sm sm:text-base font-bold text-white">
    Shop
  </a>

  <a href="/IndusAgrii/public/products.php"
     class="w-full text-center
            rounded-full border border-white/60
            px-5 py-2.5 sm:px-6 sm:py-3
            text-sm sm:text-base font-semibold text-white">
    Explore
  </a>

</div>



      <!-- TRUST STRIP -->
      <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-xl">

        <div class="flex items-center gap-3 text-white/95 group">
          <i class="fa-solid fa-seedling text-green-300
                    text-lg sm:text-xl
                    transition-transform duration-300
                    group-hover:scale-110"></i>
          <span class="text-sm sm:text-base">Farm-Direct</span>
        </div>

        <div class="flex items-center gap-3 text-white/95 group">
          <i class="fa-solid fa-shield-halved text-green-300
                    text-lg sm:text-xl
                    transition-transform duration-300
                    group-hover:scale-110"></i>
          <span class="text-sm sm:text-base">Quality Assured</span>
        </div>

        <div class="flex items-center gap-3 text-white/95 group">
          <i class="fa-solid fa-truck text-green-300
                    text-lg sm:text-xl
                    transition-transform duration-300
                    group-hover:scale-110"></i>
          <span class="text-sm sm:text-base">Pan Maharashtra Supply</span>
        </div>

        <div class="flex items-center gap-3 text-white/95 group">
          <i class="fa-solid fa-utensils text-green-300
                    text-lg sm:text-xl
                    transition-transform duration-300
                    group-hover:scale-110"></i>
          <span class="text-sm sm:text-base">Authentic Taste</span>
        </div>
      </div>
     </div>
  </div>
</section>


<div class="h-10 sm:h-12"></div>


<!-- ================= GUIDED SHOPPING ================= -->
<section class="bg-white py-14 sm:py-16">
  <div class="mx-auto max-w-7xl px-6">
    <h2 class="reveal text-center text-3xl font-extrabold mb-3">
      Choose What Fits Your Daily Cooking
    </h2>
    <p class="reveal text-center text-brand-muted max-w-2xl mx-auto mb-10">
      Simple guidance to help you pick the right grain for your home.
    </p>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <a href="#"
      data-type="rice"
      class="guided-card reveal rounded-2xl border p-6 text-center transition
              hover:-translate-y-1 hover:shadow-lg
              hover:border-brand-primary/40">
        <i class="fa-solid fa-house text-3xl text-brand-primary mb-3 transition group-hover:scale-105"></i>
        <h3 class="font-bold text-base mb-1">For Family Meals</h3>
        <p class="text-sm text-brand-muted">
          Soft, consistent rice for daily cooking.
        </p>
      </a>

    <a href="#"
      data-type="rice"
      class="guided-card reveal rounded-2xl border p-6 text-center transition
              hover:-translate-y-1 hover:shadow-lg
              hover:border-brand-primary/40">
        <i class="fa-solid fa-bowl-rice text-3xl text-brand-primary mb-3"></i>
        <h3 class="font-bold text-base mb-1">Everyday Rice</h3>
        <p class="text-sm text-brand-muted">
          Reliable taste you can trust daily.
        </p>
      </a>

    <a href="#"
      data-type="millets"
      class="guided-card reveal rounded-2xl border p-6 text-center transition
              hover:-translate-y-1 hover:shadow-lg
              hover:border-brand-primary/40">
        <i class="fa-solid fa-leaf text-3xl text-brand-primary mb-3"></i>
        <h3 class="font-bold text-base mb-1">Healthy Choices</h3>
        <p class="text-sm text-brand-muted">
          Naturally nutritious millets.
        </p>
      </a>

    <a href="#"
      data-type="millets"
      class="guided-card reveal rounded-2xl border p-6 text-center transition
              hover:-translate-y-1 hover:shadow-lg
              hover:border-brand-primary/40">
        <i class="fa-solid fa-utensils text-3xl text-brand-primary mb-3"></i>
        <h3 class="font-bold text-base mb-1">Traditional Taste</h3>
        <p class="text-sm text-brand-muted">
          Comforting, familiar grains.
        </p>
      </a>
    </div>
  </div>
</section>

<!-- ================= PREMIUM PRODUCTS ================= -->
<section class="bg-white py-14 sm:py-16">
  <div class="mx-auto max-w-7xl px-6 relative">

    <h2 class="reveal text-center text-3xl font-extrabold mb-10">
      Grains You’ll Love Cooking With
    </h2>

    <!-- SLIDER CONTROLS -->
    <button
      onclick="scrollProducts(-1)"
      class="absolute left-0 top-1/2 -translate-y-1/2 z-10
             w-10 h-10 rounded-full bg-white shadow
             flex items-center justify-center
             hover:scale-105 transition">
      ‹
    </button>

    <button
      onclick="scrollProducts(1)"
      class="absolute right-0 top-1/2 -translate-y-1/2 z-10
             w-10 h-10 rounded-full bg-white shadow
             flex items-center justify-center
             hover:scale-105 transition">
      ›
    </button>

    <!-- HORIZONTAL SCROLLER -->
    <div
      id="productScroller"
      class="flex gap-6 overflow-x-scroll scroll-smooth
             px-1
             [-ms-overflow-style:none]
             [scrollbar-width:none]
             [&::-webkit-scrollbar]:hidden">

      <?php while ($product = $products->fetch_assoc()):
        $packs = json_decode($product['pack_sizes'], true) ?? [2,5,10,30];
        $firstPack = (int)$packs[0];
      ?>

      <!-- PRODUCT CARD (EXACT SAME AS product.php) -->
      <div
        class="product-card group
               min-w-[260px] max-w-[260px]
               border border-gray-200
               rounded-2xl
               bg-white
               overflow-hidden
               flex flex-col
               transition-all duration-300
               hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300"
        data-id="<?= (int)$product['id'] ?>"
        data-slug="<?= htmlspecialchars($product['slug']) ?>">

        <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">

          <?php
          $isWishlisted = in_array(
            (int)$product['id'],
            $_SESSION['wishlist'] ?? []
          );
          ?>

          <button
            class="wishlist-btn absolute top-3 right-3
                   w-9 h-9 rounded-full bg-white/90
                   flex items-center justify-center shadow z-10"
            data-id="<?= (int)$product['id'] ?>">

            <i class="<?= $isWishlisted
              ? 'fa-solid fa-heart text-emerald-600'
              : 'fa-regular fa-heart text-gray-700' ?>"></i>
          </button>

          <img
            src="/IndusAgrii/uploads/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>"
            alt="<?= htmlspecialchars($product['name']) ?>"
            class="w-full h-full object-cover
                   transition-transform duration-500
                   group-hover:scale-105">
        </div>

        <div class="p-3 flex flex-col gap-2">

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

          <p
            class="product-price text-base font-bold mt-1"
            data-base-price="<?= (float)$product['base_price'] ?>">
            ₹<?= number_format($product['base_price'] * $firstPack, 2) ?>
          </p>

          <div class="flex gap-2 pt-1">
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

        </div>
      </div>

      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- ================= TRUST SLIDER ================= -->
<section class="bg-gray-50 py-14 sm:py-16 overflow-hidden">
  <div class="mx-auto max-w-7xl px-6">
    <h2 class="reveal text-center text-3xl font-extrabold mb-10">
      Trusted in Everyday Indian Kitchens
    </h2>
  </div>

  <div class="relative overflow-hidden">
    <div class="flex gap-12 animate-[scroll_30s_linear_infinite] w-max px-6 py-2">

      <!-- ITEM -->
      <div class="flex items-center gap-4 min-w-[300px] px-2">
        <i class="fa-solid fa-shield-heart text-3xl text-brand-primary"></i>
        <span class="text-lg">Safe for all age groups</span>
      </div>

      <div class="flex items-center gap-4 min-w-[300px] px-2">
        <i class="fa-solid fa-scale-balanced text-3xl text-brand-primary"></i>
        <span class="text-lg">Consistent taste every time</span>
      </div>

      <div class="flex items-center gap-4 min-w-[300px] px-2">
        <i class="fa-solid fa-bowl-food text-3xl text-brand-primary"></i>
        <span class="text-lg">No surprises while cooking</span>
      </div>

      <div class="flex items-center gap-4 min-w-[300px] px-2">
        <i class="fa-solid fa-heart text-3xl text-brand-primary"></i>
        <span class="text-lg">Chosen for family meals</span>
      </div>

      <!-- DUPLICATE FOR LOOP -->
      <div class="flex items-center gap-4 min-w-[300px] px-2">
        <i class="fa-solid fa-shield-heart text-3xl text-brand-primary"></i>
        <span class="text-lg">Safe for all age groups</span>
      </div>

      <div class="flex items-center gap-4 min-w-[300px] px-2">
        <i class="fa-solid fa-scale-balanced text-3xl text-brand-primary"></i>
        <span class="text-lg">Consistent taste every time</span>
      </div>

    </div>
  </div>
</section>



<section class="bg-white py-14 sm:py-16">
  <div class="max-w-6xl mx-auto px-6">

    <div class="grid gap-10 sm:grid-cols-3 text-center">

      <div class="stat-item opacity-0 translate-y-4 transition-all duration-700">
        <p class="text-4xl font-extrabold text-brand-primary tracking-tight">
          <span class="countup" data-target="3000">0</span>+
        </p>
        <p class="mt-2 text-sm text-brand-muted">
          Indian homes trust IndusAgrii for daily cooking
        </p>
      </div>

      <div class="stat-item opacity-0 translate-y-4 transition-all duration-700 delay-150">
        <p class="text-xl font-semibold">
          Consistent Batches
        </p>
        <p class="mt-2 text-sm text-brand-muted">
          Same grain quality across every delivery
        </p>
      </div>

      <div class="stat-item opacity-0 translate-y-4 transition-all duration-700 delay-300">
        <p class="text-xl font-semibold">
          Most Reordered
        </p>
        <p class="mt-2 text-sm text-brand-muted">
          Preferred choice for everyday family meals
        </p>
      </div>

    </div>
  </div>
</section>


<section class="bg-gray-50 py-12 sm:py-14">
  <div class="max-w-7xl mx-auto px-6">

    <!-- HEADER -->
    <div class="text-center mb-10">
      <h2 class="text-2xl sm:text-3xl font-extrabold">
        What Our Customers Say
      </h2>
      <p class="mt-2 text-sm text-brand-muted max-w-xl mx-auto">
        Real feedback from households that cook with IndusAgrii every day.
      </p>
    </div>

    <!-- SLIDER WRAPPER -->
    <div class="relative">

      <!-- PREV -->
      <button
        id="testimonialPrev"
        class="flex absolute absolute -left-5 top-1/2 -translate-y-1/2
               w-10 h-10 rounded-full bg-white shadow
               items-center justify-center
               hover:scale-105 transition z-10">
        ‹
      </button>

      <!-- NEXT -->
      <button
        id="testimonialNext"
        class="flex absolute absolute -right-5 top-1/2 -translate-y-1/2
               w-10 h-10 rounded-full bg-white shadow
               items-center justify-center
               hover:scale-105 transition z-10">
        ›
      </button>

      <!-- VIEWPORT -->
      <div class="overflow-hidden">

        <!-- TRACK -->
        <div
            id="testimonialTrack"
            class="flex gap-6 will-change-transform transition-transform duration-500">

          <!-- CARD -->
          <div class="testimonial-card min-w-[85%] sm:min-w-[320px] max-w-[380px]
                      bg-white border border-gray-200 rounded-2xl p-6 flex-shrink-0">
            <div class="flex gap-1 text-sm text-amber-500">★★★★★</div>
            <p class="mt-4 text-sm text-gray-700">
              “The biggest difference for us is consistency. The rice cooks the
              same way every single time.”
            </p>
            <div class="mt-5">
              <p class="text-sm font-semibold">Anjali Deshmukh</p>
              <p class="text-xs text-brand-muted">Pune, Maharashtra</p>
            </div>
          </div>

          <div class="testimonial-card min-w-[85%] sm:min-w-[320px] max-w-[380px]
                      bg-white border border-gray-200 rounded-2xl p-6 flex-shrink-0">
            <div class="flex gap-1 text-sm text-amber-500">★★★★☆</div>
            <p class="mt-4 text-sm text-gray-700">
              “No more adjusting water and timing. Cooking has become predictable.”
            </p>
            <div class="mt-5">
              <p class="text-sm font-semibold">Ramesh Patil</p>
              <p class="text-xs text-brand-muted">Nashik, Maharashtra</p>
            </div>
          </div>

          <div class="testimonial-card min-w-[85%] sm:min-w-[320px] max-w-[380px]
                      bg-white border border-gray-200 rounded-2xl p-6 flex-shrink-0">
            <div class="flex gap-1 text-sm text-amber-500">★★★★★</div>
            <p class="mt-4 text-sm text-gray-700">
              “Even after reheating the next day, the rice stays soft and fresh.”
            </p>
            <div class="mt-5">
              <p class="text-sm font-semibold">Neha Kulkarni</p>
              <p class="text-xs text-brand-muted">Nagpur, Maharashtra</p>
            </div>
          </div>

          <div class="testimonial-card min-w-[85%] sm:min-w-[320px] max-w-[380px]
                      bg-white border border-gray-200 rounded-2xl p-6 flex-shrink-0">
            <div class="flex gap-1 text-sm text-amber-500">★★★★☆</div>
            <p class="mt-4 text-sm text-gray-700">
              “We started small, but switched fully once we saw the consistency.”
            </p>
            <div class="mt-5">
              <p class="text-sm font-semibold">Suresh Joshi</p>
              <p class="text-xs text-brand-muted">Kolhapur, Maharashtra</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>


<section class="bg-gray-50 py-14 sm:py-16">
  <div class="max-w-6xl mx-auto px-6">

    <h2 class="text-center text-3xl font-extrabold mb-14">
      Helpful Kitchen Tips
    </h2>

    <div class="grid gap-8 md:grid-cols-3 items-start">

      <!-- Tip 1 -->
      <div class="tip-item opacity-0 translate-y-4 transition-all duration-700
                  border border-gray-200 rounded-2xl p-6 bg-white">

        <button type="button"
          class="tip-toggle flex w-full items-center justify-between
                 font-semibold text-left cursor-pointer">
          How should rice be stored at home?
          <span class="arrow transition-transform duration-300">▼</span>
        </button>

        <div class="tip-content hidden mt-4 text-sm text-brand-muted leading-relaxed">
          Rice should always be stored in a clean, dry, airtight container to protect it from moisture.
          As a traditional and effective practice, a few dried neem leaves can be placed on top of the rice
          before sealing the container.
          Neem naturally helps repel insects and preserves grain quality without affecting taste.
          Store the container away from heat, sunlight, and the cooking area to maintain freshness for longer periods.
        </div>
      </div>

      <!-- Tip 2 -->
      <div class="tip-item opacity-0 translate-y-4 transition-all duration-700 delay-150
                  border border-gray-200 rounded-2xl p-6 bg-white">

        <button type="button"
          class="tip-toggle flex w-full items-center justify-between
                 font-semibold text-left cursor-pointer">
          Which rice works best for daily meals?
          <span class="arrow transition-transform duration-300">▼</span>
        </button>

        <div class="tip-content hidden mt-4 text-sm text-brand-muted leading-relaxed">
          When cooked correctly, rice develops a naturally gooey and slightly sticky texture,
          which enhances its overall taste.
          Over time, one can cultivate a preference for enjoying such flavorful rice with curry.
          This texture makes meals more satisfying and encourages regular, everyday consumption
          without feeling heavy.
        </div>
      </div>

      <!-- Tip 3 -->
      <div class="tip-item opacity-0 translate-y-4 transition-all duration-700 delay-300
                  border border-gray-200 rounded-2xl p-6 bg-white">

        <button type="button"
          class="tip-toggle flex w-full items-center justify-between
                 font-semibold text-left cursor-pointer">
          How to cook millets for softer texture?
          <span class="arrow transition-transform duration-300">▼</span>
        </button>

        <div class="tip-content hidden mt-4 text-sm text-brand-muted leading-relaxed">
          To properly extract the nutritional benefits of millets, including fiber and other essential nutrients,
          soaking plays a crucial role.
          Soaking millets for more than seven hours is considered the best practice before cooking.
          This process improves nutrient absorption, enhances digestion, and results in better texture after cooking.
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ================= FAQ SECTION ================= -->
<section class="bg-white py-14 sm:py-16">
  <div class="max-w-6xl mx-auto px-6">

    <!-- HEADER -->
    <div class="text-center mb-12">
      <h2 class="text-2xl sm:text-3xl font-extrabold">
        Frequently Asked Questions
      </h2>
      <p class="mt-2 text-sm text-brand-muted max-w-xl mx-auto">
        Clear and reliable answers to common questions about our grains,
        sourcing practices, and everyday cooking.
      </p>
    </div>

    <!-- FAQ LIST -->
    <div class="space-y-4">

      <!-- ITEM -->
      <div class="faq-item border border-gray-200 rounded-2xl p-6">
        <button type="button"
          class="faq-toggle flex w-full items-center justify-between font-semibold text-left">
          Is your rice suitable for daily consumption?
          <span class="arrow transition-transform duration-300">+</span>
        </button>

        <div class="faq-content hidden mt-4 text-sm text-brand-muted leading-relaxed">
          Yes. IndusAgrii rice is carefully selected and processed specifically
          for everyday home cooking. The grains cook evenly, absorb water
          consistently, and maintain a soft texture throughout the meal.
          <br>
          This consistency allows households to cook daily meals without
          needing to adjust water ratios or cooking time.
        </div>
      </div>

      <!-- ITEM -->
      <div class="faq-item border border-gray-200 rounded-2xl p-6">
        <button type="button"
          class="faq-toggle flex w-full items-center justify-between font-semibold text-left">
          How is IndusAgrii different from local market rice?
          <span class="arrow transition-transform duration-300">+</span>
        </button>

        <div class="faq-content hidden mt-4 text-sm text-brand-muted leading-relaxed">
          Rice sold in local markets often comes from mixed or inconsistent
          batches, which may vary in grain age, size, and moisture content.
          This can result in unpredictable cooking outcomes.
          <br>
          IndusAgrii follows controlled sourcing and batch-level quality checks,
          ensuring uniform grain quality and consistent cooking results
          with every purchase.
        </div>
      </div>

      <!-- ITEM -->
      <div class="faq-item border border-gray-200 rounded-2xl p-6">
        <button type="button"
          class="faq-toggle flex w-full items-center justify-between font-semibold text-left">
          How long can grains be stored without compromising quality?
          <span class="arrow transition-transform duration-300">+</span>
        </button>

        <div class="faq-content hidden mt-4 text-sm text-brand-muted leading-relaxed">
          When stored under proper conditions, rice and millets can be safely
          stored for several months without a noticeable loss in quality.
          <br>
          Proper storage helps preserve flavour, texture, and cooking
          performance throughout the product’s shelf life.
        </div>
      </div>

    </div>
  </div>
</section>


<script>
document.addEventListener("DOMContentLoaded", () => {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.remove("opacity-0", "translate-y-4");

        if (entry.target.classList.contains("stat-item")) {
          const counter = entry.target.querySelector(".countup");
          if (counter && !counter.dataset.done) {
            counter.dataset.done = "true";
            let current = 0;
            const target = +counter.dataset.target;
            const increment = Math.ceil(target / 60);

            const update = () => {
              current += increment;
              if (current >= target) {
                counter.textContent = target;
              } else {
                counter.textContent = current;
                requestAnimationFrame(update);
              }
            };
            update();
          }
        }

        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.25 });

  document.querySelectorAll(".stat-item, .feature-item, .tip-item")
    .forEach(el => observer.observe(el));
});

document.querySelectorAll(".faq-toggle").forEach(btn => {
  btn.addEventListener("click", () => {
    const content = btn.nextElementSibling;
    const arrow = btn.querySelector(".arrow");

    content.classList.toggle("hidden");
    arrow.textContent = content.classList.contains("hidden") ? "+" : "−";
  });
});


</script>




<?php include "../includes/footer.php"; ?>