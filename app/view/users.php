<?php
$showSearch = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users Management — Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <link rel="stylesheet" href="app/view/css/usersstyles.css">
</head>
<body>

  <!-- Navbar -->
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

  <!-- Page -->
  <main class="users-page">

    <?php if (!empty($error)): ?>
      <?php foreach ($error as $msg): ?>
        <div class="alert alert-error"><?= htmlspecialchars($msg) ?></div>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <?php foreach ($success as $msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Hero -->
    <section class="users-hero">
      <h1>👥 Users Management</h1>
      <p>Manage user roles, permissions, and account status</p>
      
      <div class="users-stats">
        <div class="stat-item">
          <div class="stat-value"><?= $totalUsers ?? 24 ?></div>
          <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-item">
          <div class="stat-value"><?= $adminCount ?? 3 ?></div>
          <div class="stat-label">Admins</div>
        </div>
        <div class="stat-item">
          <div class="stat-value"><?= $blockedCount ?? 2 ?></div>
          <div class="stat-label">Blocked</div>
        </div>
      </div>
    </section>

    <!-- Search & Filter -->
    <section class="users-search-bar">
      <form action="users" method="get" class="search-box">
        
      <div class="search-box">
        <input type="text" id="searchUsers" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search by name or email...">
      </div>

      <select class="filter-select" id="filterRole" name="role">
        <option value="">All Roles</option>
        <option value="admin">Admins Only</option>
        <option value="user">Users Only</option>
      </select>

      <select class="filter-select" id="filterStatus" name="status">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="blocked">Blocked</option>
      </select>
                <button type="submit" class="btn-apply-filters" >Apply Filters</button>
          <button type="button" class="btn-clear-filters" onclick="clearFilters()">Clear</button>
      </form>
    </section>

    <!-- Users Grid -->
    <?php if (!empty($users)): ?>
      <section class="users-grid">
        <?php foreach ($users as $user): ?>
          <article class="user-card">
            <div class="user-card-header">
              <div class="user-avatar-large">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
              </div>
              <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($user['name']) ?></div>
                <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
              </div>
            </div>

            <!-- Badges -->
            <div class="user-badges">
              <?php if ($user['role'] === 'admin'): ?>
                <span class="badge badge-admin">Admin</span>
              <?php elseif ($user['role'] === 'superAdmin'): ?>
                <span class="badge badge-admin">Super Admin</span>
              <?php else: ?>
                <span class="badge badge-user">User</span>
              <?php endif; ?>

              <?php if ($user['status'] === 'blocked'): ?>
                <span class="badge badge-blocked">Blocked</span>
              <?php else: ?>
                <span class="badge badge-active">Active</span>
              <?php endif; ?>
            </div>

            <!-- Stats -->
            <div class="user-stats">
              <div class="user-stat-item">
                <div class="user-stat-label">Prompts</div>
                <div class="user-stat-value"><?= $user['prompts_count'] ?? 0 ?></div>
              </div>
              <div class="user-stat-item">
                <div class="user-stat-label">Joined</div>
                <div class="user-stat-value"><?= date('M Y', strtotime($user['created_at'])) ?></div>
              </div>
              <div class="user-stat-item">
                <div class="user-stat-label">Last Login</div>
                <div class="user-stat-value"><?= $user['last_login'] ? date('M d', strtotime($user['last_login'])) : 'Never' ?></div>
              </div>
              <div class="user-stat-item">
                <div class="user-stat-label">User ID</div>
                <div class="user-stat-value">#<?= $user['id'] ?></div>
              </div>
            </div>

            <!-- Actions -->
            <div class="user-actions">
              <?php if ($user['role'] !== 'admin'): ?>
                <button class="btn-action btn-admin" onclick="openAdminModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>')">
                  Make Admin
                </button>
              <?php else: ?>
                <button class="btn-action btn-revoke" onclick="openRevokeModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>')">
                  Revoke Admin
                </button>
              <?php endif; ?>

              <?php if ($user['status'] !== 'blocked'): ?>
                <button class="btn-action btn-block" onclick="openBlockModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>')">
                  Block User
                </button>
              <?php else: ?>
                <button class="btn-action btn-unblock" onclick="openUnblockModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>')">
                  Unblock
                </button>
              <?php endif; ?>

              <button class="btn-action btn-view" onclick="window.location.href='userDetails.php?id=<?= $user['id'] ?>'">
                View Details
              </button>
            </div>
          </article>

          <!-- Hidden forms for actions -->
          <form id="admin-form-<?= $user['id'] ?>" action="users" method="post" class="hidden-form">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="operation" value="makeAdmin">
          </form>

          <form id="revoke-form-<?= $user['id'] ?>" action="users" method="post" class="hidden-form">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="operation" value="revokeAdmin">
          </form>

          <form id="block-form-<?= $user['id'] ?>" action="users" method="post" class="hidden-form">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="operation" value="blockUser">
          </form>

          <form id="unblock-form-<?= $user['id'] ?>" action="users" method="post" class="hidden-form">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="operation" value="unblockUser">
          </form>

        <?php endforeach; ?>
      </section>

    <?php else: ?>
      <section class="users-empty">
        <div class="users-empty-icon">👤</div>
        <h3>No users found</h3>
        <p>No users match your current filters.</p>
      </section>
    <?php endif; ?>

  </main>

  <!-- Confirmation Modals -->
  <div id="confirmModal" class="modal-overlay">
    <div class="modal">
      <h2 id="modalTitle">Confirm Action</h2>
      <p id="modalText">Are you sure?</p>
      <div class="modal-footer">
        <button class="btn-action btn-view" onclick="closeModal()">Cancel</button>
        <button class="btn-action btn-block" id="modalConfirmBtn" onclick="confirmAction()">Confirm</button>
      </div>
    </div>
  </div>

  <script>
    let currentAction = null;
    let currentUserId = null;

    function openAdminModal(userId, userName) {
      currentAction = 'admin';
      currentUserId = userId;
      document.getElementById('modalTitle').textContent = 'Make Admin';
      document.getElementById('modalText').textContent = `Grant admin privileges to ${userName}?`;
      document.getElementById('modalConfirmBtn').className = 'btn-action btn-admin';
      document.getElementById('confirmModal').classList.add('active');
    }

    function openRevokeModal(userId, userName) {
      currentAction = 'revoke';
      currentUserId = userId;
      document.getElementById('modalTitle').textContent = 'Revoke Admin';
      document.getElementById('modalText').textContent = `Remove admin privileges from ${userName}?`;
      document.getElementById('modalConfirmBtn').className = 'btn-action btn-revoke';
      document.getElementById('confirmModal').classList.add('active');
    }

    function openBlockModal(userId, userName) {
      currentAction = 'block';
      currentUserId = userId;
      document.getElementById('modalTitle').textContent = 'Block User';
      document.getElementById('modalText').textContent = `Block ${userName}? They won't be able to log in.`;
      document.getElementById('modalConfirmBtn').className = 'btn-action btn-block';
      document.getElementById('confirmModal').classList.add('active');
    }

    function openUnblockModal(userId, userName) {
      currentAction = 'unblock';
      currentUserId = userId;
      document.getElementById('modalTitle').textContent = 'Unblock User';
      document.getElementById('modalText').textContent = `Restore access for ${userName}?`;
      document.getElementById('modalConfirmBtn').className = 'btn-action btn-unblock';
      document.getElementById('confirmModal').classList.add('active');
    }

    function closeModal() {
      document.getElementById('confirmModal').classList.remove('active');
      currentAction = null;
      currentUserId = null;
    }

    function confirmAction() {
      if (currentAction && currentUserId) {
        document.getElementById(`${currentAction}-form-${currentUserId}`).submit();
      }
      closeModal();
    }

    document.getElementById('confirmModal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeModal();
    });

    // Search & Filters
    document.getElementById('searchUsers').addEventListener('input', function(e) {
      // TODO: Implement search
      console.log('Search:', e.target.value);
    });

    document.getElementById('filterRole').addEventListener('change', function(e) {
      // TODO: Implement role filter
      console.log('Filter role:', e.target.value);
    });

    document.getElementById('filterStatus').addEventListener('change', function(e) {
      // TODO: Implement status filter
      console.log('Filter status:', e.target.value);
    });
        function clearFilters() {
      window.location.href = 'users';
    }
  </script>

</body>
</html>