<?php
require_once __DIR__ . "/../config/database.php";

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName  = $_SESSION['user_name'] ?? '';

$userFirstName = '';
$userInitial   = '';
$avatarGradient = 'from-emerald-500 to-emerald-700';

if ($userName) {
  $parts = preg_split('/\s+/', trim($userName));
  $userFirstName = ucfirst(strtolower($parts[0]));
  $userInitial   = strtoupper(substr($parts[0], 0, 1));

  // Deterministic gradient based on name hash
  $gradients = [
    'from-emerald-500 to-emerald-700',
    'from-green-500 to-lime-600',
    'from-teal-500 to-cyan-600',
    'from-amber-500 to-orange-600',
    'from-rose-500 to-pink-600',
    'from-violet-500 to-purple-600'
  ];

  $hash = crc32($userName);
  $avatarGradient = $gradients[$hash % count($gradients)];
}


$notifyBanner = null;

if (isset($_SESSION['user_id'])) {
  $uid = (int)$_SESSION['user_id'];

  $res = $conn->query("
    SELECT product_name
    FROM notify_requests
    WHERE user_id = $uid
      AND is_notified = 1
    ORDER BY created_at DESC
    LIMIT 1
  ");

  if ($res && $res->num_rows > 0) {
    $notifyBanner = $res->fetch_assoc()['product_name'];
  }
}


/* ================= SIMPLE AUTH HANDLER ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auth'])) {
  header('Content-Type: application/json');

  /* ---------- SIGN UP ---------- */
  if ($_POST['auth'] === 'signup') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    if (!$name || !$email || !$pass) {
      echo json_encode(['error' => 'All fields required']);
      exit;
    }

    // check duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
      echo json_encode(['error' => 'Email already registered']);
      exit;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
      "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $name, $email, $hash);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
  }

  /* ---------- LOGIN ---------- */
  if ($_POST['auth'] === 'login') {
    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    $stmt = $conn->prepare(
      "SELECT id, name, password FROM users WHERE email=?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($pass, $user['password'])) {
      echo json_encode(['error' => 'Invalid email or password']);
      exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    echo json_encode(['success' => true]);
    exit;
  }
}

$pageTitle = $pageTitle ?? "Indus Agrii | Pure Farm Produce from India";
$pageDescription = $pageDescription ?? "Premium rice and millets sourced directly from Indian farms with safe, hygienic pan-India delivery.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#2f855a">

  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  <link rel="canonical" href="<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">

  <?php
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$self = strtok($_SERVER["REQUEST_URI"], '?');
?>

<link rel="canonical" href="<?= $self ?>?page=<?= $currentPage ?>" />

<?php if ($currentPage > 1): ?>
<link rel="prev" href="<?= $self ?>?page=<?= $currentPage - 1 ?>" />
<?php endif; ?>

<link rel="next" href="<?= $self ?>?page=<?= $currentPage + 1 ?>" />

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <script>
  window.INIT_CART_COUNT = <?= isset($_SESSION['cart'])
    ? array_sum(array_column($_SESSION['cart'], 'qty'))
    : 0 ?>;

      window.INIT_WISHLIST_COUNT = <?= isset($_SESSION['wishlist'])
    ? count($_SESSION['wishlist'])
    : 0 ?>;
</script>

  
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: {
            primary: '#2f855a',
            dark: '#1f2933',
            muted: '#6b7280'
          }
        },
        keyframes: {
          scroll: {
            '0%': { transform: 'translateX(0)' },
            '100%': { transform: 'translateX(-50%)' }
          }
        },
        animation: {
          scroll: 'scroll 30s linear infinite'
        }
      }
    }
  }
</script>


  <!-- Icons -->
  <link
  rel="preconnect"
  href="https://cdnjs.cloudflare.com"
  crossorigin
>

<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
  integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>

</head>

<body class="<?= $bodyClass ?? 'bg-white text-brand-dark antialiased' ?>">

<header id="siteHeader"
  class="fixed top-0 left-0 w-full z-50
         bg-white 
         transition-all duration-300">

  <!-- HEADER BAR -->
  <div class="mx-auto flex h-[104px] max-w-7xl items-center justify-between px-6">

    <!-- MOBILE: HAMBURGER (LEFT ONLY ON MOBILE) -->
    <button id="mobileToggle"
      class="text-white text-3xl md:hidden transition hover:text-brand-primary"
      aria-label="Open menu">
      <i class="fa-solid fa-bars"></i>
    </button>

    <!-- LOGO -->
    <a href="/IndusAgrii/public/"
       class="flex items-center ml-auto md:ml-0 md:mr-auto">
      <img
        src="/IndusAgrii/assets/images/logo/IndusAgri.png"
        alt="Indus Agrii – Pure Farm Produce"
        class="h-[140px] w-auto object-contain">
    </a>

    <!-- DESKTOP NAVIGATION -->
    <nav class="hidden items-center gap-10 md:flex">

      <a href="/IndusAgrii/public/"
         class="nav-link relative text-sm font-semibold uppercase tracking-wide text-white transition
                after:absolute after:-bottom-2 after:left-0 after:h-[3px] after:w-full
                after:origin-left after:scale-x-0 after:bg-brand-primary
                after:transition-transform after:duration-300
                hover:after:scale-x-100 hover:text-brand-primary">
        Home
      </a>

      <!-- SHOP BY CATEGORY -->
      <div class="relative group">

        <a href="/IndusAgrii/public/products.php"
           class="nav-link relative flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-white transition
                  after:absolute after:-bottom-2 after:left-0 after:h-[3px] after:w-full
                  after:origin-left after:scale-x-0 after:bg-brand-primary
                  after:transition-transform after:duration-300
                  hover:after:scale-x-100 hover:text-brand-primary">
          Shop by Category
          <i class="fa-solid fa-chevron-down text-xs transition group-hover:rotate-180"></i>
        </a>

        <!-- Hover buffer (prevents instant close) -->
        <div class="absolute left-0 top-full h-3 w-full"></div>

        <!-- DROPDOWN -->
        <div
          class="absolute left-1/2 top-full mt-4 w-64 -translate-x-1/2 rounded-xl bg-white py-3 shadow-xl
                 opacity-0 invisible translate-y-2
                 transition-all duration-200 ease-out
                 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">

          <a href="/IndusAgrii/public/products.php"
             class="flex items-center gap-3 px-5 py-3 font-medium hover:bg-brand-primary/10 transition">
            <i class="fa-solid fa-box-open text-brand-primary"></i>
            All Products
          </a>

          <a href="/IndusAgrii/public/rice.php"
             class="flex items-center gap-3 px-5 py-3 font-medium hover:bg-brand-primary/10 transition">
            <i class="fa-solid fa-bowl-rice text-brand-primary"></i>
            Rice
          </a>

          <a href="/IndusAgrii/public/millets.php"
             class="flex items-center gap-3 px-5 py-3 font-medium hover:bg-brand-primary/10 transition">
            <i class="fa-solid fa-seedling text-brand-primary"></i>
            Millets
          </a>
        </div>
      </div>

      <a href="/IndusAgrii/public/about.php"
         class="nav-link relative text-sm font-semibold uppercase tracking-wide text-white transition
                after:absolute after:-bottom-2 after:left-0 after:h-[3px] after:w-full
                after:origin-left after:scale-x-0 after:bg-brand-primary
                after:transition-transform after:duration-300
                hover:after:scale-x-100 hover:text-brand-primary">
        About
      </a>

      <a href="/IndusAgrii/public/contact.php"
         class="nav-link relative text-sm font-semibold uppercase tracking-wide text-white transition
                after:absolute after:-bottom-2 after:left-0 after:h-[3px] after:w-full
                after:origin-left after:scale-x-0 after:bg-brand-primary
                after:transition-transform after:duration-300
                hover:after:scale-x-100 hover:text-brand-primary">
        Contact
      </a>


<div class="flex items-center gap-6 header-icons">

<?php if ($isLoggedIn): ?>
<div class="relative group">
  <button
  class="group flex items-center gap-2 text-white
         transition-transform duration-300"
  id="userAvatarBtn">

  <span
    class="relative flex h-9 w-9 items-center justify-center
           rounded-full bg-gradient-to-br <?= $avatarGradient ?>
           text-sm font-bold text-white
           shadow-lg">

    <?= htmlspecialchars($userInitial) ?>

    <span
      class="absolute inset-0 rounded-full
             ring-2 ring-white/20"></span>
  </span>

  <span class="header-icon hidden lg:block text-sm font-semibold tracking-wide">
    <?= htmlspecialchars($userFirstName) ?>
  </span>

</button>

  <div class="absolute right-0 mt-3 w-44 rounded-xl bg-white shadow-xl
              opacity-0 invisible group-hover:opacity-100 group-hover:visible
              transition">
    <a href="/IndusAgrii/public/profile.php"
       class="block px-4 py-2 hover:bg-gray-100">Profile</a>
    <a href="/IndusAgrii/public/orders.php"
       class="block px-4 py-2 hover:bg-gray-100">Orders</a>
    <a href="/IndusAgrii/public/logout.php"
       class="block px-4 py-2 text-red-600 hover:bg-red-50">Logout</a>
  </div>
</div>
<?php else: ?>
<button
  type="button"
  id="openAuthModal"
  class="header-icon
         relative z-[60]
         text-white
         hover:text-brand-primary
         pointer-events-auto">
  <i class="fa-regular fa-user text-lg"></i>
</button>

<?php endif; ?>


  <!-- WISHLIST -->
  <a href="/IndusAgrii/public/wishlist.php"
   class="header-icon relative text-white hover:text-brand-primary transition"
     aria-label="Wishlist">
    <i class="fa-regular fa-heart text-lg"></i>
    <span
      id="wishlistCount"
      class="absolute -right-3 -top-2
             rounded-full bg-brand-primary
             px-2 text-xs font-bold text-white hidden">
      0
    </span>
  </a>

  <!-- CART -->
<a href="/IndusAgrii/public/cart.php"
   class="header-icon relative text-white hover:text-brand-primary transition"
     aria-label="Cart">
    <i class="fa-solid fa-cart-shopping text-lg"></i>
    <span
      id="cartCount"
      class="absolute -right-3 -top-2
             rounded-full bg-brand-primary
             px-2 text-xs font-bold text-white">
      0
    </span>
  </a>

</div>

    </nav>
  </div>

<div id="mobileMenu"
  class="fixed top-0 left-0 z-[70]
         h-full w-[85%] max-w-sm
         bg-white shadow-2xl
         -translate-x-full
         transition-transform duration-300 ease-out
         md:hidden">

    <!-- MENU HEADER -->
    <div class="flex items-center justify-between border-b px-6 py-4">
      <span class="text-lg font-bold">Menu</span>
      <button id="mobileClose"
        class="text-2xl hover:text-brand-primary transition"
        aria-label="Close menu">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- MENU CONTENT -->
    <div class="p-6 space-y-6">

      <a href="/IndusAgrii/public/" class="block text-lg font-semibold">Home</a>

      <details class="group">
        <summary
          class="flex items-center justify-between rounded-lg px-2 py-2
                 text-lg font-semibold cursor-pointer
                 hover:bg-brand-primary/10 transition">
          Shop by Category
          <i class="fa-solid fa-chevron-down transition group-open:rotate-180"></i>
        </summary>

        <div class="mt-4 space-y-2 pl-4 border-l-2 border-brand-primary/30">

          <a href="/IndusAgrii/public/products.php"
             class="flex items-center gap-3 rounded-md px-3 py-2 text-base font-medium
                    hover:bg-brand-primary/10 transition">
            <i class="fa-solid fa-box-open text-brand-primary"></i>
            All Products
          </a>

          <a href="/IndusAgrii/public/rice.php"
             class="flex items-center gap-3 rounded-md px-3 py-2 text-base font-medium
                    hover:bg-brand-primary/10 transition">
            <i class="fa-solid fa-bowl-rice text-brand-primary"></i>
            Rice
          </a>

          <a href="/IndusAgrii/public/millets.php"
             class="flex items-center gap-3 rounded-md px-3 py-2 text-base font-medium
                    hover:bg-brand-primary/10 transition">
            <i class="fa-solid fa-seedling text-brand-primary"></i>
            Millets
          </a>

        </div>
      </details>

      <a href="/IndusAgrii/public/about.php" class="block text-lg font-semibold">About</a>
      <a href="/IndusAgrii/public/contact.php" class="block text-lg font-semibold">Contact</a>


<div class="pt-4 border-t space-y-4">

  <!-- USER ROW -->
  <?php if ($isLoggedIn): ?>
  <div class="flex items-center justify-between">
    <a
      href="/IndusAgrii/public/profile.php"
      class="flex items-center gap-2 text-base font-semibold">

      <span
        class="flex h-9 w-9 items-center justify-center
               rounded-full bg-gradient-to-br <?= $avatarGradient ?>
               text-sm font-bold text-white">
        <?= htmlspecialchars($userInitial) ?>
      </span>

      <span><?= htmlspecialchars($userFirstName) ?></span>
    </a>

    <a
      href="/IndusAgrii/public/logout.php"
      class="text-red-600 text-sm font-semibold">
      Logout
    </a>
  </div>
  <?php else: ?>
  <button
    type="button"
    id="openAuthModalMobile"
    class="text-lg font-semibold">
    Login
  </button>
  <?php endif; ?>

  <!-- ICON ROW -->
  <div class="flex items-center justify-end gap-6">

    <!-- WISHLIST -->
    <a href="/IndusAgrii/public/wishlist.php"
       class="relative flex items-center justify-center"
       aria-label="Wishlist">
      <i class="fa-regular fa-heart text-xl"></i>
      <span
        id="wishlistCountMobile"
        class="absolute -top-2 -right-2
               min-w-[18px] h-[18px]
               bg-emerald-700 text-white
               text-[11px] font-bold
               rounded-full flex items-center justify-center hidden">
        0
      </span>
    </a>

    <!-- CART -->
    <a href="/IndusAgrii/public/cart.php"
       class="relative flex items-center justify-center"
       aria-label="Cart">
      <i class="fa-solid fa-cart-shopping text-xl"></i>
      <span
        id="cartCountMobile"
        class="absolute -top-2 -right-2
               min-w-[18px] h-[18px]
               bg-emerald-700 text-white
               text-[11px] font-bold
               rounded-full flex items-center justify-center hidden">
        0
      </span>
    </a>

  </div>
</div>

  </div>

  <!-- BACKDROP -->
</div><div id="mobileBackdrop"
  class="fixed inset-0 z-[40]
         bg-black/40
         opacity-0 pointer-events-none
         transition-opacity duration-300 md:hidden">
</div>



</header>

<?php if ($notifyBanner): ?>
  <div
    class="bg-emerald-700 text-white
           text-sm text-center
           py-2 px-4">
    🎉 <strong><?= htmlspecialchars($notifyBanner) ?></strong>
    is now back in stock!
  </div>
<?php endif; ?>



<!-- Cookies -->
<div
  id="cookieConsent"
  class="fixed bottom-6 right-6 z-[999]
         w-full max-w-sm
         rounded-2xl bg-white p-6 shadow-2xl
         opacity-0 translate-y-6 pointer-events-none
         transition-all duration-300 ease-out">

  <div class="flex flex-col gap-4">

    <p class="text-sm text-brand-dark">
      We use cookies to improve your experience and analyze site traffic.
      Essential cookies are always enabled.
    </p>

    <div class="flex flex-wrap items-center gap-3">

      <button
        id="acceptCookies"
        class="rounded-full bg-brand-primary px-5 py-2
               text-sm font-semibold text-white
               transition hover:opacity-90">
        Accept
      </button>

      <button
        id="declineCookies"
        class="rounded-full border px-5 py-2
               text-sm font-semibold
               transition hover:bg-gray-100">
        Decline
      </button>

      <button
        id="openCookieSettings"
        class="text-sm font-semibold text-brand-primary hover:underline">
        Settings
      </button>

    </div>
  </div>
</div>

<div
  id="cookieSettingsModal"
  role="dialog"
  aria-modal="true"
  aria-labelledby="cookieSettingsTitle"
  class="fixed inset-0 z-[1000] hidden
         bg-black/40 backdrop-blur-sm
         flex items-center justify-center px-4">

  <div
    class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl
           opacity-0 scale-95
           transition-all duration-300 ease-out"
    id="cookieSettingsPanel">

    <h3 id="cookieSettingsTitle"
        class="text-lg font-bold text-brand-dark">
      Cookie Preferences
    </h3>

    <p class="mt-2 text-sm text-brand-muted">
      Manage how we use cookies. Essential cookies are always enabled.
    </p>

    <!-- Cookie Toggles -->
    <div class="mt-5 space-y-4">

      <!-- Essential -->
      <div class="flex items-center justify-between">
        <div>
          <p class="font-semibold text-sm">Essential Cookies</p>
          <p class="text-xs text-brand-muted">
            Required for site functionality
          </p>
        </div>
        <input type="checkbox" checked disabled class="h-5 w-5">
      </div>

      <!-- Analytics -->
      <div class="flex items-center justify-between">
        <div>
          <p class="font-semibold text-sm">Analytics Cookies</p>
          <p class="text-xs text-brand-muted">
            Helps us understand site usage
          </p>
        </div>
        <input id="analyticsToggle"
               type="checkbox"
               class="h-5 w-5 cursor-pointer">
      </div>

      <!-- Marketing -->
      <div class="flex items-center justify-between">
        <div>
          <p class="font-semibold text-sm">Marketing Cookies</p>
          <p class="text-xs text-brand-muted">
            Used for future advertising
          </p>
        </div>
        <input id="marketingToggle"
               type="checkbox"
               class="h-5 w-5 cursor-pointer">
      </div>

    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end gap-3">
      <button
        id="closeCookieSettings"
        class="rounded-lg border px-4 py-2
               text-sm font-semibold transition hover:bg-gray-100">
        Cancel
      </button>

      <button
        id="saveCookieSettings"
        class="rounded-lg bg-brand-primary px-4 py-2
               text-sm font-semibold text-white transition hover:opacity-90">
        Save Preferences
      </button>
    </div>

  </div>
</div>

<!-- AUTH MODAL -->
<div id="authModal"
     class="fixed inset-0 z-[1200] hidden
            bg-black/50 backdrop-blur-sm
            flex items-center justify-center px-4">

  <div id="authPanel"
       class="relative w-full max-w-md
              rounded-3xl bg-white
              shadow-2xl
              p-8
              opacity-0 scale-95
              transition-all duration-300 ease-out">

    <!-- CLOSE -->
    <button id="closeAuthModal"
            aria-label="Close"
            class="absolute right-5 top-5
                   text-gray-400 hover:text-brand-primary
                   transition">
      <i class="fa-solid fa-xmark text-xl"></i>
    </button>

    <!-- BRAND -->
    <div class="mb-6 text-center">
      <p class="text-xs font-semibold tracking-widest text-brand-primary uppercase">
        IndusAgrii
      </p>
      <h3 id="authTitle"
          class="mt-2 text-2xl font-bold text-brand-dark">
        Welcome back
      </h3>
      <p class="mt-1 text-sm text-brand-muted">
        Login to continue shopping pure farm produce
      </p>
    </div>

    <!-- LOGIN FORM -->
    <form id="loginForm" class="space-y-4">

      <div>
        <label class="mb-1 block text-xs font-semibold text-brand-muted">
          Email address
        </label>
        <input
          id="loginEmail"
          type="email"
          required
          placeholder="you@example.com"
          class="w-full rounded-xl border
                 px-4 py-3 text-sm
                 focus:outline-none
                 focus:ring-2 focus:ring-brand-primary/60">
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-brand-muted">
          Password
        </label>
        <input
          id="loginPassword"
          type="password"
          required
          placeholder="••••••••"
          class="w-full rounded-xl border
                 px-4 py-3 text-sm
                 focus:outline-none
                 focus:ring-2 focus:ring-brand-primary/60">
      </div>

      <button
        type="submit"
        class="mt-2 w-full rounded-xl
               bg-brand-primary py-3
               text-sm font-semibold text-white
               transition hover:opacity-90">
        Login
      </button>
    </form>

    <!-- DIVIDER -->
    <div class="my-6 flex items-center gap-3 text-xs text-brand-muted">
      <span class="h-px flex-1 bg-gray-200"></span>
      OR
      <span class="h-px flex-1 bg-gray-200"></span>
    </div>

    <!-- SWITCH TO SIGNUP -->
    <button id="showSignup"
            class="w-full rounded-xl border
                   py-3 text-sm font-semibold
                   transition hover:bg-gray-50">
      Create a new account
    </button>

    <!-- SIGNUP FORM -->
    <form id="signupForm" class="mt-6 hidden space-y-4">

      <div>
        <label class="mb-1 block text-xs font-semibold text-brand-muted">
          Full name
        </label>
        <input
          id="signupName"
          type="text"
          required
          placeholder="Your full name"
          class="w-full rounded-xl border
                 px-4 py-3 text-sm
                 focus:outline-none
                 focus:ring-2 focus:ring-brand-primary/60">
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-brand-muted">
          Email address
        </label>
        <input
          id="signupEmail"
          type="email"
          required
          placeholder="you@example.com"
          class="w-full rounded-xl border
                 px-4 py-3 text-sm
                 focus:outline-none
                 focus:ring-2 focus:ring-brand-primary/60">
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-brand-muted">
          Password
        </label>
        <input
          id="signupPassword"
          type="password"
          required
          placeholder="Create a strong password"
          class="w-full rounded-xl border
                 px-4 py-3 text-sm
                 focus:outline-none
                 focus:ring-2 focus:ring-brand-primary/60">
      </div>

      <button
        type="submit"
        class="mt-2 w-full rounded-xl
               bg-brand-primary py-3
               text-sm font-semibold text-white
               transition hover:opacity-90">
        Create account
      </button>

      <button id="backToLogin"
              type="button"
              class="w-full text-sm font-semibold
                     text-brand-primary
                     hover:underline">
        Back to login
      </button>
    </form>

  </div>
</div>


<main>
