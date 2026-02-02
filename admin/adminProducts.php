  <?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once __DIR__ . "/includes/admin-auth.php";
  require_once __DIR__ . "/../config/database.php";

  $pageTitle = "Products | Indus Agrii";

  /* ======================================================
    AJAX HANDLERS
  ====================================================== */
  if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');

    /* DELETE SINGLE */
    if ($_POST['action'] === 'delete') {
      $id = (int) $_POST['id'];
      $conn->query("DELETE FROM products WHERE id=$id");
      echo json_encode(['success' => true]);
      exit;
    }

    /* BULK DELETE */
    if ($_POST['action'] === 'bulk_delete') {
      $ids = implode(',', array_map('intval', $_POST['ids']));
      $conn->query("DELETE FROM products WHERE id IN ($ids)");
      echo json_encode(['success' => true]);
      exit;
    }

    /* DELETE GALLERY IMAGE */
    if ($_POST['action'] === 'delete_image') {
      $image = basename($_POST['image']);
      $path = __DIR__ . "/../uploads/" . $image;

      if (file_exists($path)) {
        unlink($path);
      }

      echo json_encode(['success' => true]);
      exit;
    }

    /* STATUS TOGGLE */
    if ($_POST['action'] === 'toggle_status') {
      $id = (int) $_POST['id'];
      $status = (int) $_POST['status'];
      $conn->query("UPDATE products SET is_active=$status WHERE id=$id");
      echo json_encode(['success' => true]);
      exit;
    }

    /* STOCK TOGGLE (NEW – ADDITIVE ONLY) */
if ($_POST['action'] === 'toggle_stock') {
  $id = (int) $_POST['id'];
  $stock = (int) $_POST['stock'];

  $conn->query("UPDATE products SET in_stock=$stock WHERE id=$id");

  if ($stock === 1) {
    $conn->query("
      UPDATE notify_requests
      SET is_notified = 1
      WHERE product_id = $id
        AND is_notified = 0
    ");
  }

  echo json_encode(['success' => true]);
  exit;
}


    /* ADD / EDIT PRODUCT */
    if ($_POST['action'] === 'save') {

      /* ---------- BASIC FIELDS ---------- */
      $id           = $_POST['id'] ?? null;
      $name         = $conn->real_escape_string(trim($_POST['name']));
      $slug         = $conn->real_escape_string(trim($_POST['slug']));
      $short_title  = $conn->real_escape_string($_POST['short_title']);
      $short_desc   = $conn->real_escape_string($_POST['short_description']);
      $long_desc    = $conn->real_escape_string($_POST['long_description']);
      $seo_title    = $conn->real_escape_string($_POST['seo_title']);
      $seo_desc     = $conn->real_escape_string($_POST['seo_description']);
      $category     = $_POST['category'];
      $is_active = isset($_POST['is_active'])
      ? ($_POST['is_active'] === 'active' ? 1 : 0)
      : (int)($_POST['existing_is_active'] ?? 0);
      $in_stock     = $_POST['in_stock'] === '1' ? 1 : 0; // NEW

      /* ---------- PRICE LOGIC (LOCKED) ---------- */
      // Admin enters PRICE PER KG
      $base_price = (float) $_POST['price'];

      if ($base_price <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid price']);
        exit;
      }

      // Default display price = 2kg
      $price = $base_price * 2;

      // Fixed pack sizes
      $pack_sizes = json_encode([2, 5, 10, 30]);

      /* ---------- SLUG SAFETY ---------- */
      $check = $conn->query(
        "SELECT id FROM products WHERE slug='$slug' AND id != '$id' LIMIT 1"
      );
      if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Slug already exists']);
        exit;
      }

      /* ---------- MAIN IMAGE ---------- */
      $mainImage = $_POST['existing_image'] ?? null;

      if (!empty($_FILES['main_image']['name'])) {
        $mainImage = time() . '_' . basename($_FILES['main_image']['name']);
        move_uploaded_file(
          $_FILES['main_image']['tmp_name'],
          __DIR__ . "/../uploads/" . $mainImage
        );
      }

      /* ---------- GALLERY ---------- */
      $gallery = json_decode($_POST['existing_gallery'] ?? '[]', true);

      if (!empty($_FILES['gallery_images']['name'][0])) {
        foreach ($_FILES['gallery_images']['name'] as $i => $file) {
          $gName = time() . '_' . basename($file);
          move_uploaded_file(
            $_FILES['gallery_images']['tmp_name'][$i],
            __DIR__ . "/../uploads/" . $gName
          );
          $gallery[] = $gName;
        }
      }

      $galleryJson = json_encode($gallery);

      /* ---------- INSERT / UPDATE ---------- */
      if ($id) {
        $sql = "
          UPDATE products SET
            name='$name',
            slug='$slug',
            short_title='$short_title',
            short_description='$short_desc',
            long_description='$long_desc',
            seo_title='$seo_title',
            seo_description='$seo_desc',
            base_price='$base_price',
            price='$price',
            pack_sizes='$pack_sizes',
            category='$category',
            main_image='$mainImage',
            gallery_images='$galleryJson',
            is_active='$is_active',
            in_stock='$in_stock'
          WHERE id=$id
        ";
      } else {
        $sql = "
          INSERT INTO products
            (name, slug, short_title, short_description, long_description,
            seo_title, seo_description,
            base_price, price, pack_sizes,
            category, main_image, gallery_images, is_active, in_stock)
          VALUES
            ('$name','$slug','$short_title','$short_desc','$long_desc',
            '$seo_title','$seo_desc',
            '$base_price','$price','$pack_sizes',
            '$category','$mainImage','$galleryJson','$is_active','$in_stock')
        ";
      }

      $conn->query($sql);
      echo json_encode(['success' => true]);
      exit;
    }
  }

  /* ======================================================
    FETCH PRODUCTS
  ====================================================== */
$products = $conn->query("
  SELECT *
  FROM products
  ORDER BY created_at DESC
");
  ?>

  <?php include __DIR__ . "/includes/admin-header.php"; ?>
  <?php include __DIR__ . "/includes/admin-sidebar.php"; ?>

  <style>
  .input {
    width: 100%;
    padding: 0.65rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #d1d5db;
  }
  .input:focus {
    outline: none;
    border-color: #065f46;
    box-shadow: 0 0 0 2px rgba(6,95,70,.2);
  }
  </style>

  <div class="space-y-8">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900">Products</h1>
        <p class="text-sm text-gray-500">Add, edit and manage products shown on the website</p>
      </div>
      <div class="flex gap-3">
        <button id="bulkDeleteBtn" class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold">
          Bulk Delete
        </button>
        <button id="addProductBtn" class="px-4 py-2 rounded-xl bg-emerald-800 text-white font-semibold">
          + Add Product
        </button>
      </div>
    </div>

    <!-- SEARCH / FILTER -->
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col sm:flex-row gap-4">
      <input id="searchInput" type="text"
        placeholder="Search products by name..."
        class="input sm:w-1/2">

      <select id="categoryFilter" class="input sm:w-48">
        <option value="">All Products</option>
        <option value="rice">Rice</option>
        <option value="millets">Millets</option>
      </select>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <?php if ($products->num_rows === 0): ?>
        <div class="col-span-full text-center text-gray-500">
          No products added yet.
        </div>
      <?php endif; ?>

      <?php while ($p = $products->fetch_assoc()): ?>
        <div class="bg-white rounded-2xl shadow overflow-hidden product-card"
        <?= !$p['is_active'] ? 'opacity-50 grayscale' : '' ?>
            data-name="<?= strtolower($p['name']) ?>"
            data-category="<?= $p['category'] ?>">

          <div class="relative">
            <?php if (!$p['in_stock']): ?>
            <span class="absolute top-3 right-3
                        bg-red-600 text-white
                        text-xs font-bold
                        px-3 py-1 rounded-full z-10">
              Out of Stock
            </span>
          <?php endif; ?>

            <input type="checkbox"
              class="bulkCheck absolute top-3 left-3 w-5 h-5"
              value="<?= $p['id'] ?>">

            <img
              src="/IndusAgrii/uploads/<?= htmlspecialchars($p['main_image'] ?? 'placeholder.png') ?>"
              class="w-full h-44 object-cover">
          </div>

          <div class="p-5 space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
              <?= $p['category'] === 'rice'
                ? 'bg-emerald-100 text-emerald-800'
                : 'bg-yellow-100 text-yellow-800' ?>">
              <?= ucfirst($p['category']) ?>
            </span>

            <h3 class="font-bold text-gray-900"><?= htmlspecialchars($p['name']) ?></h3>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($p['slug']) ?></p>

            <p class="text-lg font-bold text-emerald-800">
              ₹<?= number_format($p['price'], 2) ?> / kg
            </p>

            <div class="flex items-center justify-between pt-3">

              <div class="flex flex-col gap-2 text-sm">

                <!-- ACTIVE TOGGLE (EXISTING) -->
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" class="hidden peer"
                    <?= $p['is_active'] ? 'checked' : '' ?>
                    onchange="toggleStatus(<?= $p['id'] ?>, this.checked)">
                  <div class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-emerald-600 relative transition">
                    <span class="absolute left-1 top-0.5 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-5"></span>
                  </div>
                  Active
                </label>

                <!-- STOCK TOGGLE (NEW) -->
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" class="hidden peer"
                    <?= $p['in_stock'] ? 'checked' : '' ?>
                    onchange="toggleStock(<?= $p['id'] ?>, this.checked)">
                  <div class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-blue-600 relative transition">
                    <span class="absolute left-1 top-0.5 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-5"></span>
                  </div>
                  In Stock
                </label>

              </div>

              <div class="flex gap-2">
                <button
                  class="px-3 py-1 rounded-lg bg-gray-100 font-semibold edit-btn"
                  data-product='<?= htmlspecialchars(json_encode($p), ENT_QUOTES, "UTF-8") ?>'>
                  Edit
                </button>
                <button
                  onclick="deleteProduct(<?= $p['id'] ?>)"
                  class="px-3 py-1 rounded-lg bg-red-600 text-white font-semibold">
                  Delete
                </button>
              </div>

            </div>
          </div>

        </div>
      <?php endwhile; ?>

    </div>
  </div>

  <!-- MODAL -->
<div id="productModal"
  class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

  <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl
              flex flex-col max-h-[90vh]">

    <!-- MODAL HEADER -->
    <div class="flex justify-between items-center px-6 py-4 border-b">
      <h2 class="text-lg font-bold text-gray-900" id="modalTitle">Add Product</h2>
      <button onclick="closeModal()" class="text-xl font-bold text-gray-500">
        &times;
      </button>
    </div>

    <!-- MODAL BODY -->
<form id="productForm" enctype="multipart/form-data"
      class="px-6 py-5 overflow-y-auto space-y-6">

  <input type="hidden" name="id" id="product_id">
  <input type="hidden" name="existing_is_active" id="existing_is_active">
  <input type="hidden" name="existing_image" id="existing_image">
  <input type="hidden" name="existing_gallery" id="existing_gallery">


      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input name="name" placeholder="Product Name" class="input">
        <input name="slug" placeholder="Slug" class="input">
      </div>

      <input name="short_title" placeholder="Short Title" class="input">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <textarea name="short_description" placeholder="Short Description"
          class="input min-h-[90px]"></textarea>
        <textarea name="long_description" placeholder="Long Description"
          class="input min-h-[90px]"></textarea>
      </div>

      <div class="bg-gray-50 rounded-xl p-4 space-y-3">
        <input name="seo_title" placeholder="SEO Title" class="input">
        <textarea name="seo_description" placeholder="SEO Description"
          class="input min-h-[70px]"></textarea>
      </div>

      <!-- ⬇⬇ ONLY CHANGE IS HERE (1 FIELD ADDED) ⬇⬇ -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="number" name="price" placeholder="Price per kg (₹)" class="input">

        <select name="category" class="input">
          <option value="rice">Rice</option>
          <option value="millets">Millets</option>
        </select>

        <select name="is_active" class="input">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>

        <!-- NEW: STOCK FIELD (ADDITIVE, NO SIDE EFFECTS) -->
        <select name="in_stock" class="input">
          <option value="1">In Stock</option>
          <option value="0">Out of Stock</option>
        </select>
      </div>
      <!-- ⬆⬆ NOTHING ELSE TOUCHED ⬆⬆ -->

      <div class="space-y-3">
        <input type="file" name="main_image" class="input">
        <div id="mainImagePreview"
          class="w-32 h-32 border rounded-xl overflow-hidden"></div>

        <input type="file" name="gallery_images[]" multiple class="input">
        <div id="galleryPreview"
          class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
      </div>

      <!-- MODAL FOOTER -->
      <div class="flex justify-end gap-3 pt-6 border-t">
        <button type="button"
          onclick="closeModal()"
          class="px-4 py-2 rounded-xl bg-gray-100 font-semibold">
          Cancel
        </button>

        <button type="submit"
          class="px-5 py-2 rounded-xl bg-emerald-800 text-white font-semibold">
          Save Product
        </button>
      </div>
    </form>
  </div>
</div>

<!-- CONFIRM MODAL -->
<div id="confirmModal"
  class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50">

  <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl p-6 space-y-4">

    <h3 id="confirmTitle"
      class="text-lg font-bold text-gray-900">
      Confirm Action
    </h3>

    <p id="confirmMessage"
      class="text-sm text-gray-600">
      Are you sure?
    </p>

    <div class="flex justify-end gap-3 pt-4">
      <button id="confirmCancel"
        class="px-4 py-2 rounded-xl bg-gray-100 font-semibold">
        Cancel
      </button>

      <button id="confirmOk"
        class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold">
        Yes, Delete
      </button>
    </div>

  </div>
</div>

<script src="/IndusAgrii/admin/adminJS/admin.js"></script>
<?php include __DIR__ . "/includes/admin-footer.php"; ?>
