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

      <!-- 1️⃣ All Categories -->
      <a href="products.php"
         class="guided-card reveal rounded-2xl border p-6 text-center transition
                hover:-translate-y-1 hover:shadow-lg
                hover:border-brand-primary/40">
        <i class="fa-solid fa-house text-3xl text-brand-primary mb-3"></i>
        <h3 class="font-bold text-base mb-1">For Family Meals</h3>
        <p class="text-sm text-brand-muted">
          Soft, consistent rice for daily cooking.
        </p>
      </a>

      <!-- 2️⃣ Rice -->
      <a href="rice.php"
         class="guided-card reveal rounded-2xl border p-6 text-center transition
                hover:-translate-y-1 hover:shadow-lg
                hover:border-brand-primary/40">
        <i class="fa-solid fa-bowl-rice text-3xl text-brand-primary mb-3"></i>
        <h3 class="font-bold text-base mb-1">Everyday Rice</h3>
        <p class="text-sm text-brand-muted">
          Reliable taste you can trust daily.
        </p>
      </a>

      <!-- 3️⃣ Millets -->
      <a href="millets.php"
         class="guided-card reveal rounded-2xl border p-6 text-center transition
                hover:-translate-y-1 hover:shadow-lg
                hover:border-brand-primary/40">
        <i class="fa-solid fa-leaf text-3xl text-brand-primary mb-3"></i>
        <h3 class="font-bold text-base mb-1">Healthy Choices</h3>
        <p class="text-sm text-brand-muted">
          Naturally nutritious millets.
        </p>
      </a>

      <!-- 4️⃣ All Categories -->
      <a href="products.php"
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
<section class="bg-gray-50 py-16 sm:py-20" id="faq-section">
  <div class="max-w-4xl mx-auto px-6">

    <div class="text-center mb-12">
      <span class="text-brand-primary font-bold tracking-wide uppercase text-sm">Got Questions?</span>
      <h2 class="mt-2 text-3xl sm:text-4xl font-extrabold text-brand-dark">
        Frequently Asked Questions
      </h2>
      <p class="mt-4 text-base text-brand-muted max-w-2xl mx-auto leading-relaxed">
        Everything you need to know about our sourcing, delivery timelines, return policies, and product quality. Transparency is our priority.
      </p>
    </div>

    <div class="space-y-4">

      <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition hover:shadow-md">
        <button type="button" class="faq-toggle flex w-full items-center justify-between p-6 font-bold text-left text-brand-dark hover:text-brand-primary transition-colors">
          <span>Is your rice suitable for daily consumption?</span>
          <span class="icon transition-transform duration-300 text-brand-primary text-xl">
            <i class="fa-solid fa-plus"></i>
          </span>
        </button>
        <div class="faq-content hidden px-6 pb-6 text-brand-muted text-sm sm:text-base leading-relaxed border-t border-gray-100 pt-4">
          Absolutely. IndusAgrii rice (especially our Indrayani and Kala Namak varieties) is sourced and processed specifically for everyday home cooking. Unlike highly polished commercial rice that strips away nutrients, our grains retain their natural fiber and essential minerals.<br><br>
          They cook evenly, are easy to digest, and are free from artificial glazing agents or wax, making them the healthiest choice for your family's daily meals.
        </div>
      </div>

      <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition hover:shadow-md">
        <button type="button" class="faq-toggle flex w-full items-center justify-between p-6 font-bold text-left text-brand-dark hover:text-brand-primary transition-colors">
          <span>How is IndusAgrii different from local market rice?</span>
          <span class="icon transition-transform duration-300 text-brand-primary text-xl">
            <i class="fa-solid fa-plus"></i>
          </span>
        </button>
        <div class="faq-content hidden px-6 pb-6 text-brand-muted text-sm sm:text-base leading-relaxed border-t border-gray-100 pt-4">
          Local market loose rice often comes from mixed batches with unknown origins, leading to inconsistent cooking (some grains remain hard, others become mushy). It is also frequently stored in open sacks, exposing it to dust and moisture.<br><br>
          <strong>IndusAgrii Advantage:</strong> We source directly from specific regions (e.g., Maval for Indrayani). Every batch is lab-tested for purity, cleaned mechanically, and packed in hygienic, moisture-proof packaging to ensure the last cup tastes as fresh as the first.
        </div>
      </div>

      <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition hover:shadow-md">
        <button type="button" class="faq-toggle flex w-full items-center justify-between p-6 font-bold text-left text-brand-dark hover:text-brand-primary transition-colors">
          <span>How long does delivery take and what are the charges?</span>
          <span class="icon transition-transform duration-300 text-brand-primary text-xl">
            <i class="fa-solid fa-plus"></i>
          </span>
        </button>
        <div class="faq-content hidden px-6 pb-6 text-brand-muted text-sm sm:text-base leading-relaxed border-t border-gray-100 pt-4">
          We strive to deliver your order as fresh as possible.
          <ul class="list-disc pl-5 mt-2 space-y-1">
            <li><strong>Metro Cities:</strong> 2 to 4 business days.</li>
            <li><strong>Rest of India:</strong> 5 to 7 business days.</li>
          </ul>
          <br>
          Shipping is calculated based on the weight of your order and your location. However, we frequently run <strong>Free Shipping</strong> offers on orders above a certain value. You can check the exact shipping cost on the Checkout page before payment.
        </div>
      </div>

      <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition hover:shadow-md">
        <button type="button" class="faq-toggle flex w-full items-center justify-between p-6 font-bold text-left text-brand-dark hover:text-brand-primary transition-colors">
          <span>What is your Return & Refund Policy?</span>
          <span class="icon transition-transform duration-300 text-brand-primary text-xl">
            <i class="fa-solid fa-plus"></i>
          </span>
        </button>
        <div class="faq-content hidden px-6 pb-6 text-brand-muted text-sm sm:text-base leading-relaxed border-t border-gray-100 pt-4">
          We have a customer-friendly policy. While food products generally cannot be returned due to hygiene reasons, we offer a <strong>100% Replacement or Refund</strong> if:
          <ul class="list-disc pl-5 mt-2 space-y-1">
            <li>The package arrives damaged or unsealed.</li>
            <li>The product received is incorrect or expired.</li>
            <li>There is a genuine quality issue with the grain.</li>
          </ul>
          <br>
          Please report any issues to our support team within <strong>48 hours</strong> of delivery with photos, and we will resolve it immediately.
        </div>
      </div>

      <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition hover:shadow-md">
        <button type="button" class="faq-toggle flex w-full items-center justify-between p-6 font-bold text-left text-brand-dark hover:text-brand-primary transition-colors">
          <span>Is my personal information and payment safe?</span>
          <span class="icon transition-transform duration-300 text-brand-primary text-xl">
            <i class="fa-solid fa-plus"></i>
          </span>
        </button>
        <div class="faq-content hidden px-6 pb-6 text-brand-muted text-sm sm:text-base leading-relaxed border-t border-gray-100 pt-4">
          Yes, your security is our top priority. We use industry-standard <strong>SSL encryption</strong> to protect your personal data. We do not store your credit/debit card details.<br><br>
          All payments are processed through secure gateways (Razorpay/PhonePe) regulated by the RBI. We strictly adhere to a privacy policy that ensures your phone number and email are never sold to third parties.
        </div>
      </div>

      <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition hover:shadow-md">
        <button type="button" class="faq-toggle flex w-full items-center justify-between p-6 font-bold text-left text-brand-dark hover:text-brand-primary transition-colors">
          <span>Do you accept bulk orders for events or retail?</span>
          <span class="icon transition-transform duration-300 text-brand-primary text-xl">
            <i class="fa-solid fa-plus"></i>
          </span>
        </button>
        <div class="faq-content hidden px-6 pb-6 text-brand-muted text-sm sm:text-base leading-relaxed border-t border-gray-100 pt-4">
          Yes! We supply to hotels, caterers, wedding planners, and retail shops. For bulk quantities (above 50kg), we offer special wholesale pricing and customized logistics support.<br><br>
          Please visit our <a href="#" class="text-brand-primary font-semibold hover:underline">Contact Us</a> page or email us directly to get a quote.
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

document.addEventListener('DOMContentLoaded', () => {
    const toggles = document.querySelectorAll('.faq-toggle');

    toggles.forEach(toggle => {
      toggle.addEventListener('click', () => {
        const content = toggle.nextElementSibling;
        const icon = toggle.querySelector('.icon i');

        // Toggle current item
        if (content.classList.contains('hidden')) {
            // Open
            content.classList.remove('hidden');
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');
            toggle.classList.add('bg-gray-50'); // Optional active state bg
        } else {
            // Close
            content.classList.add('hidden');
            icon.classList.remove('fa-minus');
            icon.classList.add('fa-plus');
            toggle.classList.remove('bg-gray-50');
        }
      });
    });
  });
</script>




<?php include "../includes/footer.php"; ?>