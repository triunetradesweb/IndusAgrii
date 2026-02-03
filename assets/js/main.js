let currentPage =
  document.body.classList.contains("rice-page") ||
  document.body.classList.contains("millets-page")
    ? 2
    : 1;

  let isLoading = false;
  let isResetting = false;

/* =========================================================
   HEADER STATE (DESKTOP + MOBILE)
========================================================= */

const header = document.getElementById("siteHeader");
const mobileToggle = document.getElementById("mobileToggle");
const search = document.getElementById("searchInput");
const filter = document.getElementById("filterCategory");
const sort = document.getElementById("sortProducts");
const grid = document.getElementById("productsGrid");

const isStaticHeaderPage =
  document.body.classList.contains("products-page") || 
  document.body.classList.contains("cart-page") ||
  document.body.classList.contains("product-details-page") ||
  document.body.classList.contains("wishlist-page") ||
  document.body.classList.contains("profile-page") ||
  document.body.classList.contains("orders-page");

  let searchTimer = null;

function updateHeaderState() {
  if (!header) return;

  // 🔒 LOCKED HEADER (PRODUCTS, CART)
  if (isStaticHeaderPage) {
    header.classList.add("bg-white", "shadow-lg");

    header.querySelectorAll(".nav-link").forEach(link => {
      link.classList.remove("text-white");
      link.classList.add("text-brand-dark");
    });

    document.querySelectorAll(".nav-cart, .header-icon").forEach(el => {
      el.classList.remove("text-white");
      el.classList.add("text-brand-dark");
    });

    mobileToggle?.classList.remove("text-white");
    mobileToggle?.classList.add("text-brand-dark");
    return;
  }

  // 🌄 HERO / HOME SCROLL HEADER
  const scrolled = window.scrollY > 20;

  header.classList.toggle("bg-white", scrolled);
  header.classList.toggle("shadow-lg", scrolled);

  header.querySelectorAll(".nav-link").forEach(link => {
    link.classList.toggle("text-white", !scrolled);
    link.classList.toggle("text-brand-dark", scrolled);
  });

  document.querySelectorAll(".nav-cart, .header-icon").forEach(el => {
    el.classList.toggle("text-white", !scrolled);
    el.classList.toggle("text-brand-dark", scrolled);
  });

  mobileToggle?.classList.toggle("text-white", !scrolled);
  mobileToggle?.classList.toggle("text-brand-dark", scrolled);
}

/* INIT */
/* INIT */
updateHeaderState();
window.addEventListener("scroll", updateHeaderState);

/* FORCE HEADER STATE ON STATIC PAGES */
document.addEventListener("DOMContentLoaded", () => {
  if (isStaticHeaderPage) {
    window.scrollTo(0, 1);
    updateHeaderState();
  }
});


/* =========================================================
   ACTIVE NAV LINK
========================================================= */
document.querySelectorAll(".nav-link").forEach(link => {
  if (link.href === window.location.href) {
    link.classList.add("after:scale-x-100", "text-brand-primary");
  }
});

/* =========================================================
   MOBILE MENU
========================================================= */

const menu = document.getElementById("mobileMenu");
const backdrop = document.getElementById("mobileBackdrop");
const closeBtn = document.getElementById("mobileClose");

function openMenu() {
  if (!menu || !backdrop) return;

  menu.classList.remove("-translate-x-full", "opacity-0", "pointer-events-none");
  menu.classList.add("translate-x-0", "opacity-100", "pointer-events-auto");

  backdrop.classList.remove("opacity-0", "pointer-events-none");
  backdrop.classList.add("opacity-100", "pointer-events-auto");
}

function closeMenu() {
  if (!menu || !backdrop) return;

  menu.classList.remove("translate-x-0", "opacity-100", "pointer-events-auto");
  menu.classList.add("-translate-x-full", "opacity-0", "pointer-events-none");

  backdrop.classList.remove("opacity-100", "pointer-events-auto");
  backdrop.classList.add("opacity-0", "pointer-events-none");
}

mobileToggle?.addEventListener("click", openMenu);
closeBtn?.addEventListener("click", closeMenu);
backdrop?.addEventListener("click", closeMenu);

menu?.querySelectorAll("a").forEach(link => {
  link.addEventListener("click", closeMenu);
});

// ================= ACTIVE FILTER CHIPS =================
const chipsWrap = document.getElementById("activeFilters");

function renderChips() {
  if (!chipsWrap) return;

  chipsWrap.innerHTML = "";
  let hasChips = false;

  if (filter.value !== "all") {
    hasChips = true;
    chipsWrap.innerHTML += chipTemplate(filter.value, "filter");
  }

  if (search.value.trim()) {
    hasChips = true;
    chipsWrap.innerHTML += chipTemplate(search.value, "search");
  }

  chipsWrap.classList.toggle("hidden", !hasChips);
}

function chipTemplate(label, type) {
  return `
    <button
      data-type="${type}"
      class="flex items-center gap-1
             px-3 py-1.5 rounded-full
             bg-emerald-50 text-emerald-800
             text-sm font-medium
             hover:bg-emerald-100
             transition">
      ${label}
      <span class="text-lg leading-none">&times;</span>
    </button>
  `;
}

chipsWrap?.addEventListener("click", e => {
  const chip = e.target.closest("button");
  if (!chip) return;

  if (chip.dataset.type === "filter") {
    filter.value = "all";
  }

  if (chip.dataset.type === "search") {
    search.value = "";
  }

  fetchProducts(true); // 🔥 REQUIRED
  renderChips();
});


filter?.addEventListener("change", () => {
  fetchProducts(true);
  renderChips();
});

sort?.addEventListener("change", () => {
  fetchProducts(true);
  renderChips();
});

search?.addEventListener("input", () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    fetchProducts(true);
    renderChips();
  }, 300);
});


// Smooth reveal (performance safe)
const prefersReducedMotion = window.matchMedia(
  "(prefers-reduced-motion: reduce)"
).matches;

let observer = null;

if (!prefersReducedMotion) {
  observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.remove("opacity-0", "translate-y-4");
        entry.target.classList.add("opacity-100", "translate-y-0");
        observer.unobserve(entry.target);
      });
    },
    { threshold: 0.15 }
  );

  document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
}
 else {
  document.querySelectorAll(".reveal").forEach(el => {
    el.classList.remove("opacity-0", "translate-y-4");
  });
}


/* =========================================================
   HERO SLIDER – CLEAN, SMOOTH, AUTO, TEXT + MOBILE CTA
========================================================= */
document.addEventListener("DOMContentLoaded", () => {

  const track  = document.getElementById("heroSlides");
  const slides = document.querySelectorAll(".hero-slide");

  if (!track || slides.length < 2) return;

  const badge   = document.querySelector(".hero-badge");
  const title   = document.querySelector(".hero-title");
  const desc    = document.querySelector(".hero-desc");
  const actions = document.querySelector(".hero-actions");

  const INTERVAL = 5000; // 5 sec
  let index = 0;
  const REAL_SLIDES = slides.length - 1; // last is clone

  /* ---------- Reset hero text ---------- */
  function resetHeroText() {
    [badge, title, desc, actions].forEach(el => {
      el.classList.remove("opacity-100", "translate-y-0");
      el.classList.add("opacity-0", "translate-y-4");
      el.style.transition = "none";
    });
  }

  /* ---------- Animate hero text ---------- */
  function animateHeroText() {
    [badge, title, desc, actions].forEach((el, i) => {
      el.style.transition =
        "opacity 700ms cubic-bezier(0.22,1,0.36,1), transform 700ms cubic-bezier(0.22,1,0.36,1)";
      el.style.transitionDelay = `${i * 140}ms`;
      el.classList.remove("opacity-0", "translate-y-4");
      el.classList.add("opacity-100", "translate-y-0");
    });
  }

  /* ---------- Mobile CTA micro animation ---------- */
  function mobileCTAPulse() {
    if (window.innerWidth >= 640) return;

    const cta = document.querySelector(".hero-actions a");
    if (!cta) return;

    setTimeout(() => {
      cta.style.transition =
        "transform 600ms cubic-bezier(0.22,1,0.36,1), box-shadow 600ms";
      cta.style.transform = "scale(1.05)";
      cta.style.boxShadow = "0 10px 30px rgba(0,0,0,0.25)";

      setTimeout(() => {
        cta.style.transform = "scale(1)";
        cta.style.boxShadow = "none";
      }, 600);
    }, 500);
  }

  /* ---------- Slide movement (NO REVERSE) ---------- */
  function nextSlide() {
    resetHeroText();
    index++;
    track.style.transform = `translateX(-${index * 100}%)`;

    // seamless loop
    if (index === REAL_SLIDES) {
      setTimeout(() => {
        track.style.transition = "none";
        index = 0;
        track.style.transform = "translateX(0)";
        track.offsetHeight; // force reflow
        track.style.transition = "transform 1000ms ease-in-out";
      }, 1000);
    }

    setTimeout(animateHeroText, 300);
    mobileCTAPulse();
  }

  /* ---------- Init ---------- */
  animateHeroText();
  mobileCTAPulse();
  setInterval(nextSlide, INTERVAL);

});


/* ================= COOKIE SYSTEM (CLEAN & HARDENED) ================= */

const cookieBox = document.getElementById("cookieConsent");
const acceptBtn = document.getElementById("acceptCookies");
const declineBtn = document.getElementById("declineCookies");
const settingsBtn = document.getElementById("openCookieSettings");

const settingsModal = document.getElementById("cookieSettingsModal");
const settingsPanel = document.getElementById("cookieSettingsPanel");
const saveSettingsBtn = document.getElementById("saveCookieSettings");
const closeSettingsBtn = document.getElementById("closeCookieSettings");

const analyticsToggle = document.getElementById("analyticsToggle");
const marketingToggle = document.getElementById("marketingToggle");

const COOKIE_KEY = "indusagrii_cookie_prefs";

/* ---------- Show / Hide Banner ---------- */
function showCookie() {
  if (!cookieBox) return;
  cookieBox.classList.remove("pointer-events-none");
  requestAnimationFrame(() => {
    cookieBox.classList.remove("opacity-0", "translate-y-6");
    cookieBox.classList.add("opacity-100", "translate-y-0");
  });
}

function hideCookie() {
  if (!cookieBox) return;
  cookieBox.classList.remove("opacity-100", "translate-y-0");
  cookieBox.classList.add("opacity-0", "translate-y-6");
  setTimeout(() => {
    if (cookieBox) cookieBox.classList.add("pointer-events-none");
  }, 300);
}

/* ---------- Settings Modal ---------- */
function openSettings() {
  if (!settingsModal || !settingsPanel) return;
  settingsModal.classList.remove("hidden");
  requestAnimationFrame(() => {
    settingsPanel.classList.remove("opacity-0", "scale-95");
    settingsPanel.classList.add("opacity-100", "scale-100");
  });
}

function closeSettings() {
  if (!settingsModal || !settingsPanel) return;
  settingsPanel.classList.remove("opacity-100", "scale-100");
  settingsPanel.classList.add("opacity-0", "scale-95");
  setTimeout(() => {
    if (settingsModal) settingsModal.classList.add("hidden");
  }, 300);
}

/* ---------- Save Preferences ---------- */
function savePreferences() {
  const prefs = {
    analytics: analyticsToggle?.checked ?? false,
    marketing: marketingToggle?.checked ?? false
  };

  localStorage.setItem(COOKIE_KEY, JSON.stringify(prefs));
  hideCookie();
  closeSettings();
}

/* ---------- Initial Load ---------- */
(function initCookies() {
  if (!cookieBox) return;

  const saved = localStorage.getItem(COOKIE_KEY);

  if (!saved) {
    showCookie();
    return;
  }

  try {
    const prefs = JSON.parse(saved);
    if (analyticsToggle) analyticsToggle.checked = !!prefs.analytics;
    if (marketingToggle) marketingToggle.checked = !!prefs.marketing;
  } catch (e) {
    // corrupted storage → reset safely
    localStorage.removeItem(COOKIE_KEY);
    showCookie();
  }
})();

/* ---------- Events ---------- */
acceptBtn?.addEventListener("click", () => {
  localStorage.setItem(
    COOKIE_KEY,
    JSON.stringify({ analytics: true, marketing: false })
  );
  hideCookie();
});

declineBtn?.addEventListener("click", () => {
  localStorage.setItem(
    COOKIE_KEY,
    JSON.stringify({ analytics: false, marketing: false })
  );
  hideCookie();
});

settingsBtn?.addEventListener("click", openSettings);
closeSettingsBtn?.addEventListener("click", closeSettings);
saveSettingsBtn?.addEventListener("click", savePreferences);


// Search Logic
const openSearch = document.getElementById("openSearch");
const searchBox = document.getElementById("searchBox");

openSearch?.addEventListener("click", () => {
  searchBox.classList.toggle("hidden");
  searchBox.querySelector("input")?.focus();
});


/* ================= PRODUCTS AJAX FILTER (FINAL & FIXED) ================= */

let hasMore = true;

function fetchProducts(reset = false) {
  if (!grid || isLoading || (!hasMore && !reset)) return;

  if (reset) {
    isResetting = true;
    currentPage =
  document.body.classList.contains("rice-page") ||
  document.body.classList.contains("millets-page")
    ? 2
    : 1;

    hasMore = true;
    grid.innerHTML = "";
  }

  isLoading = true;
  grid.classList.add("opacity-50");


  const data = new FormData();
  data.append("ajax", "1");
  data.append("category", filter?.value || "all"); // variety on rice page
  data.append("sort", sort?.value || "new");
  data.append("search", search?.value || "");
  data.append("page", currentPage);

const endpoint = getProductsEndpoint();

fetch(endpoint, {
  method: "POST",
  body: data
})

    .then(res => res.json())
    .then(res => {
      if (!res || !res.html) return;

      if (reset) {
        grid.innerHTML = res.html;
      } else {
        grid.insertAdjacentHTML("beforeend", res.html);
      }

      document.querySelectorAll(".product-card").forEach(card => {
        initProductPricing(card);
        observer?.observe(card);
      });

      hasMore = !!res.hasMore;
      currentPage++;
    })
    .catch(err => {
      console.error("AJAX product load failed:", err);
    })
    .finally(() => {
      isLoading = false;
      isResetting = false;
      grid.classList.remove("opacity-50", "pointer-events-none");
    });
}


const sentinel = document.getElementById("scrollSentinel");

let skipFirstIntersect = document.body.classList.contains("rice-page");

if (sentinel) {
  const infiniteObserver = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting || isResetting) return;

    if (skipFirstIntersect) {
      skipFirstIntersect = false;
      return;
    }

    fetchProducts();
  }, {
    rootMargin: "200px"
  });

  infiniteObserver.observe(sentinel);
}


/* ================= SEARCH TYPEWRITER EFFECT (WORKING & SAFE) ================= */

(function () {
  if (!search) return;

  const placeholder = document.getElementById("searchPlaceholder");
  if (!placeholder) return;

  const words = [
    "Search rice...",
    "Search millets...",
    "Search organic grains...",
    "Search premium basmati..."
  ];

  let wordIndex = 0;
  let charIndex = 0;
  let deleting = false;
  let pause = false;

  function type() {
    // stop ONLY when user types
    if (search.value.length > 0) return;

    const word = words[wordIndex];

    if (!deleting) {
      placeholder.textContent = word.slice(0, charIndex + 1);
      charIndex++;

      if (charIndex === word.length) {
        pause = true;
        setTimeout(() => {
          deleting = true;
          pause = false;
        }, 1200);
      }
    } else {
      placeholder.textContent = word.slice(0, charIndex - 1);
      charIndex--;

      if (charIndex === 0) {
        deleting = false;
        wordIndex = (wordIndex + 1) % words.length;
      }
    }
  }

  setInterval(() => {
    if (!pause) type();
  }, 120);

  // Hide placeholder when user interacts
  search.addEventListener("focus", () => {
    placeholder.classList.add("opacity-0");
  });

  search.addEventListener("blur", () => {
    if (!search.value) {
      placeholder.classList.remove("opacity-0");
    }
  });

  search.addEventListener("input", () => {
    placeholder.classList.add("opacity-0");
  });
})();


document.addEventListener("click", async (e) => {
  const btn = e.target.closest(".wishlist-btn");
  if (!btn) return;

  e.preventDefault();
  e.stopPropagation();

  const id = btn.dataset.id;

  const res = await fetch("/IndusAgrii/public/wishlist.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      toggle_wishlist: 1,
      id
    })
  });

  const data = await res.json();
  if (!data.success) return;

  /* ================= ICON TOGGLE ================= */
  const icon = btn.querySelector("i");

  if (data.state === "added") {
    icon.classList.remove("fa-regular", "text-gray-700");
    icon.classList.add("fa-solid", "text-emerald-600");
  } else {
    icon.classList.remove("fa-solid", "text-emerald-600");
    icon.classList.add("fa-regular", "text-gray-700");
  }

  /* ================= BADGE UPDATE ================= */
  const desktop = document.getElementById("wishlistCount");
  const mobile  = document.getElementById("wishlistCountMobile");

  [desktop, mobile].forEach(badge => {
    if (!badge) return;

    if (data.count > 0) {
      badge.textContent = data.count;
      badge.classList.remove("hidden");
    } else {
      badge.classList.add("hidden");
    }
  });

  /* ================= REMOVE CARD ON WISHLIST PAGE ================= */
  if (
    document.body.classList.contains("wishlist-page") &&
    data.state === "removed"
  ) {
    const card = btn.closest(".product-card");

    if (card) {
      card.classList.add("opacity-0", "scale-95");

      setTimeout(() => {
        card.remove();

        const grid = document.querySelector(".grid");
        if (grid && grid.children.length === 0) {
          grid.innerHTML = `
            <p class="col-span-full text-center text-gray-500">
              Your wishlist is empty.
            </p>
          `;
        }
      }, 200);
    }
  }
});


/* ================= SAFE PACK + PRICE EXTENSION (FINAL) ================= */
function initProductPricing(card) {
  const packSelect = card.querySelector(".pack-size");
  const qtyInput   = card.querySelector(".qty-input");
  const minusBtn   = card.querySelector(".qty-minus");
  const plusBtn    = card.querySelector(".qty-plus");

  const priceEl   = card.querySelector(".product-price");
  const basePrice = parseFloat(priceEl.dataset.basePrice);

  function update() {
    const pack = parseInt(packSelect.value, 10);
    const qty  = parseInt(qtyInput.value, 10);

    const total = basePrice * pack * qty;

    priceEl.textContent = `₹${total.toFixed(2)}`;

    // minus button safety
    minusBtn.disabled = qty <= 1;
    minusBtn.classList.toggle("opacity-40", qty <= 1);
    minusBtn.classList.toggle("cursor-not-allowed", qty <= 1);
  }

  packSelect.addEventListener("change", update);

  plusBtn.addEventListener("click", () => {
    qtyInput.value = parseInt(qtyInput.value, 10) + 1;
    update();
  });

  minusBtn.addEventListener("click", () => {
    if (qtyInput.value > 1) {
      qtyInput.value = parseInt(qtyInput.value, 10) - 1;
      update();
    }
  });

  qtyInput.addEventListener("change", () => {
    if (qtyInput.value < 1) qtyInput.value = 1;
    update();
  });

  update(); // initial price render
}

/* INIT */
document.querySelectorAll(".product-card").forEach(initProductPricing);


/* ================= CART UI + LOGIC ================= */

// INIT CART COUNT ON LOAD
document.addEventListener("DOMContentLoaded", () => {
  const badge = document.getElementById("cartCount");
  if (!badge) return;

  if (window.INIT_CART_COUNT > 0) {
    badge.textContent = window.INIT_CART_COUNT;
    badge.classList.remove("hidden");
  }
});

// INIT WISHLIST COUNT ON LOAD
document.addEventListener("DOMContentLoaded", () => {
  const desktop = document.getElementById("wishlistCount");
  const mobile  = document.getElementById("wishlistCountMobile");

  if (window.INIT_WISHLIST_COUNT > 0) {
    if (desktop) {
      desktop.textContent = window.INIT_WISHLIST_COUNT;
      desktop.classList.remove("hidden");
    }
    if (mobile) {
      mobile.textContent = window.INIT_WISHLIST_COUNT;
      mobile.classList.remove("hidden");
    }
  }
});


/* =========================================================
   CART SYSTEM – PRODUCTS PAGE (FINAL & ISOLATED)
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

/* ---------- ADD TO CART ---------- */
document.body.addEventListener("click", e => {
  const btn = e.target.closest(".add-to-cart");
  if (!btn) return;

  e.preventDefault();

  const card = btn.closest(".product-card");
  if (!card) return;

  // HARD BLOCK: out of stock ✅ REQUIRED
  if (card.classList.contains("pointer-events-none")) {
outOfStockToast();
    return;
  }

  const id   = btn.dataset.id;
  const pack = parseInt(card.querySelector(".pack-size")?.value || 0);
  const qty  = parseInt(card.querySelector(".qty-input")?.value || 1);

  const priceText = card.querySelector(".product-price")?.textContent || "0";
  const price = parseFloat(priceText.replace(/[₹,]/g, ""));

const endpoint = getProductsEndpoint();
      fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        add_to_cart: "1",
        id,
        pack,
        qty,
        price
      })
    })
    .then(res => res.json())
    .then(res => {
      if (!res.success) {
        showToast(
  "Action Couldn’t Be Completed",
  "Please try again in a moment.",
  "warning"
);
        return;
      }

      // Visual feedback
      btn.innerHTML = "Added ✓";
      btn.classList.add("opacity-70");

      setTimeout(() => {
        btn.innerHTML = `<i class="fa-solid fa-bag-shopping"></i> Add`;
        btn.classList.remove("opacity-70");
      }, 1000);

// Cart badge
const badge = document.getElementById("cartCount");
if (badge) {
  badge.textContent = res.count;
  badge.classList.remove("hidden");
}

pulseCartIcon();

showToast(
  "Added to Cart",
  "The product has been added to your cart.",
  "success"
);
    });
  });


  /* ---------- BUY NOW ---------- */
  document.body.addEventListener("click", e => {
    const btn = e.target.closest(".buy-now");
    if (!btn) return;

    e.preventDefault();

    const card = btn.closest(".product-card");
    if (!card) return;

    // HARD BLOCK: out of stock
    if (card.classList.contains("pointer-events-none")) {
outOfStockToast();
      return;
    }

    const id   = card.dataset.id;
    const pack = card.querySelector(".pack-size")?.value;
    const qty  = card.querySelector(".qty-input")?.value;

    const priceText = card.querySelector(".product-price")?.textContent || "0";
    const price = parseFloat(priceText.replace("₹", ""));

 const endpoint = getProductsEndpoint();
      fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        add_to_cart: "1",
        id,
        pack,
        qty,
        price
      })
    })
    .then(() => {
      window.location.href = "/IndusAgrii/public/cart.php";
    });
  });

});


// TOAST UI
function showToast(title, message, type = "info") {
  let container = document.getElementById("toastContainer");

  if (!container) {
    container = document.createElement("div");
    container.id = "toastContainer";
    container.className =
      "fixed bottom-6 right-6 z-[999] flex flex-col gap-3";
    document.body.appendChild(container);
  }

  const tone = {
    info: {
      bg: "bg-white",
      iconBg: "bg-emerald-50",
      icon: "fa-circle-info text-emerald-700"
    },
    success: {
      bg: "bg-white",
      iconBg: "bg-emerald-50",
      icon: "fa-check text-emerald-700"
    },
    warning: {
      bg: "bg-white",
      iconBg: "bg-amber-50",
      icon: "fa-triangle-exclamation text-amber-700"
    }
  }[type];

  const toast = document.createElement("div");
  toast.className = `
    ${tone.bg}
    border border-gray-200
    shadow-[0_20px_40px_rgba(0,0,0,0.12)]
    rounded-2xl
    px-5 py-4
    flex gap-4 items-start
    opacity-0 translate-y-3
    transition-all duration-300
    max-w-sm
  `;

  toast.innerHTML = `
    <div class="w-10 h-10 rounded-full ${tone.iconBg} flex items-center justify-center shrink-0">
      <i class="fa-solid ${tone.icon}"></i>
    </div>
    <div class="space-y-0.5">
      <p class="text-sm font-semibold text-gray-900">
        ${title}
      </p>
      <p class="text-sm text-gray-600 leading-relaxed">
        ${message}
      </p>
    </div>
  `;

  container.appendChild(toast);

  requestAnimationFrame(() => {
    toast.classList.remove("opacity-0", "translate-y-3");
  });

  setTimeout(() => {
    toast.classList.add("opacity-0", "translate-y-3");
    setTimeout(() => toast.remove(), 300);
  }, 2800);
}

/* ================= SHARED HELPERS ================= */

function outOfStockToast() {
  showToast(
    "Currently Unavailable",
    "This product is temporarily out of stock.",
    "warning"
  );
}

function getProductsEndpoint() {
  if (document.body.classList.contains("rice-page")) {
    return "/IndusAgrii/public/rice.php";
  }
  if (document.body.classList.contains("millets-page")) {
    return "/IndusAgrii/public/millets.php";
  }
  return "/IndusAgrii/public/products.php";
}

function pulseCartIcon() {
  const icon = document.querySelector(".nav-cart");
  if (!icon) return;

  icon.classList.add("scale-110");
  setTimeout(() => icon.classList.remove("scale-110"), 180);
}


/* ================= AUTH MODAL ================= */

const authModal = document.getElementById("authModal");
const authPanel = document.getElementById("authPanel");
const openAuth = document.getElementById("openAuthModal");
const closeAuth = document.getElementById("closeAuthModal");

const loginForm = document.getElementById("loginForm");
const signupForm = document.getElementById("signupForm");
const showSignup = document.getElementById("showSignup");
const backToLogin = document.getElementById("backToLogin");
const authTitle = document.getElementById("authTitle");
const openAuthMobile = document.getElementById("openAuthModalMobile");

openAuthMobile?.addEventListener("click", () => {
  closeMenu();  
  setTimeout(() => {
    openAuthModal();
  }, 200);
});

function openAuthModal() {
  authModal.classList.remove("hidden");

  //  Lock background scroll (mobile + desktop)
  document.body.classList.add("overflow-hidden");

  requestAnimationFrame(() => {
    authPanel.classList.remove("opacity-0", "scale-95");
    authPanel.classList.add("opacity-100", "scale-100");
  });
}

function closeAuthModal() {
  authPanel.classList.remove("opacity-100", "scale-100");
  authPanel.classList.add("opacity-0", "scale-95");

  //  Restore scroll
  document.body.classList.remove("overflow-hidden");

  setTimeout(() => authModal.classList.add("hidden"), 300);
}

openAuth?.addEventListener("click", openAuthModal);
closeAuth?.addEventListener("click", closeAuthModal);

authModal?.addEventListener("click", e => {
  if (e.target === authModal) closeAuthModal();
});


/* Toggle Login / Signup */
showSignup?.addEventListener("click", () => {
  loginForm.classList.add("hidden");
  signupForm.classList.remove("hidden");
  authTitle.textContent = "Create your account";
});

backToLogin?.addEventListener("click", () => {
  signupForm.classList.add("hidden");
  loginForm.classList.remove("hidden");
  authTitle.textContent = "Login to Indus Agrii";
});

/* ================= SIMPLE AUTH (NO OTP) ================= */
loginForm?.addEventListener("submit", e => {
  e.preventDefault();

  fetch(window.location.href, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      auth: "login",
      email: loginEmail.value.trim(),
      password: loginPassword.value.trim()
    })
  })
.then(r => r.json())
.then(res => {
  if (res.error) {
    showToast(
      "Unable to Sign In",
      res.error || "Please check your credentials and try again.",
      "warning"
    );
    return;
  }

  sessionStorage.setItem("loginSuccess", "1");

  showToast(
    "Welcome Back",
    "You’ve successfully signed in to your account.",
    "success"
  );

  setTimeout(() => location.reload(), 700);
})

  .catch(() => {
    showToast(
      "Connection Issue",
      "Please check your internet connection and try again.",
      "warning"
    );
  });
});


signupForm?.addEventListener("submit", e => {
  e.preventDefault();

  fetch(window.location.href, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      auth: "signup",
      name: signupName.value.trim(),
      email: signupEmail.value.trim(),
      password: signupPassword.value.trim()
    })
  })
  .then(r => r.json())
  .then(res => {
    if (res.error) {
      showToast(
        "Unable to Create Account",
        res.error || "Please review your details and try again.",
        "warning"
      );
      return;
    }

    showToast(
      "Account Created",
      "Your account has been created. You may now sign in.",
      "success"
    );

    signupForm.classList.add("hidden");
    loginForm.classList.remove("hidden");
    authTitle.textContent = "Login to Indus Agrii";
  })
  .catch(() => {
    showToast(
      "Connection Issue",
      "Please check your internet connection and try again.",
      "warning"
    );
  });
});

/* ================= LOGIN AVATAR ANIMATION ================= */

(function () {
  // Only run once after login
  if (!sessionStorage.getItem("loginSuccess")) return;

  sessionStorage.removeItem("loginSuccess");

  const avatar = document.querySelector("#userAvatarBtn span");
  if (!avatar) return;

  avatar.classList.add(
    "animate-[pulse_1.2s_ease-in-out_1]"
  );

  // Glow burst
  avatar.classList.add("ring-4", "ring-emerald-300/60");

  setTimeout(() => {
    avatar.classList.remove("ring-4", "ring-emerald-300/60");
  }, 1200);
})();



/* ================= PRODUCT DETAILS PAGE LOGIC ================= */
(function () {
  const page = document.getElementById("productDetails");
  if (!page) return;

  const basePrice = parseFloat(page.dataset.basePrice);
  const priceEl = page.querySelector(".product-price");
  const qtyInput = page.querySelector(".qty-input");

  let pack = parseInt(page.querySelector(".pack-size").value);
  let qty = 1;

  function updatePrice() {
    priceEl.textContent = "₹" + (basePrice * pack * qty).toFixed(2);
  }

  page.querySelectorAll(".pack-size").forEach(btn => {
    btn.addEventListener("click", () => {
      page.querySelectorAll(".pack-size")
        .forEach(b => b.classList.remove("bg-emerald-800","text-white"));
      btn.classList.add("bg-emerald-800","text-white");
      pack = parseInt(btn.value);
      updatePrice();
    });
  });

  page.querySelector(".qty-plus").onclick = () => {
    qty++;
    qtyInput.value = qty;
    updatePrice();
  };

  page.querySelector(".qty-minus").onclick = () => {
    if (qty > 1) {
      qty--;
      qtyInput.value = qty;
      updatePrice();
    }
  };

  qtyInput.onchange = () => {
    qty = Math.max(1, parseInt(qtyInput.value || 1));
    qtyInput.value = qty;
    updatePrice();
  };
})();


document.addEventListener("click", function (e) {
  const card = e.target.closest(".product-card");
  if (!card) return;

  // Ignore interactive elements
  if (e.target.closest("button, select, input, .wishlist-btn")) {
    return;
  }

  const isOutOfStock = card.dataset.stock === "0";

  // BLOCK OUT OF STOCK CARD CLICK
  if (isOutOfStock) {
    e.preventDefault();
    outOfStockToast();
    return;
  }

  const slug = card.getAttribute("data-slug");
  if (!slug) return;

  window.location.href =
    "/IndusAgrii/public/product-details.php?slug=" +
    encodeURIComponent(slug);
});


/* ================= PRODUCT DETAILS UI ================= */
document.addEventListener("DOMContentLoaded", () => {

  /* Rating Modal */
  const open = document.getElementById("openRatingModal");
  const modal = document.getElementById("ratingModal");
  const close = document.getElementById("closeRatingModal");

  open?.addEventListener("click", () => modal.classList.remove("hidden"));
  close?.addEventListener("click", () => modal.classList.add("hidden"));
  modal?.addEventListener("click", e => {
    if (e.target === modal) modal.classList.add("hidden");
  });

});


function scrollProducts(direction) {
  const scroller = document.getElementById('productScroller');
  const cardWidth = scroller.querySelector('.product-card').offsetWidth + 24;
  scroller.scrollBy({
    left: direction * cardWidth,
    behavior: 'smooth'
  });
}


// Testimonial
document.addEventListener("DOMContentLoaded", () => {
  const track = document.getElementById("testimonialTrack");
  const prev  = document.getElementById("testimonialPrev");
  const next  = document.getElementById("testimonialNext");

  if (!track || !prev || !next) return;

  const originalCards = Array.from(track.children);
  const COUNT = originalCards.length;
  if (!COUNT) return;

  const GAP = 24;
  let index = 0;
  let autoplay = null;
  let isAnimating = false;
  const isMobile = window.innerWidth < 640;

  function cardWidth() {
    return originalCards[0].getBoundingClientRect().width + GAP;
  }

  function move(animate = true) {
    track.style.transition = animate ? "transform 0.45s ease" : "none";
    track.style.transform =
      `translateX(-${index * cardWidth()}px)`;
  }

  /* ================= MOBILE (VISIBLE & SAFE) ================= */
  if (isMobile) {
    index = 0;
    move(false);

    next.onclick = () => {
      index = (index + 1) % COUNT;
      move();
    };

    prev.onclick = () => {
      index = (index - 1 + COUNT) % COUNT;
      move();
    };

    return; // ⛔ stop here for mobile
  }

  /* ================= DESKTOP TRUE INFINITE ================= */

  // clone cards twice
  originalCards.forEach(c => track.appendChild(c.cloneNode(true)));
  originalCards.forEach(c => track.appendChild(c.cloneNode(true)));

  const TOTAL = track.children.length;

  index = COUNT; // start from middle
  move(false);

  function normalize() {
    if (index >= TOTAL - COUNT) {
      index -= COUNT;
      move(false);
    }
    if (index < COUNT) {
      index += COUNT;
      move(false);
    }
  }

  function safeMove(dir) {
    if (isAnimating) return;
    isAnimating = true;

    index += dir;
    move();

    setTimeout(() => {
      normalize();
      isAnimating = false;
    }, 500);
  }

  next.onclick = () => safeMove(1);
  prev.onclick = () => safeMove(-1);

  autoplay = setInterval(() => safeMove(1), 4500);

  track.addEventListener("mouseenter", () => clearInterval(autoplay));
  track.addEventListener("mouseleave", () => {
    autoplay = setInterval(() => safeMove(1), 4500);
  });

  window.addEventListener("resize", () => location.reload());
});



/* ================= KITCHEN TIPS ACCORDION (STABLE & SINGLE OPEN) ================= */
document.addEventListener("DOMContentLoaded", () => {
  const items = document.querySelectorAll(".tip-item");

  items.forEach(item => {
    const toggle = item.querySelector(".tip-toggle");
    const content = item.querySelector(".tip-content");
    const arrow = item.querySelector(".arrow");

    toggle.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      const isOpen = !content.classList.contains("hidden");

      // Close all items
      items.forEach(i => {
        i.querySelector(".tip-content").classList.add("hidden");
        i.querySelector(".arrow").classList.remove("rotate-180");
      });

      // Open only the clicked one if it was closed
      if (!isOpen) {
        content.classList.remove("hidden");
        arrow.classList.add("rotate-180");
      }
    });
  });
});

/* ================= NOTIFY ME MODAL (FINAL & CLEAN) ================= */

// Cache elements once
const notifyModal = document.getElementById("notifyModal");
const notifyPanel = document.getElementById("notifyPanel");
const notifyProductName = document.getElementById("notifyProductName");
const notifyPhone = document.getElementById("notifyPhone");
const notifyMessage = document.getElementById("notifyMessage");
const sendNotifyBtn = document.getElementById("sendNotify");

// OPEN MODAL (event delegation – works for AJAX cards too)
document.addEventListener("click", e => {
  const btn = e.target.closest(".notify-btn");
  if (!btn) return;

  const productId = btn.dataset.id;
  const productName = btn.dataset.name;

  if (!productId || !productName) {
  showToast(
    "Something Went Wrong",
    "Product information is missing.",
    "warning"
  );
  return;
}

  // Set modal content
  notifyProductName.textContent = "Product: " + productName;
  notifyModal.dataset.productId = productId;

  // Reset inputs
  notifyPhone.value = "";
  notifyMessage.value = "";

  // Show modal
  notifyModal.classList.remove("hidden");

  // Animate panel
  requestAnimationFrame(() => {
    notifyPanel.classList.remove("opacity-0", "scale-95");
    notifyPanel.classList.add("opacity-100", "scale-100");
  });
});

// CLOSE MODAL (background click)
notifyModal?.addEventListener("click", e => {
  if (e.target !== notifyModal) return;
  closeNotifyModal();
});

// SEND NOTIFY REQUEST
sendNotifyBtn?.addEventListener("click", async (e) => {
  const phone = notifyPhone.value.trim();
  const message = notifyMessage.value.trim();
  const productId = notifyModal.dataset.productId;
  const productName =
    notifyProductName.textContent.replace("Product: ", "");

  if (!phone) {
    showToast(
  "Phone Number Required",
  "Please enter a valid phone number so we can notify you.",
  "warning"
);

    return;
  }

  if (!productId) {
    showToast(
  "Something Went Wrong",
  "Please try again in a moment.",
  "warning"
);
    return;
  }

  const fd = new FormData();
  fd.append("product_id", productId);
  fd.append("product_name", productName);
  fd.append("phone", phone);
  fd.append("message", message);

  try {
    const res = await fetch("/IndusAgrii/public/notify.php", {
      method: "POST",
      body: fd
    });

    const data = await res.json();

    if (data.success) {
      showToast(
  "Request Received",
  "We’ll notify you as soon as this product is back in stock.",
  "success"
);

      closeNotifyModal();
    } else {
      showToast(
  "Something Went Wrong",
  "Please try again in a moment.",
  "warning"
);

    }
  } catch (err) {
    console.error("Notify error:", err);
    showToast(
  "Something Went Wrong",
  "Please try again in a moment.",
  "warning"
);

  }

});

// CLOSE HELPER
function closeNotifyModal() {
  notifyPanel.classList.remove("opacity-100", "scale-100");
  notifyPanel.classList.add("opacity-0", "scale-95");

  setTimeout(() => {
    notifyModal.classList.add("hidden");
  }, 200);
}
