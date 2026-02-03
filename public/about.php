<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* =========================================================
   PAGE META & HEADER CONTROL
========================================================= */
$pageTitle = "About IndusAgrii | Pure Farm Produce from India";
$pageDescription = "Discover IndusAgrii – a farm-direct brand delivering premium rice and millets across India with trust, consistency, and transparency.";

// Hero-style page → transparent header initially
$bodyClass = "about-page bg-white text-brand-dark antialiased";

require_once __DIR__ . "/../includes/header.php";
?>

<section class="relative overflow-hidden h-[500px] lg:h-[600px]">

  <img
    src="/IndusAgrii/assets/images/banners/hero2.jpg"
    alt="About IndusAgrii – Farm Direct Produce"
    class="absolute inset-0 h-full w-full object-cover"
    loading="eager"
  />

  <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/30"></div>

  <div class="relative z-10 mx-auto max-w-7xl px-6 h-full flex flex-col justify-center">
    
    <div class="pt-16">
        <span class="inline-block rounded-full bg-white/20 px-4 py-1
                     text-sm font-semibold text-white backdrop-blur border border-white/10">
          Rooted in Indian Agriculture
        </span>
    
        <h1 class="mt-5 max-w-3xl text-4xl sm:text-5xl lg:text-6xl
                   font-extrabold leading-tight text-white shadow-sm">
          About <span class="text-green-300">IndusAgrii</span>
        </h1>
    
        <p class="mt-6 max-w-2xl text-lg sm:text-xl text-white/90 leading-relaxed">
          IndusAgrii connects Indian farms directly to kitchens and businesses,
          delivering trusted grains with consistency, care, and transparency.
        </p>
    </div>

  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-6 space-y-20">

    <div class="grid gap-12 lg:gap-16 lg:grid-cols-2 items-center">

      <div class="space-y-6">
        <div class="reveal">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-brand-dark mb-4">
              Our Story
            </h2>
            <p class="text-xl font-semibold text-brand-primary">
              From the Fields We Trust, To the Tables We Serve
            </p>
        </div>

        <div class="reveal space-y-4 text-brand-muted text-lg leading-relaxed">
            <p>
              In a world of long supply chains and unknown origins, we asked a simple question:
              <span class="font-semibold text-brand-dark">
                What if you could trace every grain back to the farmer who grew it?
              </span>
            </p>
            <p>
              It began with <span class="font-semibold text-brand-dark">Indrayani rice from the Maval region</span> — celebrated for generations for its aroma. Over time, middlemen and repackaging made authenticity difficult.
            </p>
            <p>
              We decided to change that.
            </p>
        </div>

        <div class="reveal border-l-4 border-brand-primary pl-6 py-2 bg-gray-50 rounded-r-lg">
          <h3 class="font-bold text-lg mb-1 text-brand-dark">
            Our Promise: Direct · Authentic · Traceable
          </h3>
          <p class="text-brand-muted">
            We work directly with farmers and FPOs. No middlemen. No compromises. Every grain comes straight from the hands that cultivated it.
          </p>
        </div>
      </div>

      <div class="reveal relative">
        <div class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl bg-gray-100 transform rotate-1 hover:rotate-0 transition duration-500">
          <img
            src="/IndusAgrii/assets/images/banners/hero1.jpg"
            alt="IndusAgrii farm sourcing"
            class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition duration-700"
          />
          <div class="absolute inset-0 bg-black/10"></div>
        </div>
        <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-brand-primary/10 rounded-full -z-10 hidden lg:block"></div>
        <div class="absolute -top-6 -right-6 w-32 h-32 bg-yellow-400/10 rounded-full -z-10 hidden lg:block"></div>
      </div>

    </div>

    <div class="reveal">
        <div class="h-px bg-gray-200 w-full mb-12"></div>
        
        <div class="grid gap-8 md:grid-cols-3">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-location-dot text-brand-primary text-xl"></i>
                </div>
                <h4 class="font-bold text-lg text-brand-dark mb-2">Indrayani Rice</h4>
                <p class="text-brand-muted text-sm leading-relaxed">
                  From the legendary <span class="font-semibold">Maval region</span> of Maharashtra. The soil and climate here create its distinctive fragrance and delicate sticky texture.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-leaf text-brand-primary text-xl"></i>
                </div>
                <h4 class="font-bold text-lg text-brand-dark mb-2">Kala Namak Rice</h4>
                <p class="text-brand-muted text-sm leading-relaxed">
                  From <span class="font-semibold">Uttar Pradesh</span>. Known as "Buddha Rice," it is treasured for its heritage aroma, black husk, and unique nutritional profile.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-wheat-awn text-brand-primary text-xl"></i>
                </div>
                <h4 class="font-bold text-lg text-brand-dark mb-2">Traditional Millets</h4>
                <p class="text-brand-muted text-sm leading-relaxed">
                  Sourced from farmers who have preserved these ancient grains. Rich in fiber and grown using sustainable farming practices.
                </p>
            </div>
        </div>
    </div>

    <div class="reveal bg-gray-50 rounded-3xl p-8 lg:p-12">
        <div class="grid gap-10 lg:grid-cols-2">
            
            <div class="space-y-4">
                <h4 class="flex items-center gap-3 font-bold text-xl text-brand-dark">
                  <i class="fa-solid fa-heart text-brand-primary"></i>
                  Why We Do This
                </h4>
                <p class="text-brand-muted leading-relaxed">
                  Because farmers deserve fair prices. Because you deserve to know where your food comes from. Authentic grains — grown with care and harvested with pride — nourish better and simply taste better.
                </p>
            </div>

            <div class="space-y-4 lg:border-l lg:border-gray-200 lg:pl-10">
                <h4 class="flex items-center gap-3 font-bold text-xl text-brand-dark">
                  <i class="fa-solid fa-handshake text-brand-primary"></i>
                  Join Our Journey
                </h4>
                <p class="text-brand-muted leading-relaxed">
                  Every purchase supports farmers. Every meal reconnects you to the land.
                  <span class="block mt-3 font-semibold text-brand-dark">
                    From our fields to your family — pure, authentic, honest.
                  </span>
                </p>
            </div>

        </div>
    </div>

  </div>
</section>

<section class="bg-white py-16 border-t border-gray-100">
  <div class="mx-auto max-w-7xl px-6">

    <div class="reveal text-center mb-12">
        <h2 class="text-3xl font-extrabold text-brand-dark">What We Stand For</h2>
        <p class="mt-2 text-brand-muted">The pillars of the IndusAgrii brand</p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

      <div class="reveal group rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-brand-primary/30">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-50 text-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-colors">
            <i class="fa-solid fa-seedling text-2xl"></i>
        </div>
        <h3 class="mb-2 font-bold text-lg">Farm-Direct</h3>
        <p class="text-sm text-brand-muted">Sourced responsibly from trusted Indian farmers.</p>
      </div>

      <div class="reveal group rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-brand-primary/30">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-50 text-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-colors">
            <i class="fa-solid fa-shield-halved text-2xl"></i>
        </div>
        <h3 class="mb-2 font-bold text-lg">Quality First</h3>
        <p class="text-sm text-brand-muted">Clean, consistent, and carefully processed grains.</p>
      </div>

      <div class="reveal group rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-brand-primary/30">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-50 text-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-colors">
            <i class="fa-solid fa-truck text-2xl"></i>
        </div>
        <h3 class="mb-2 font-bold text-lg">Reliable Supply</h3>
        <p class="text-sm text-brand-muted">Smooth delivery across India with dependable logistics.</p>
      </div>

      <div class="reveal group rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-brand-primary/30">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-50 text-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-colors">
            <i class="fa-solid fa-heart text-2xl"></i>
        </div>
        <h3 class="mb-2 font-bold text-lg">Customer Trust</h3>
        <p class="text-sm text-brand-muted">Built for long-term confidence in everyday cooking.</p>
      </div>

    </div>
  </div>
</section>

<section class="bg-gray-50 py-20">
  <div class="mx-auto max-w-7xl px-6">

    <h2 class="reveal mb-12 text-center text-3xl font-extrabold text-brand-dark">
      Who We Serve
    </h2>

    <div class="grid gap-8 md:grid-cols-3">

      <div class="reveal flex flex-col items-center text-center bg-white rounded-2xl p-8 shadow-sm border border-gray-100 h-full">
        <div class="mb-4 p-3 bg-blue-50 text-blue-600 rounded-lg">
            <i class="fa-solid fa-house-chimney text-2xl"></i>
        </div>
        <h3 class="mb-3 text-xl font-bold text-brand-dark">Households</h3>
        <p class="text-brand-muted leading-relaxed">
          Daily-use rice and millets for families who value taste, safety, and authentic nutrition on their dining table.
        </p>
      </div>

      <div class="reveal flex flex-col items-center text-center bg-white rounded-2xl p-8 shadow-sm border border-gray-100 h-full">
        <div class="mb-4 p-3 bg-purple-50 text-purple-600 rounded-lg">
            <i class="fa-solid fa-store text-2xl"></i>
        </div>
        <h3 class="mb-3 text-xl font-bold text-brand-dark">Retailers</h3>
        <p class="text-brand-muted leading-relaxed">
          Consistent stock and dependable quality for your customers, ensuring repeat purchases and brand loyalty.
        </p>
      </div>

      <div class="reveal flex flex-col items-center text-center bg-white rounded-2xl p-8 shadow-sm border border-gray-100 h-full">
        <div class="mb-4 p-3 bg-orange-50 text-orange-600 rounded-lg">
            <i class="fa-solid fa-hotel text-2xl"></i>
        </div>
        <h3 class="mb-3 text-xl font-bold text-brand-dark">Bulk Buyers</h3>
        <p class="text-brand-muted leading-relaxed">
          Reliable high-volume sourcing for hotels, caterers, and institutions needing uniform quality every time.
        </p>
      </div>

    </div>
  </div>
</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>