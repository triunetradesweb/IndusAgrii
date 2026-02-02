<?php
// admin/includes/admin-footer.php
?>

</main>
</div>

<!-- Toast container (used by JS in adminProducts.php) -->
<div id="toastRoot" class="fixed top-4 right-4 z-[9999]"></div>

<div id="adminToast"
  class="fixed bottom-6 right-6 hidden
         bg-emerald-800 text-white
         px-5 py-3 rounded-xl
         font-semibold shadow-xl z-50">
</div>


<!-- CONFIRM MODAL -->
<div id="confirmModal"
     class="fixed inset-0 bg-black/50 z-50 hidden
            items-center justify-center pointer-events-none">

  <div class="bg-white rounded-2xl p-6 w-[90%] max-w-sm space-y-4">
    <h3 class="text-lg font-bold">Confirm Action</h3>
    <p id="confirmText" class="text-sm text-gray-600"></p>

    <div class="flex justify-end gap-3 pt-2">
      <button id="confirmCancel"
              class="px-4 py-2 rounded-xl bg-gray-200 font-semibold">
        Cancel
      </button>
      <button id="confirmOk"
              class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold">
        Yes, Continue
      </button>
    </div>
  </div>
</div>

<!-- ADMIN TOAST -->
<div id="adminToast"
     class="fixed bottom-6 right-6 bg-black text-white px-5 py-3 rounded-xl hidden z-50 flex items-center gap-3">
  <span id="toastText" class="text-sm"></span>
  <button id="toastUndo"
          class="hidden text-xs underline ml-3">
    Undo
  </button>
</div>

<div id="toast-container"
     class="fixed top-6 right-6 z-[9999] space-y-3 pointer-events-none"></div>


<script src="/IndusAgrii/admin/adminJS/admin.js" defer></script>
</body>
</html>
