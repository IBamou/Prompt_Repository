<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Prompts Manager') ?></title>
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <link rel="stylesheet" href="app/view/css/categories.css">
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
    <div class="nav-center">
    <div class="nav-search">
        <form action="categories" method="get">   <!-- point to your real search route -->
        <input type="text" name="search" placeholder="Search categories..." autocomplete="off" value="<?=$search?? ''?>">
        </form>
    </div>
    </div>


    <!-- Right‑side actions (example) -->
    <div class="nav-actions">
      <a href="profile" class="btn btn-secondary">Profile</a>
      <a href="dashboard" class="btn btn-secondary">Dashboard</a>
      <a href="categories" class="btn btn-secondary">Categories</a>
            <a href="prompts" class="btn btn-secondary">Prompts</a>

                  <?php if($isAdmin): ?>
        <a href="users" class="btn btn-secondary">Users</a>
      <?php endif; ?>

      <form action="auth" method="post">
            <button type="submit" name="action" value="logout" class="btn btn-secondary">Logout</button>
      </form>
    </div>
  </nav>

  <!-- ====================== PAGE CONTENT ====================== -->
    <main>
        <div class="page-header">
            <h1><?= $title ?></h1>
            <div class="action-buttons">
                <?php if($isAdmin): ?>
                <form action="categories" method="post">
                    <button type="submit" name="action" value="addCategory" class="btn btn-secondary">
                        + Add Category
                    </button>
                </form>
                <?php endif; ?>
                <form action="promptCategory" method="post">
                    <input type="hidden" name="from" value="categories">
                    <button type="submit" name="action" value="addPrompt" class="btn btn-primary">
                        + Add Prompt
                    </button>
                </form>
            </div>
        </div>

        <?php if (!empty($categories)): ?>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <h3><?= htmlspecialchars($category['name']) ?></h3>
                        <?php if (!empty($category['description'])): ?>
                            <p><?= htmlspecialchars($category['description']) ?></p>
                        <?php endif; ?>

                        <div class="card-actions">
                            <form action="promptCategory" method="post">
                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($category['name']) ?>">
                                <input type="hidden" name="description" value="<?= htmlspecialchars($category['description'] ?? '') ?>">
                                <button type="submit" name="showCategory" value="on" class="btn btn-view">View Prompts</button>
                            </form>
                            <?php if($isAdmin): ?>
                            <?php if ($category['id'] != 1): ?>
                                <form action="categories" method="post">
                                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($category['name']) ?>">
                                    <input type="hidden" name="description" value="<?= htmlspecialchars($category['description'] ?? '') ?>">
                                    <button type="submit" name="action" value="editCategory" class="btn btn-edit">Edit</button>
                                </form>

                                <form action="categories" method="post">
                                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                    <button type="submit" name="operation" value="deleteCategory" class="btn btn-delete"
                                            onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                                </form>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No categories found.</p>
            </div>
        <?php endif; ?>
    </main>
    <script>
        document.addEventListener('')= 
        function hi() {
            console.log('hi');
        }
    </script>
</body>
</html>
