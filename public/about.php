<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* =========================================================
   PAGE META & HEADER CONTROL (PRODUCTION)
========================================================= */
$pageTitle = "About IndusAgrii | Pure Farm Produce from India";
$pageDescription = "Discover IndusAgrii – a farm-direct brand delivering premium rice and millets across India with trust, consistency, and transparency.";

// Hero-style page → transparent header initially
$bodyClass = "about-page bg-white text-brand-dark antialiased";

require_once __DIR__ . "/../includes/header.php";
?>

<!-- ================= ABOUT HERO ================= -->
<section class="relative overflow-hidden">

  <!-- Background image -->
  <img
    src="/IndusAgrii/assets/images/banners/hero2.jpg"
    alt="About IndusAgrii – Farm Direct Produce"
    class="absolute inset-0 h-full w-full object-cover"
    loading="eager"
  />

  <!-- Overlay -->
  <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/30"></div>

  <!-- Content -->
  <div class="relative z-10 mx-auto max-w-7xl px-6
              pt-[calc(8rem+env(safe-area-inset-top))]
              pb-24">

    <span class="inline-block rounded-full bg-white/20 px-4 py-1
                 text-sm font-semibold text-white backdrop-blur">
      Rooted in Indian Agriculture
    </span>

    <h1 class="mt-5 max-w-3xl text-4xl sm:text-5xl lg:text-6xl
               font-extrabold leading-tight text-white">
      About <span class="text-green-300">IndusAgrii</span>
    </h1>

    <p class="mt-6 max-w-2xl text-lg sm:text-xl text-white/95 leading-relaxed">
      IndusAgrii connects Indian farms directly to kitchens and businesses,
      delivering trusted grains with consistency, care, and transparency.
    </p>

  </div>
</section>

<!-- ================= OUR STORY ================= -->
<section class="bg-white py-16">
  <div class="mx-auto max-w-7xl px-6 grid gap-12 lg:grid-cols-2 items-center">

    <!-- Text -->
    <div class="space-y-6">
      <h2 class="reveal text-3xl font-extrabold">Our Story</h2>

      <p class="reveal text-lg leading-relaxed text-brand-muted">
        IndusAgrii was built with a simple belief: Indian households and businesses
        deserve grains they can trust every single day. We work closely with
        farmers and suppliers to ensure responsible sourcing and careful processing.
      </p>

      <p class="reveal text-lg leading-relaxed text-brand-muted">
        From daily family meals to bulk requirements, our focus is consistency —
        the same taste, texture, and confidence in every batch we deliver.
      </p>
    </div>

    <!-- Image -->
    <div class="reveal relative overflow-hidden rounded-3xl shadow-lg">
      <img
        src="/IndusAgrii/assets/images/banners/hero1.jpg"
        alt="Indian farming and grain sourcing"
        class="h-full w-full object-cover transition-transform duration-700 hover:scale-105"
        loading="lazy"
      />
    </div>

  </div>
</section>

<!-- ================= VALUES ================= -->
<section class="bg-gray-50 py-16">
  <div class="mx-auto max-w-7xl px-6">

    <h2 class="reveal mb-12 text-center text-3xl font-extrabold">
      What We Stand For
    </h2>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

      <div class="reveal rounded-2xl bg-white border p-6 text-center
                  transition hover:-translate-y-1 hover:shadow-lg">
        <i class="fa-solid fa-seedling mb-3 text-3xl text-brand-primary"></i>
        <h3 class="mb-1 font-bold">Farm-Direct</h3>
        <p class="text-sm text-brand-muted">
          Sourced responsibly from trusted Indian farmers.
        </p>
      </div>

      <div class="reveal rounded-2xl bg-white border p-6 text-center
                  transition hover:-translate-y-1 hover:shadow-lg">
        <i class="fa-solid fa-shield-halved mb-3 text-3xl text-brand-primary"></i>
        <h3 class="mb-1 font-bold">Quality First</h3>
        <p class="text-sm text-brand-muted">
          Clean, consistent, and carefully processed grains.
        </p>
      </div>

      <div class="reveal rounded-2xl bg-white border p-6 text-center
                  transition hover:-translate-y-1 hover:shadow-lg">
        <i class="fa-solid fa-truck mb-3 text-3xl text-brand-primary"></i>
        <h3 class="mb-1 font-bold">Reliable Supply</h3>
        <p class="text-sm text-brand-muted">
          Smooth delivery across India with dependable logistics.
        </p>
      </div>

      <div class="reveal rounded-2xl bg-white border p-6 text-center
                  transition hover:-translate-y-1 hover:shadow-lg">
        <i class="fa-solid fa-heart mb-3 text-3xl text-brand-primary"></i>
        <h3 class="mb-1 font-bold">Customer Trust</h3>
        <p class="text-sm text-brand-muted">
          Built for long-term confidence in everyday cooking.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ================= WHO WE SERVE ================= -->
<section class="bg-white py-16">
  <div class="mx-auto max-w-7xl px-6">

    <h2 class="reveal mb-10 text-center text-3xl font-extrabold">
      Who We Serve
    </h2>

    <div class="grid gap-8 md:grid-cols-3">

      <div class="reveal rounded-xl border p-6">
        <h3 class="mb-2 text-lg font-bold">Households</h3>
        <p class="text-brand-muted">
          Daily-use rice and millets for families who value taste and safety.
        </p>
      </div>

      <div class="reveal rounded-xl border p-6">
        <h3 class="mb-2 text-lg font-bold">Retailers</h3>
        <p class="text-brand-muted">
          Consistent stock and dependable quality for your customers.
        </p>
      </div>

      <div class="reveal rounded-xl border p-6">
        <h3 class="mb-2 text-lg font-bold">Bulk Buyers</h3>
        <p class="text-brand-muted">
          Reliable sourcing for hotels, caterers, and institutions.
        </p>
      </div>

    </div>
  </div>
</section>



<?php require_once __DIR__ . "/../includes/footer.php"; ?>