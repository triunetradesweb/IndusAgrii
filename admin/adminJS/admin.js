document.addEventListener("DOMContentLoaded", () => {

  if (window.__ADMIN_JS_LOADED__) return;
  window.__ADMIN_JS_LOADED__ = true;


  /* =========================================================
     DOM ELEMENTS
  ========================================================= */
  const modal = document.getElementById("productModal");
  const form = document.getElementById("productForm");
  const addProductBtn = document.getElementById("addProductBtn");
  const bulkDeleteBtn = document.getElementById("bulkDeleteBtn");

  const mainImageInput = form.querySelector('[name="main_image"]');
  const galleryInput = form.querySelector('[name="gallery_images[]"]');
  const mainPreview = document.getElementById("mainImagePreview");
  const galleryPreview = document.getElementById("galleryPreview");

  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");

  if (!modal || !form) return;

  let existingGallery = [];
  let draggedItem = null;

  /* =========================================================
     HELPERS
  ========================================================= */
const showToast = (message, type = "success") => {
  const container = document.getElementById("toast-container");
  if (!container) return;

  const colors = {
    success: "bg-emerald-800",
    error: "bg-red-700",
    info: "bg-gray-900"
  };

  const toast = document.createElement("div");

  toast.className = `
    pointer-events-auto
    min-w-[260px] max-w-[360px]
    px-4 py-3
    rounded-2xl
    text-white text-sm font-semibold
    shadow-2xl
    flex items-center gap-3
    ${colors[type] || colors.success}
    transform translate-x-24 opacity-0
    transition-all duration-300 ease-out
  `;

  toast.textContent = message;
  container.appendChild(toast);

  // ENTER
  requestAnimationFrame(() => {
    toast.classList.remove("translate-x-24", "opacity-0");
    toast.classList.add("translate-x-0", "opacity-100");
  });

  // EXIT
  setTimeout(() => {
    toast.classList.add("translate-x-24", "opacity-0");
  }, 3000);

  // REMOVE
  setTimeout(() => {
    toast.remove();
  }, 3500);
};

const showConfirm = ({ title, message, confirmText = "Confirm", onConfirm }) => {
  const modal = document.getElementById("confirmModal");
  const titleEl = document.getElementById("confirmTitle");
  const msgEl = document.getElementById("confirmMessage");
  const okBtn = document.getElementById("confirmOk");
  const cancelBtn = document.getElementById("confirmCancel");

  titleEl.innerText = title;
  msgEl.innerText = message;
  okBtn.innerText = confirmText;

  modal.classList.remove("hidden");
  modal.classList.add("flex");

  const close = () => {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    okBtn.onclick = null;
    cancelBtn.onclick = null;
  };

  cancelBtn.onclick = close;

  okBtn.onclick = () => {
    close();
    onConfirm?.();
  };
};


// IMAGE GALLERY
  const clearMainPreview = () => mainPreview.innerHTML = "";
  const clearGalleryPreview = () => galleryPreview.innerHTML = "";

  const renderGallery = () => {
    clearGalleryPreview();

  existingGallery.forEach((img, index) => {
    galleryPreview.insertAdjacentHTML("beforeend", `
      <div class="relative group gallery-item
           w-32 h-32 sm:w-36 sm:h-36
           rounded-xl overflow-hidden border bg-white"
           draggable="true">

        <img src="/IndusAgrii/uploads/${img}"
             class="w-full h-full object-cover">

        <button type="button"
          class="absolute top-2 right-2 z-10
                 bg-red-600 text-white
                 w-6 h-6 rounded-full text-sm
                 hidden group-hover:flex
                 items-center justify-center
                 shadow remove-gallery">
          ✕
        </button>
      </div>
    `);
  });

  syncGalleryToHiddenField();
};


  const syncGalleryToHiddenField = () => {
    form.querySelector('#existing_gallery').value =
      JSON.stringify(existingGallery);
  };

  /* =========================================================
     MODAL OPEN / CLOSE
  ========================================================= */
function openModal(product = null) {
  form.reset();
  clearMainPreview();
  clearGalleryPreview();
  existingGallery = [];

  form.querySelector('[name="id"]').value = "";
  form.querySelector('#existing_image').value = "";
  form.querySelector('#existing_gallery').value = "[]";

  document.getElementById("modalTitle").innerText =
    product ? "Edit Product" : "Add Product";

  if (product) {
  for (const key in product) {
    if (!form.elements[key]) continue;

    const el = form.elements[key];
    if (el.type === "file") continue;

    el.value = product[key] ?? "";
  }

  // ACTIVE / INACTIVE
  form.elements['is_active'].value =
    product.is_active == 1 ? 'active' : 'inactive';

  // 🔥 NEW: PRESERVE ACTIVE STATE (CRITICAL FIX)
  form.querySelector('#existing_is_active').value = product.is_active;

  // STOCK FIELD
  if (form.elements['in_stock']) {
    form.elements['in_stock'].value =
      product.in_stock == 1 ? '1' : '0';
  }

  // MAIN IMAGE
  if (product.main_image) {
    mainPreview.innerHTML = `
      <img src="/IndusAgrii/uploads/${product.main_image}"
           class="w-full h-full object-cover rounded-xl">`;
    form.querySelector('#existing_image').value = product.main_image;
  }

  // GALLERY
  if (product.gallery_images) {
    existingGallery = JSON.parse(product.gallery_images);
    renderGallery();
  }
}

modal.classList.remove("hidden");
modal.classList.add("flex");
}


  function closeModal() {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
  }

  addProductBtn?.addEventListener("click", () => openModal());


/* =========================================================
   MAIN IMAGE PREVIEW + REPLACE CONFIRM (CUSTOM MODAL)
========================================================= */
mainImageInput.addEventListener("change", () => {
  const file = mainImageInput.files[0];
  if (!file) return;

  const previewImage = () => {
    clearMainPreview();

    const reader = new FileReader();
    reader.onload = e => {
      mainPreview.innerHTML = `
        <img src="${e.target.result}"
             class="w-full h-full object-cover rounded-xl">`;
    };
    reader.readAsDataURL(file);
  };

  // If editing & image already exists → confirm replace
  if (form.querySelector("#existing_image").value) {
    showConfirm({
      title: "Replace Main Image",
      message: "This will replace the existing main product image.",
      confirmText: "Replace",
      onConfirm: previewImage
    });

    // Reset input so cancel truly cancels
    mainImageInput.value = "";
    return;
  }

  // New product → no confirm needed
  previewImage();
});


  /* =========================================================
     ADD NEW GALLERY IMAGES
  ========================================================= */
galleryInput.addEventListener("change", () => {
  [...galleryInput.files].forEach(file => {
    const reader = new FileReader();

    reader.onload = e => {
      galleryPreview.insertAdjacentHTML("beforeend", `
        <div class="relative group gallery-item
             w-32 h-32 sm:w-36 sm:h-36
             rounded-xl overflow-hidden border bg-white"
             draggable="true">

          <img src="${e.target.result}"
               class="w-full h-full object-cover">

          <button type="button"
            class="absolute top-2 right-2 z-10
                   bg-red-600 text-white
                   w-6 h-6 rounded-full text-sm
                   hidden group-hover:flex
                   items-center justify-center
                   shadow remove-gallery">
            ✕
          </button>
        </div>
      `);
    };

    reader.readAsDataURL(file);
  });
});

  /* =========================================================
     REMOVE GALLERY IMAGE (SYNC + SERVER DELETE)
  ========================================================= */
  galleryPreview.addEventListener("click", (e) => {
    if (!e.target.classList.contains("remove-gallery")) return;

    const item = e.target.closest(".gallery-item");
    const index = [...galleryPreview.children].indexOf(item);

    const removedImage = existingGallery[index];
    existingGallery.splice(index, 1);
    item.remove();
    syncGalleryToHiddenField();

    /* DELETE IMAGE FROM SERVER */
    const fd = new FormData();
    fd.append("ajax", "1");
    fd.append("action", "delete_image");
    fd.append("image", removedImage);

    fetch("adminProducts.php", { method: "POST", body: fd });
  });

  /* =========================================================
     DRAG & DROP REORDER (GALLERY)
  ========================================================= */
  galleryPreview.addEventListener("dragstart", (e) => {
    draggedItem = e.target.closest(".gallery-item");
    draggedItem?.classList.add("opacity-50");
  });

  galleryPreview.addEventListener("dragend", () => {
    draggedItem?.classList.remove("opacity-50");
    draggedItem = null;

    existingGallery = [...galleryPreview.children].map(item =>
      item.querySelector("img").getAttribute("src").split("/").pop()
    );
    syncGalleryToHiddenField();
  });

  galleryPreview.addEventListener("dragover", (e) => {
    e.preventDefault();
    const target = e.target.closest(".gallery-item");
    if (!target || target === draggedItem) return;

    const rect = target.getBoundingClientRect();
    const next = (e.clientY - rect.top) > rect.height / 2;
    galleryPreview.insertBefore(draggedItem, next ? target.nextSibling : target);
  });

  /* =========================================================
     FORM SUBMIT
  ========================================================= */
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const name = form.name.value.trim();
    const price = parseFloat(form.price.value);

    if (!name) return showToast("Product name is required", "error");
    if (isNaN(price) || price <= 0) return showToast("Enter a valid price", "error");

    syncGalleryToHiddenField();

    const fd = new FormData(form);
    fd.append("ajax", "1");
    fd.append("action", "save");

      const submitBtn =
        document.querySelector('button[form="productForm"][type="submit"]');

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerText = "Saving...";
      }

    const res = await fetch("adminProducts.php", {
      method: "POST",
      body: fd
    });

    const data = await res.json();
      if (data.success) {
      showToast("Product saved successfully");
      setTimeout(() => location.reload(), 800);
    } else {
      showToast(data.error || "Save failed", "error");
    }
});

  /* =========================================================
     BULK DELETE
  ========================================================= */
bulkDeleteBtn?.addEventListener("click", async () => {
  const ids = [...document.querySelectorAll(".bulkCheck:checked")]
    .map(cb => cb.value);

  if (!ids.length) {
    showToast("Select products to delete", "error");
    return;
  }

  showConfirm({
    title: "Delete Selected Products",
    message: "All selected products will be permanently deleted.",
    confirmText: "Delete All",
    onConfirm: async () => {
      const fd = new FormData();
      fd.append("ajax", "1");
      fd.append("action", "bulk_delete");
      ids.forEach(id => fd.append("ids[]", id));

      await fetch("adminProducts.php", { method: "POST", body: fd });

      showToast("Selected products deleted");
      setTimeout(() => location.reload(), 700);
    }
  });
});


  /* =========================================================
     SEARCH + FILTER
  ========================================================= */
  const filterProducts = () => {
    const search = searchInput.value.toLowerCase();
    const category = categoryFilter.value;

    document.querySelectorAll(".product-card").forEach(card => {
      const name = card.dataset.name;
      const cat = card.dataset.category;
      card.style.display =
        name.includes(search) && (!category || cat === category)
          ? "block" : "none";
    });
  };

  searchInput?.addEventListener("input", filterProducts);
  categoryFilter?.addEventListener("change", filterProducts);

// Edit Handler
document.addEventListener("click", (e) => {
  const btn = e.target.closest(".edit-btn");
  if (!btn) return;

  // console.log("EDIT CLICKED", btn.dataset.product);

  const product = JSON.parse(btn.dataset.product);
  openModal(product);
});


  /* =========================================================
     GLOBAL EXPORTS
  ========================================================= */
  window.editProduct = (product) => openModal(product);
  window.closeModal = closeModal;

window.toggleStatus = async (id, checked) => {
  const fd = new FormData();
  fd.append("ajax", "1");
  fd.append("action", "toggle_status");
  fd.append("id", id);
  fd.append("status", checked ? 1 : 0);

  const res = await fetch("adminProducts.php", {
    method: "POST",
    body: fd
  });

  const data = await res.json();

  if (!data.success) {
    showToast("Failed to update status", "error");
    return;
  }

  showToast(
    checked ? "Product Activated" : "Product Deactivated",
    "info"
  );

  // 🔥 reload to keep UI + DB perfectly in sync
  setTimeout(() => location.reload(), 400);
};

  
window.toggleStock = async (id, checked) => {
  const fd = new FormData();
  fd.append("ajax", "1");
  fd.append("action", "toggle_stock");
  fd.append("id", id);
  fd.append("stock", checked ? 1 : 0);

  await fetch("adminProducts.php", { method: "POST", body: fd });

  showToast(
    checked ? "Marked In Stock" : "Marked Out of Stock",
    "info"
  );

  // 🔥 FORCE UI CONSISTENCY
  setTimeout(() => {
    location.reload();
  }, 400);
};

window.deleteProduct = (id) => {
  showConfirm({
    title: "Delete Product",
    message: "This product will be permanently deleted.",
    confirmText: "Delete",
    onConfirm: async () => {
      const fd = new FormData();
      fd.append("ajax", "1");
      fd.append("action", "delete");
      fd.append("id", id);

      await fetch("adminProducts.php", { method: "POST", body: fd });

      showToast("Product deleted");
      setTimeout(() => location.reload(), 700);
    }
  });
};
});
