<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Prompts</title>
  
  <!-- Navbar CSS -->
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <!-- Page CSS -->
  <link rel="stylesheet" href="app/view/css/prompts.css">
  <style>

/* ====================== QUICK ACTIONS (Add Prompt) ====================== */

.quick-actions {
  display: flex;
  justify-content: flex-start;   /* button on the right */
  align-items: center;
  margin-bottom: 1.5rem;
}

/* Form reset (important) */
.quick-actions form {
  margin: 0;
}

/* Button */
.quick-actions .btn-primary {
  background: #4f46e5;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 10px;

  font-size: 0.95rem;
  font-weight: 600;

  display: inline-flex;
  align-items: center;
  gap: 8px;

  cursor: pointer;
  transition: all 0.2s ease;
}

/* Hover effect */
.quick-actions .btn-primary:hover {
  background: #4338ca;
  transform: translateY(-2px);
  box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.35);
}

/* Click effect */
.quick-actions .btn-primary:active {
  transform: translateY(0);
  box-shadow: 0 4px 10px -3px rgba(79, 70, 229, 0.3);
}

/* Optional: focus (keyboard accessibility) */
.quick-actions .btn-primary:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
}
  </style>
</head>
<body>

  <!-- ====================== NAVBAR ====================== -->
  <nav class="app-navbar">
    <div class="nav-brand">Prompts Manager</div>

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
  <main class="all-prompts-page">

    <!-- Hero Section -->
    <section class="prompts-hero">
      <h1>📝 All Prompts</h1>
      <p>Browse, search, and manage all your prompts in one place</p>
      
      <div class="prompts-stats">
        <div class="stat-item">
          <div class="stat-value"><?= $totalPrompts ?? 128 ?></div>
          <div class="stat-label">Total Prompts</div>
        </div>
        <div class="stat-item">
          <div class="stat-value"><?= $totalCategories ?? 14 ?></div>
          <div class="stat-label">Categories</div>
        </div>
        <div class="stat-item">
          <div class="stat-value"><?= $activeUsers ?? 8 ?></div>
          <div class="stat-label">Contributors</div>
        </div>
      </div>
    </section>
    <section  class="quick-actions">
                                           <form action="promptCategory" method="post" >
                    <input type="hidden" name="from" value="prompts">
                    <button type="submit" name="action" value="addPrompt" class="btn btn-primary">
                        + Add Prompt
                    </button>
                </form>
    </section>
    <!-- Search & Filter Bar -->
    <section class="search-filter-bar">
      <form action="prompts" method="get">
        <!-- Main Search -->
        <div class="search-main">
          <div class="search-box">
            <input type="text" name="search" placeholder="Search prompts by title, or category..."
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          </div>
 
        </div>

        <!-- Advanced Filters -->
        <div class="filters-advanced">
          <!-- Category Filter -->
          <div class="filter-group">
            <label for="filterCategory">Category</label>
            <select name="category" id="filterCategory">
              <option value="">All Categories</option>
              <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"
                          <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <!-- User Filter -->
          <div class="filter-group">
            <label for="filterUser">Added By</label>
            <select name="user" id="filterUser">
              <option value="">All Users</option>
              <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                  <option value="<?= $user['id'] ?>"
                          <?= (isset($_GET['user']) && $_GET['user'] == $user['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['name']) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <!-- Date From -->
          <div class="filter-group">
            <label for="filterDateFrom">Date From</label>
            <input type="date" name="date_from" id="filterDateFrom"
                   value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
          </div>

          <!-- Date To -->
          <div class="filter-group">
            <label for="filterDateTo">Date To</label>
            <input type="date" name="date_to" id="filterDateTo"
                   value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
          </div>

          <!-- Sort By -->
          <div class="filter-group">
            <label for="filterSort">Sort By</label>
            <select name="sort" id="filterSort">
              <option value="recent" <?= (isset($_GET['sort']) && $_GET['sort'] == 'recent') ? 'selected' : '' ?>>Most Recent</option>
              <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : '' ?>>Oldest First</option>
              <option value="title" <?= (isset($_GET['sort']) && $_GET['sort'] == 'title') ? 'selected' : '' ?>>Title (A-Z)</option>
              <option value="author" <?= (isset($_GET['sort']) && $_GET['sort'] == 'author') ? 'selected' : '' ?>>Author</option>
            </select>
          </div>

          <!-- Apply & Clear Buttons -->
          <button type="submit" class="btn-apply-filters" >Apply Filters</button>
          <button type="button" class="btn-clear-filters" onclick="clearFilters()">Clear</button>
        </div>
      </form>

      <!-- Quick Filter Chips -->
      <!-- <div class="filters-chips">
        <div class="filter-chip active" data-filter="all">All</div>
        <div class="filter-chip" data-filter="marketing">Marketing</div>
        <div class="filter-chip" data-filter="development">Development</div>
        <div class="filter-chip" data-filter="design">Design</div>
        <div class="filter-chip" data-filter="recent">Recent</div>
        <div class="filter-chip" data-filter="favorites">Favorites</div>
      </div> -->

    </section>

    <!-- Prompts Grid -->
    <?php if (!empty($prompts)): ?>
      <section class="prompts-grid">
        <?php foreach ($prompts as $prompt): ?>
          <article class="prompt-card">
            <div class="prompt-card-top">
              <div>
                <h3 class="prompt-title"><?= htmlspecialchars($prompt['title']) ?></h3>
                <span class="prompt-category">
                  <?= htmlspecialchars($prompt['category_name'] ?? 'Uncategorized') ?>
                </span>
              </div>
            </div>

            <p class="prompt-preview">
              <?= htmlspecialchars(substr($prompt['content'], 0, 150)) ?>...
            </p>

            <div class="prompt-meta">
              <div class="prompt-author">
                <div class="author-avatar">
                  <?= strtoupper(substr($prompt['creator'] ?? 'U', 0, 1) ?? '') ?>
                </div>
                <?= htmlspecialchars($prompt['creator'] ?? 'Unknown') ?>
              </div>
              <div class="prompt-date">
                <?= date('M d, Y', strtotime($prompt['created_at'] ?? '')) ?>
              </div>
            </div>

            <!-- ✅ Actions — ALWAYS VISIBLE + LOGS BUTTON -->
            <div class="prompt-actions">
              <form action="promptsLogs" method="get" style="display: flex; flex: 1;">
                <input type="hidden" name="prompt_id" value="<?= $prompt['id'] ?>">
                <button class="btn-action btn-logs" type="submit">
                Logs
              </button>
              </form>

              <button class="btn-action btn-view" 
                      onclick="openViewModal(<?= $prompt['id'] ?>, '<?= htmlspecialchars($prompt['title']??'', ENT_QUOTES) ?>', '<?= htmlspecialchars($prompt['content'], ENT_QUOTES) ?>', '<?= htmlspecialchars($prompt['category_name'] ?? 'Uncategorized', ENT_QUOTES) ?>', '<?= htmlspecialchars($prompt['creator'] ?? 'Unknown', ENT_QUOTES) ?>', '<?= htmlspecialchars($prompt['created_at'] ?? '') ?>')" >
                 Details
              </button>

                <?php if ($userId == $prompt['user_id'] || $isAdmin): ?>
                  <!-- EDIT -->
                  <form action="promptCategory" method="post" style="display: flex; flex: 1;">
                    <input type="hidden" name="id" value="<?= $prompt['id'] ?>">
                    <input type="hidden" name="title" value="<?= htmlspecialchars($prompt['title']) ?>">
                    <input type="hidden" name="content" value="<?= htmlspecialchars($prompt['content']) ?>">
                    <input type="hidden" name="from" value="prompts">
                    <input type="hidden" name="category_id" value="<?= $prompt['category_id'] ?>">
                    <button type="submit" name="action" value="editPrompt" class="btn-action btn-edit">Edit</button>
                  </form>

                  <!-- DELETE -->
                  <form action="promptCategory" method="post" onsubmit="return confirm('Delete this prompt?');" style="display: flex; flex: 1;">
                    <input type="hidden" name="from" value="prompts">
                    <input type="hidden" name="id" value="<?= $prompt['id'] ?>">
                    <button type="submit" name="operation" value="deletePrompt" class="btn-action btn-delete">Delete</button>
                  </form>
                <?php endif; ?>
            </div>
          </article>



        <?php endforeach; ?>
      </section>

    <?php else: ?>
      <section class="prompts-empty">
        <div class="prompts-empty-icon">📭</div>
        <h3>No prompts found</h3>
        <p>Try adjusting your filters or create a new prompt!</p>
      </section>
    <?php endif; ?>

  </main>

  <!-- ====================== VIEW DETAILS MODAL ====================== -->
  <div id="viewModal" class="modal-overlay">
    <div class="modal">
      <div class="modal-header">
        <h2 id="viewModalTitle">Prompt Details</h2>
        <button class="modal-close" onclick="closeViewModal()">×</button>
      </div>
      
      <div class="modal-body">
        <div class="modal-info">
          <div class="info-item">
            <div class="info-label">Category</div>
            <div class="info-value" id="viewModalCategory">—</div>
          </div>
          <div class="info-item">
            <div class="info-label">Author</div>
            <div class="info-value" id="viewModalAuthor">—</div>
          </div>
          <div class="info-item">
            <div class="info-label">Created</div>
            <div class="info-value" id="viewModalDate">—</div>
          </div>
          <div class="info-item">
            <div class="info-label">Prompt ID</div>
            <div class="info-value" id="viewModalId">—</div>
          </div>
        </div>

        <div class="modal-content" id="viewModalContent">
          Loading...
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn-action btn-view" onclick="copyContent()">📋 Copy</button>
        <button class="btn-action btn-edit" onclick="closeViewModal()">Close</button>
      </div>
    </div>
  </div>

  <!-- ====================== LOGS MODAL ====================== -->
  <div id="logsModal" class="modal-overlay">
    <div class="modal">
      <div class="modal-header">
        <h2>Prompt Logs</h2>
        <button class="modal-close" onclick="closeLogsModal()">×</button>
      </div>
      
      <div class="modal-body">
        <div id="logsContent" class="logs-list">
          <!-- ✅ TODO: Populate from backend -->
          <div class="log-item">
            <div class="log-item-date">2024-01-15 10:24 AM</div>
            Prompt created by John Doe
          </div>
          <div class="log-item">
            <div class="log-item-date">2024-01-16 2:15 PM</div>
            Prompt edited by Jane Smith — Title updated
          </div>
          <div class="log-item">
            <div class="log-item-date">2024-01-18 9:03 AM</div>
            Prompt moved to "Marketing" category by Admin
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn-action btn-edit" onclick="closeLogsModal()">Close</button>
      </div>
    </div>
  </div>

  <!-- ====================== DELETE CONFIRMATION MODAL ====================== -->
  <div id="deleteConfirmModal" class="modal-overlay">
    <div class="modal delete-confirm-modal">
      <div class="delete-confirm-title">Delete Prompt?</div>
      <div class="delete-confirm-text">
        This action cannot be undone. Are you sure you want to permanently delete this prompt?
      </div>
      <div class="delete-confirm-footer">
        <button class="btn-action btn-view" onclick="closeDeleteConfirm()">Cancel</button>
        <button class="btn-action btn-delete" onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>

  <script>
    // ——— View Details Modal ———
    function openViewModal(id, title, content, category, author, date) {
      document.getElementById('viewModalTitle').textContent = title;
      document.getElementById('viewModalContent').textContent = content;
      document.getElementById('viewModalCategory').textContent = category;
      document.getElementById('viewModalAuthor').textContent = author;
      document.getElementById('viewModalDate').textContent = new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
      document.getElementById('viewModalId').textContent = '#' + id;
      document.getElementById('viewModal').classList.add('active');
    }

    function closeViewModal() {
      document.getElementById('viewModal').classList.remove('active');
    }

    function copyContent() {
      const content = document.getElementById('viewModalContent').textContent;
      navigator.clipboard.writeText(content).then(() => {
        alert('✅ Content copied to clipboard!');
      });
    }

    // ——— Logs Modal ———
    function openLogsModal(promptId) {
      // ✅ TODO: Fetch logs via AJAX from backend
      // fetch(`getLogs.php?prompt_id=${promptId}`)...
      document.getElementById('logsModal').classList.add('active');
    }

    function closeLogsModal() {
      document.getElementById('logsModal').classList.remove('active');
    }

    // ——— Delete Confirmation ———
    let deleteTargetFormId = null;

    function openDeleteConfirm(promptId) {
      deleteTargetFormId = 'delete-form-' + promptId;
      document.getElementById('deleteConfirmModal').classList.add('active');
    }

    function closeDeleteConfirm() {
      document.getElementById('deleteConfirmModal').classList.remove('active');
      deleteTargetFormId = null;
    }

    function confirmDelete() {
      if (deleteTargetFormId) {
        document.getElementById(deleteTargetFormId).submit();
      }
      closeDeleteConfirm();
    }

    // ——— Close modals on backdrop click ———
    document.getElementById('viewModal').addEventListener('click', function(e) {
      if (e.target === this) closeViewModal();
    });
    document.getElementById('logsModal').addEventListener('click', function(e) {
      if (e.target === this) closeLogsModal();
    });
    document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteConfirm();
    });

    // ——— Close on Escape ———
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeViewModal();
        closeLogsModal();
        closeDeleteConfirm();
      }
    });

    // ——— Filter Chips ———
    document.querySelectorAll('.filter-chip').forEach(chip => {
      chip.addEventListener('click', function() {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        // ✅ TODO: Implement filter logic
      });
    });

    // ——— Clear Filters ———
    function clearFilters() {
      window.location.href = 'prompts';
    }

    // ——— Search Input ———
    document.querySelector('.search-box input').addEventListener('input', function(e) {
      // ✅ TODO: Implement live search or debounce + AJAX
      console.log('Searching:', e.target.value);
    });
  </script>

</body>
</html>