<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile — Prompts Manager</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <link rel="stylesheet" href="app/view/css/profilestyles.css">
</head>
<body>

  <nav class="app-navbar">
    <div class="nav-brand">Prompts Manager</div>

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

  <main class="profile-page">
    <div class="profile-container">

      <header class="profile-header">
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=4f46e5&color=fff&size=192"
             alt="User Avatar" class="profile-avatar">

        <div class="profile-info">
          <h1><?= htmlspecialchars($user['name']) ?></h1>
          <div class="profile-role"><?= htmlspecialchars(ucfirst($user['role'])) ?></div>
          <div class="profile-email"><?= htmlspecialchars($user['email']) ?></div>
        </div>
      </header>

      <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
          <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <?php foreach ($success as $msg): ?>
          <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
        <?php endforeach; ?>
      <?php endif; ?>

      <div class="profile-form-section">
        <div class="profile-grid">
          <section class="profile-form-card">
            <h3>Edit Profile</h3>
            <form action="profile" method="post">
              <input type="hidden" name="action" value="updateProfile">
              <div class="profile-form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
              </div>
              <div class="profile-form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
              </div>
              <button type="submit" class="btn-save-profile">Save Changes</button>
            </form>
          </section>

          <section class="profile-form-card">
            <h3>Change Password</h3>
            <form action="profile" method="post">
              <input type="hidden" name="action" value="changePassword">
              <div class="profile-form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
              </div>
              <div class="profile-form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required minlength="6">
              </div>
              <div class="profile-form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
              </div>
              <button type="submit" class="btn-save-profile">Change Password</button>
            </form>
          </section>
        </div>
      </div>

      <div class="profile-grid">
        <section class="profile-card">
          <div class="profile-card-header">
            <h2>Account Information</h2>
          </div>
          <div class="profile-list">
            <div class="profile-list-item">
              <span class="profile-list-label">User ID</span>
              <span class="profile-list-value">#<?= $user['id'] ?></span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Email</span>
              <span class="profile-list-value"><?= htmlspecialchars($user['email']) ?></span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Member Since</span>
              <span class="profile-list-value"><?= date("M d, Y", strtotime($user['created_at'] ?? 'now')) ?></span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Account Status</span>
              <span class="profile-list-value"><?= ucfirst($user['status'] ?? 'active') ?></span>
            </div>
          </div>
        </section>

        <section class="profile-card">
          <div class="profile-card-header">
            <h2>Preferences</h2>
          </div>
          <div class="profile-list">
            <div class="profile-list-item">
              <span class="profile-list-label">Theme</span>
              <span class="profile-list-value">Light Mode</span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Language</span>
              <span class="profile-list-value">English (US)</span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Account Type</span>
              <span class="profile-list-value"><?= ucfirst($user['role']) ?></span>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

</body>
</html>
