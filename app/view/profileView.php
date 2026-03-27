<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile — Prompts Manager</title>

  <!-- Load navbar CSS first -->
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <!-- Then load this page’s isolated CSS -->
  <link rel="stylesheet" href="app/view/css/profilestyle.css">
</head>
<body>

  <!-- ====================== NAVBAR (re‑usable) ====================== -->
  <nav class="app-navbar">
    <div class="nav-brand">Prompts Manager</div>

      <div class="nav-center">
        <div class="nav-search">
          <form action="search" method="get">
            <input type="text" name="q" placeholder="Search prompts, categories..." autocomplete="off">
          </form>
        </div>
      </div>

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

  <!-- ====================== PROFILE PAGE CONTENT ====================== -->
  <main class="profile-page">
    <div class="profile-container">

      <!-- Header: Avatar · Name · Role · Email · Edit Button -->
      <header class="profile-header">
        <!-- Avatar (replace src with your backend data) -->
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=4f46e5&color=fff&size=192"
             alt="User Avatar" class="profile-avatar">

        <div class="profile-info">
          <h1><?= $user['name'] ?></h1>
          <div class="profile-role"><?= $user['role'] ?></div>
          <div class="profile-email"><?= $user['email'] ?></div>
        </div>

        <!-- Edit Profile button -->
        <a href="edit-profile.php" class="btn-edit-profile">Edit Profile</a>
      </header>

      <!-- Grid of profile sections -->
      <div class="profile-grid">

        <!-- Account Information -->
        <section class="profile-card">
          <div class="profile-card-header">
            <h2>Account Information</h2>
            <a href="#">View All</a>
          </div>
          <div class="profile-list">
            <div class="profile-list-item">
              <span class="profile-list-label">Username</span>
              <span class="profile-list-value"><?= $user['name'] ?></span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Email</span>
              <span class="profile-list-value"><?= $user['email'] ?></span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Member Since</span>
              <?php $date = new DateTime($user['created_at']); ?>
              <span class="profile-list-value"><?= $date->format("M d, Y | H:i"); ?></span>
            </div>
          </div>
        </section>

        <!-- Preferences -->
        <section class="profile-card">
          <div class="profile-card-header">
            <h2>Preferences</h2>
            <a href="#">Edit</a>
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
              <span class="profile-list-label">Notifications</span>
              <span class="profile-list-value">On</span>
            </div>
          </div>
        </section>

        <!-- Recent Activity (example) -->
        <section class="profile-card" style="grid-column: 1 / -1;"> <!-- full width on wrap -->
          <div class="profile-card-header">
            <h2>Recent Activity</h2>
            <a href="#">See All</a>
          </div>
          <div class="profile-list">
            <div class="profile-list-item">
              <span class="profile-list-label">Last Login</span>
              <span class="profile-list-value">2 hours ago (Today, 10:24 AM)</span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Last Prompt Created</span>
              <span class="profile-list-value">“Marketing Email Generator” — Yesterday</span>
            </div>
            <div class="profile-list-item">
              <span class="profile-list-label">Categories Added</span>
              <span class="profile-list-value">3 new categories this week</span>
            </div>
          </div>
        </section>

      </div>
    </div>
  </main>

</body>
</html>