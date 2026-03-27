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
  <title><?= htmlspecialchars($title ?? 'Prompts Manager') ?></title>
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <link rel="stylesheet" href="app/view/css/promptForm.css">

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

        <?php if($isAdmin): ?>
        <a href="users" class="btn btn-secondary">Users</a>
      <?php endif; ?>
      <form action="auth" method="post">
        <button type="submit" name="action" value="logout" class="btn btn-secondary">Logout</button>
      </form>
    </div>
  </nav>

   <!-- ====================== PAGE CONTENT ====================== -->
    <main class="prompt-form-page">
        <div class="prompt-form-card">
            <h1><?= $isEditing ? 'Edit Prompt' : 'Add Prompt' ?></h1>

            <form action="promptCategory" method="post">
                <?php if (isset($_POST['from']) ) : ?>
                <input type="hidden" name="from" value="<?= htmlspecialchars($_POST['from'] ?? '') ?>">
                <?php endif; ?>

                <input type="hidden" name="id" value="<?= $isEditing ? $promptId : '' ?>">

                <!-- Title -->
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title"
                           value="<?= htmlspecialchars($promptTitle ?? '') ?>"
                           placeholder="Prompt Title" required>
                </div>

                <!-- Content -->
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content"
                              placeholder="Write your prompt content here..." required><?= htmlspecialchars($promptContent ?? '') ?></textarea>
                </div>

                <!-- Category (conditional) -->
                <?php if (!$categoryId || $isEditing) :?>
                <div class="form-group">
                    <label for="category_id">Category:</label>
                    <select name="category_id" id="category_id">
                        <option value="" disabled <?= !$isEditing ? 'selected' : '' ?>>Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"
                                <?= $isEditing && $categoryId == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else :?>
                    <!-- Hidden category when adding inside a specific category -->
                    <input type="hidden" name="category_id" value="<?= $categoryId ?>">
                <?php endif; ?>

                <!-- Submit -->
                <button type="submit" name="operation"
                        value="<?= $isEditing ? 'editPrompt' : 'addPrompt' ?>"
                        class="btn-submit">
                    <?= $isEditing ? 'Update Prompt' : 'Add Prompt' ?>
                </button>
            </form>
        </div>
    </main>
</body>
</html>