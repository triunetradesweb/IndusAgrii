<?php
session_start();
require_once __DIR__ . "/../config/database.php";
include "../includes/header.php";

$success = false;
$errors  = [];

/* ================= HANDLE CONTACT FORM ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  /* ---------- HONEYPOT (ANTI-SPAM) ---------- */
  if (!empty($_POST['company'])) {
    exit; // bot detected
  }

  $name    = trim($_POST['name'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $phone   = trim($_POST['phone'] ?? '');
  $message = trim($_POST['message'] ?? '');

  /* ---------- VALIDATION ---------- */
  if ($name === '' || strlen($name) < 3) {
    $errors[] = "Please enter your full name.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
  }

  if ($phone && !preg_match('/^[0-9+\-\s]{8,15}$/', $phone)) {
    $errors[] = "Please enter a valid phone number.";
  }

  if ($message === '' || strlen($message) < 10) {
    $errors[] = "Message must be at least 10 characters.";
  }

  /* ---------- SAVE ---------- */
  if (empty($errors)) {
    $stmt = $conn->prepare(
      "INSERT INTO contact_enquiries (name, email, phone, message)
       VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $name, $email, $phone, $message);
    $stmt->execute();
    $stmt->close();
    $success = true;
  }
}
?>

<!-- ================= CONTACT HERO ================= -->
<section class="relative overflow-hidden bg-gray-900">
  <img src="/IndusAgrii/assets/images/banners/hero1.jpg"
       alt="Contact IndusAgrii"
       class="absolute inset-0 w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/30"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-6
              pt-[calc(8rem+env(safe-area-inset-top))]
              pb-24">
    <span class="inline-block rounded-full bg-white/20 px-4 py-1 text-sm font-semibold text-white backdrop-blur">
      We’re Here to Help
    </span>

    <h1 class="mt-5 max-w-3xl text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
      Contact <span class="text-green-300">IndusAgrii</span>
    </h1>

    <p class="mt-6 max-w-2xl text-lg sm:text-xl text-white/95 leading-relaxed">
      Questions about products, bulk orders, or partnerships?
      We’d love to hear from you.
    </p>
  </div>
</section>

<!-- ================= CONTACT CONTENT ================= -->
<section class="bg-white py-16">
  <div class="max-w-7xl mx-auto px-6 grid gap-12 lg:grid-cols-2">

    <!-- LEFT INFO -->
    <div class="space-y-8">
      <h2 class="reveal text-3xl font-extrabold">Get in Touch</h2>

      <p class="reveal text-brand-muted text-lg leading-relaxed">
        From everyday household needs to bulk sourcing, IndusAgrii believes in
        transparent communication and dependable support.
      </p>

      <div class="space-y-5">
        <div class="reveal flex items-start gap-4">
          <i class="fa-solid fa-location-dot text-brand-primary text-xl mt-1"></i>
          <div>
            <h3 class="font-bold">Office</h3>
            <p class="text-brand-muted">
              Ishwari Apartment,<br>
              HQ9C+P2F, Nandan Prospera Rd,<br>
              Laxman Nagar, Baner,<br>
              Pune, Maharashtra 411045
            </p>
          </div>
        </div>

        <div class="reveal flex items-start gap-4">
          <i class="fa-solid fa-envelope text-brand-primary text-xl mt-1"></i>
          <div>
            <h3 class="font-bold">Email</h3>
            <p class="text-brand-muted">contact@triunetrades.com</p>
          </div>
        </div>

        <div class="reveal flex items-start gap-4">
          <i class="fa-solid fa-phone text-brand-primary text-xl mt-1"></i>
          <div>
            <h3 class="font-bold">Phone</h3>
            <p class="text-brand-muted">+91 96071 11550</p>
          </div>
        </div>
      </div>

      <!-- LOCATION -->
      <div class="reveal flex items-center gap-4">
        <div class="h-px flex-1 bg-gray-200"></div>
        <span class="text-xs font-semibold tracking-wider text-brand-muted uppercase">
          Visit Us
        </span>
        <div class="h-px flex-1 bg-gray-200"></div>
      </div>

      <div class="reveal mt-8">
        <div class="aspect-square w-full rounded-2xl overflow-hidden border">
          <iframe
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            class="w-full h-full"
            src="https://www.google.com/maps?q=Ishwari%20Apartment%20HQ9C%2BP2F%20Nandan%20Prospera%20Rd%20Laxman%20Nagar%20Baner%20Pune%20Maharashtra%20411045&output=embed">
          </iframe>
        </div>
      </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="space-y-8">

      <div class="reveal rounded-3xl border bg-gray-50 p-8 shadow-sm">
        <h3 class="text-2xl font-bold mb-2">Send Us a Message</h3>
        <p class="text-sm text-brand-muted mb-6">
          Share your query and our team will assist you shortly.
        </p>

        <?php if (!empty($errors)): ?>
          <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">
              <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="post" class="space-y-5">

          <!-- Honeypot -->
          <input type="text" name="company" tabindex="-1" autocomplete="off" class="hidden">

          <div>
            <label class="block text-sm font-semibold mb-1">Full Name</label>
            <input name="name" required
                   class="w-full h-12 rounded-xl border px-4 text-sm outline-none
                          transition focus:border-brand-primary">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Email Address</label>
            <input name="email" type="email" required
                   class="w-full h-12 rounded-xl border px-4 text-sm outline-none
                          transition focus:border-brand-primary">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Phone (optional)</label>
            <input name="phone"
                   class="w-full h-12 rounded-xl border px-4 text-sm outline-none
                          transition focus:border-brand-primary">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Message</label>
            <textarea name="message" rows="4" required
                      class="w-full rounded-xl border px-4 py-3 text-sm outline-none
                             transition focus:border-brand-primary"></textarea>
          </div>

          <button type="submit"
                  class="w-full rounded-full bg-brand-primary py-4 text-white font-bold
                         transition hover:-translate-y-1 active:scale-95">
            Send Message →
          </button>
        </form>
      </div>

      <div class="reveal rounded-3xl border bg-white p-6">
        <div class="flex items-start gap-3">
          <i class="fa-solid fa-clock text-brand-primary mt-1"></i>
          <div class="text-sm text-brand-muted">
            <p class="font-semibold text-brand-dark">We usually respond within 24 hours</p>
            <p class="mt-1">
              <span class="font-semibold">Business Hours:</span><br>
              Monday – Saturday: 10:00 AM – 6:30 PM<br>
              Sunday: Closed
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php if ($success): ?>
<div id="contactToast"
     class="fixed bottom-6 right-6 z-50 flex gap-3 rounded-2xl
            bg-white border border-emerald-200 px-5 py-4 shadow-lg">
  <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
  <div class="text-sm">
    <p class="font-semibold">Enquiry sent successfully</p>
    <p class="text-brand-muted">Our team will contact you within 24 hours.</p>
  </div>
</div>

<script>
  setTimeout(() => {
    const toast = document.getElementById('contactToast');
    if (toast) toast.classList.add('opacity-0', 'translate-y-2');
  }, 3500);

  setTimeout(() => {
    const toast = document.getElementById('contactToast');
    if (toast) toast.remove();
  }, 4200);
</script>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>
