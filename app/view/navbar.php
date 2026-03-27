<?php
// ✅ Set this per page:
$showSearch = true;   // ← change to false on pages where you don't want the search bar
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Prompts Manager') ?></title>
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <!-- load your page‑specific CSS after, so it can override if needed -->
</head>
<body>

  <!-- ====================== NAVBAR ====================== -->
  <nav class="app-navbar">
    <!-- Brand / Title -->
    <div class="nav-brand">
      Prompts Manager
    </div>

    <!-- ✅ Search bar – only renders when $showSearch is true -->
    <?php if ($showSearch): ?>
      <div class="nav-center">
        <div class="nav-search">
          <form action="search" method="get">   <!-- point to your real search route -->
            <input type="text" name="q" placeholder="Search prompts, categories..." autocomplete="off">
          </form>
        </div>
      </div>
    <?php endif; ?>

    <!-- Right‑side actions (example) -->
    <div class="nav-actions">
      <a href="profile.php" class="btn btn-secondary">Profile</a>
      <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
  </nav>

  <!-- ====================== PAGE CONTENT ====================== -->
  <main class="page-content">
    <!-- Your existing page content goes here -->
  </main>

</body>
</html>