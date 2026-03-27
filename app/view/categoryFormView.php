<?php 
if (!isset($isEditing)) {
    $isEditing = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars('Prompts Manager') ?></title>
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <link rel="stylesheet" href="app/view/css/categoryFormstyles.css">

  <!-- load your page‑specific CSS after, so it can override if needed -->
</head>
<body>

  <!-- ====================== NAVBAR ====================== -->
  <nav class="app-navbar">
    <!-- Brand / Title -->
    <div class="nav-brand">
      Prompts Manager
    </div>

    <!-- Right‑side actions (example) -->
    <div class="nav-actions">
      <a href="profile" class="btn btn-secondary">Profile</a>
      <a href="dashboard" class="btn btn-secondary">Dashboard</a>
      <a href="categories" class="btn btn-secondary">Categories</a>
            <a href="prompts" class="btn btn-secondary">Prompts</a>

                  <?php if($isSuperAdmin): ?>
        <a href="users" class="btn btn-secondary">Users</a>
      <?php endif; ?>
      <form action="auth" method="post">
        <button type="submit" name="action" value="logout" class="btn btn-secondary">Logout</button>
      </form>
    </div>
  </nav>

  <!-- ====================== PAGE CONTENT ====================== -->
    <main class="category-form-page">
        <div class="category-form-card">
            <h1><?= $isEditing ? 'Edit Category' : 'Add Category' ?></h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="categories" method="post">
                <?php if (isset($_POST['from'])): ?>
                    <input type="hidden" name="from" value="<?= htmlspecialchars($_POST['from']) ?>">
                <?php endif; ?>
                <input type="hidden" name="id" value="<?= $categoryId ?? '' ?>">

                <!-- Name -->
                <div class="form-group">
                    <label for="categoryName">Category Name:</label>
                    <input type="text" id="categoryName" name="name"
                           value="<?= htmlspecialchars($categoryName ?? '') ?>"
                           placeholder="Category Name" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="categoryDescription">Category Description:</label>
                    <textarea id="categoryDescription" name="description"
                              placeholder="Category Description" required><?= htmlspecialchars($categoryDescription ?? '') ?></textarea>
                </div>

                <!-- Submit -->
                <button type="submit" name="operation"
                        value="<?= $isEditing ? 'editCategory' : 'addCategory' ?>"
                        class="btn-submit">
                    <?= $isEditing ? 'Save Changes' : 'Add Category' ?>
                </button>
            </form>
        </div>
    </main>

</body>
</html>
