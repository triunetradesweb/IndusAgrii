<?php
$unreadCount = 0;
$res = $conn->query("
  SELECT COUNT(*) AS c
  FROM contact_enquiries
  WHERE is_read = 0 AND is_deleted = 0
");
if ($res) {
  $unreadCount = (int) $res->fetch_assoc()['c'];
}
?>


<!-- SIDEBAR -->
<aside
  id="adminSidebar"
  class="fixed md:static top-16 left-0 h-[calc(100vh-4rem)]
         w-64 bg-white shadow-lg
         transform -translate-x-full md:translate-x-0
         transition-transform z-40
         pointer-events-none md:pointer-events-auto">

  <nav class="flex flex-col h-full p-4 gap-1">

    <a href="adminIndex.php"
       class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
              hover:bg-emerald-50 text-emerald-800">
      <i class="fa-solid fa-chart-line"></i>
      Dashboard
    </a>

    <a href="adminProducts.php"
       class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
              hover:bg-emerald-50">
      <i class="fa-solid fa-box"></i>
      Products
    </a>

      <a href="admin_enquires.php"
        class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
                hover:bg-emerald-50">
        <i class="fa-solid fa-envelope-open-text"></i>
        Enquiries

        <?php if ($unreadCount > 0): ?>
          <span class="ml-auto inline-flex items-center justify-center
                      min-w-[20px] h-5 px-1.5
                      rounded-full bg-emerald-600
                      text-white text-xs font-bold">
            <?= $unreadCount ?>
          </span>
        <?php endif; ?>
      </a>

    <a href="admin_userDetails.php"
      class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
              hover:bg-emerald-50">
      <i class="fa-solid fa-envelope-open-text"></i>
      User Details
    </a>


    <div class="mt-auto pt-4 border-t">
      <a href="../"
         target="_blank"
         class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
                hover:bg-gray-100 text-gray-700">
        <i class="fa-solid fa-globe"></i>
        View Website
      </a>
    </div>

  </nav>
</aside>

<main class="flex-1 p-4 md:p-6">
