</main>

<footer class="bg-[#8b3a6b] text-white" role="contentinfo">
  <div class="mx-auto max-w-7xl px-6 pt-16 pb-10 grid gap-6 md:grid-cols-[2.2fr_1fr_1fr_1.4fr]">

    <!-- Brand -->
    <div class="space-y-4 md:px-6 md:border-r md:border-white/15">
      <h4 class="text-2xl md:text-[26px] font-extrabold tracking-wide">
        IndusAgrii
      </h4>

      <p class="text-base md:text-[16px] font-semibold opacity-95">
        Export · Import · Trust · True Value
      </p>

      <p class="text-base md:text-[16px] leading-relaxed opacity-90 max-w-md">
        Supplying premium rice and millets across India with consistency,
        transparency, and long-term partnerships.
      </p>
</div>


    <!-- Company -->
    <div class="space-y-3 md:px-6 md:border-r md:border-white/15">
      <h4 class="text-lg md:text-[18px] font-bold">Company</h4>
      <ul class="space-y-2 text-base md:text-[16px]">
        <li><a href="/IndusAgrii/public/about.php" class="underline-offset-4 hover:underline transition">About Us</a></li>
        <li><a href="/IndusAgrii/public/products.php" class="underline-offset-4 hover:underline transition">Products</a></li>
        <li><a href="/IndusAgrii/public/contact.php" class="underline-offset-4 hover:underline transition">Contact</a></li>
      </ul>
    </div>

    <!-- Business -->
    <div class="space-y-3 md:px-6 md:border-r md:border-white/15">
      <h4 class="text-lg md:text-[18px] font-bold">Business</h4>
      <ul class="space-y-2 text-base md:text-[16px]">
        <li class="hover:underline underline-offset-4 transition">Bulk Orders</li>
        <li class="hover:underline underline-offset-4 transition">Retail Partnerships</li>
        <li class="hover:underline underline-offset-4 transition">Supply Enquiry</li>
      </ul>
    </div>

    <!-- Contact -->
    <div class="space-y-3 md:px-6">
      <h4 class="text-lg md:text-[18px] font-bold">Contact</h4>

      <div class="space-y-3 text-base md:text-[16px]">
        <p class="flex gap-3 items-start">
          <i class="fa-solid fa-location-dot mt-1"></i>
          <span class="leading-relaxed">
            7th Floor, C/o Workhub, <br>Baner Business Bay<br>
            Pune, Maharashtra 411045
          </span>
        </p>

        <p class="flex gap-3 items-center">
          <i class="fa-solid fa-phone"></i> <a href="tel:+91988115540" 
              class="hover:underline underline-offset-4">
              +91 98811 5540
            </a>
        </p>

        <p class="flex gap-3 items-center">
          <i class="fa-solid fa-envelope"></i><a href="mailto:contact@triunetrades.com"
            class="hover:underline underline-offset-4">
            contact@triunetrades.com
          </a>
        </p>
      </div>
    </div>

  </div>

<!-- FOOTER BOTTOM -->
<div class="flex flex-col items-center pt-6 pb-6">

  <!-- SOCIAL LINKS -->
  <div class="flex items-center gap-6 mb-5">
    <?php foreach ([
      ["url"=>"https://www.instagram.com/triunetrades","icon"=>"instagram"],
      ["url"=>"https://www.linkedin.com/company/triune-trades/","icon"=>"linkedin-in"],
      ["url"=>"https://www.facebook.com/share/1WrJuRwfPQ/","icon"=>"facebook-f"],
      ["url"=>"https://x.com/triunetrades","icon"=>"x-twitter"]
    ] as $s): ?>
      <a href="<?= $s['url'] ?>"
         aria-label="<?= ucfirst($s['icon']) ?> profile"
         class="text-white/80 hover:text-white transition">
        <i class="fa-brands fa-<?= $s['icon'] ?> text-lg" aria-hidden="true"></i>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- DIVIDER -->
  <div class="w-full flex justify-center">
    <div class="h-px w-full max-w-4xl bg-white/20"></div>
  </div>

  <!-- COPYRIGHT -->
  <div class="mt-2 text-center text-sm md:text-[15px] opacity-95">
    © <?= date("Y") ?> IndusAgrii · Made in India 🇮🇳
  </div>

</div>
</footer>



<!-- NOTIFY MODAL -->
<div
  id="notifyModal"
  class="fixed inset-0 z-[999]
         bg-black/50
         hidden
         flex items-center justify-center
         px-4">

  <div
    class="bg-white
           w-full max-w-md
           rounded-2xl
           p-6
           shadow-xl
           scale-95 opacity-0
           transition-all duration-200"
    id="notifyPanel">

    <h3 class="text-lg font-bold mb-1">
      Notify Me When Available
    </h3>

    <p
      id="notifyProductName"
      class="text-sm text-gray-600 mb-4">
      Product:
    </p>

    <input
      id="notifyPhone"
      type="tel"
      placeholder="Your phone number"
      class="w-full mb-3
             border rounded-xl
             px-4 py-2
             text-sm
             focus:ring-2 focus:ring-emerald-500/40
             outline-none" />

    <textarea
      id="notifyMessage"
      placeholder="Optional message"
      rows="3"
      class="w-full mb-4
             border rounded-xl
             px-4 py-2
             text-sm
             focus:ring-2 focus:ring-emerald-500/40
             outline-none"></textarea>

    <div class="flex gap-3 justify-end">

      <button
        type="button"
        onclick="document.getElementById('notifyModal').classList.add('hidden')"
        class="px-4 py-2
               rounded-xl
               text-sm
               bg-gray-100
               hover:bg-gray-200">
        Cancel
      </button>

      <!-- 🔥 THIS IS WHAT YOUR JS NEEDS -->
      <button
        id="sendNotify"
        type="button"
        class="px-5 py-2
               rounded-xl
               text-sm font-semibold
               bg-emerald-600
               text-white
               hover:bg-emerald-700
               active:scale-95
               transition">
        Notify Me
      </button>

    </div>
  </div>
</div>



<!-- ================= SEO SCHEMA (NO UI IMPACT) ================= -->

<!-- Local Business -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "IndusAgrii",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "7th Floor, C/o Workhub, Baner Business Bay",
    "addressLocality": "Pune",
    "addressRegion": "MH",
    "postalCode": "411045",
    "addressCountry": "IN"
  },
  "telephone": "+91-98811-5540",
  "email": "contact@triunetrades.com"
}
</script>

<!-- Product Category Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "Indus Agrii Products",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Premium Rice",
      "url": "https://<?= $_SERVER['HTTP_HOST'] ?>/IndusAgrii/public/rice.php"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Traditional Millets",
      "url": "https://<?= $_SERVER['HTTP_HOST'] ?>/IndusAgrii/public/millets.php"
    }
  ]
}
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://<?= $_SERVER['HTTP_HOST'] ?>/IndusAgrii/public/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Products",
      "item": "https://<?= $_SERVER['HTTP_HOST'] ?>/IndusAgrii/public/products.php"
    }
  ]
}
</script>

<script src="/IndusAgrii/assets/js/main.js" defer></script>

</body>
</html>
