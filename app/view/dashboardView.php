<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <link rel="stylesheet" href="app/view/css/dashboardstyle.css">
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
      <?php if($isSuperAdmin): ?>
        <a href="users" class="btn btn-secondary">Users</a>
      <?php endif; ?>
      <form action="auth" method="post">
        <button type="submit" name="action" value="logout" class="btn btn-secondary">Logout</button>
      </form>
    </div>
  </nav>

  <!-- ====================== DASHBOARD CONTENT ====================== -->
  <main class="dashboard-page">
    <div class="dashboard-container">

      <!-- Page Header -->
      <header class="dashboard-header">
        <h1>Dashboard</h1>
        <!-- Optional: greeting or date here -->
      </header>

      <!-- Stats Grid -->
      <section class="stats-grid">
        <div class="stat-card">
          <div class="stat-label">Total Prompts</div>
          <div class="stat-value"><?= $totalPrompts ?></div>
          <div class="stat-subtext">+<?= $promptsThisMonth ?> this mounth</div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Categories</div>
          <div class="stat-value"><?= $totalCategories?></div>
          <div class="stat-subtext">+<?=$addedRecently ?? 2?> added recently</div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Total Users</div>
          <div class="stat-value"><?= $totalUsers ?></div>
          <div class="stat-subtext"></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Most active Contributor</div>
          <div class="stat-value"><?= $mostActiveUser ?></div>
          <div class="stat-subtext"></div>
        </div>

  <!-- <div class="stat-card">
          <div class="stat-label">Most Used Prompt</div>
          <div class="stat-value" style="font-size: 1.3rem;"><?= htmlspecialchars($topPrompt ?? 'Marketing Email') ?></div>
          <div class="stat-subtext">Used 34 times</div>
        </div> -->

  <!-- <div class="stat-card">
          <div class="stat-label">This Month</div>
          <div class="stat-value"><?= $promptsThisMonth ?? 23 ?></div>
          <div class="stat-subtext">Prompts created</div>
        </div> -->
        </section>
              <!-- Quick Actions -->
      <section class="quick-actions">
          <?php if($isSuperAdmin || $isAdmin): ?>
                <form action="categories" method="post">
                    <input type="hidden" name="from" value="dashboard">
                    <button type="submit" name="action" value="addCategory" class="btn btn-secondary">
                        + Add Category
                    </button>
                </form>
                <?php endif; ?>
                <form action="promptCategory" method="post">
                    <input type="hidden" name="from" value="dashboard">
                    <button type="submit" name="action" value="addPrompt" class="btn btn-primary">
                        + Add Prompt
                    </button>
                </form>
        <a href="prompts" class="btn btn-secondary">View All Prompts</a>
        <a href="promptsLogs" class="btn btn-secondary">Prompts Logs</a>
      </section>

        <section class="recent-section">
  <div class="recent-header">
    <h2>Recent Prompts</h2>
    <a href="prompts">View All</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Category</th>
        <th>User</th>
        <th>Actions</th>
      </tr>
    </thead>

    <tbody>
      <?php if (!empty($recentPrompts)): ?>
        <?php foreach ($recentPrompts as $prompt): ?>
          <tr>
            <td>
              <span class="recent-item-title">
                <?= htmlspecialchars($prompt['title']) ?>
              </span>
            </td>

            <td>
              <form action="promptCategory" method="post">
                <input type="hidden" name="id" value="<?= $prompt['category_id']?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($prompt['category_name']) ?>">
                <input type="hidden" name="description" value="<?= htmlspecialchars($prompt['category_description'] ?? '') ?>">
                <span class="recent-item-category" >
                <button type="submit" name="showCategory" value="on" class="btn btn-view" title="Go To Category"><?= htmlspecialchars($prompt['category_name']) ?></button>
                </span>
              </form>
            </td>

            <td>
              <span class="recent-item-category">
                <?= htmlspecialchars($prompt['creator']) ?>
              </span>
            </td>

            <td class="table-actions-cell">
              <div class="recent-item-actions">

                <?php if ($userId == $prompt['user_id'] || $isSuperAdmin): ?>
                  <!-- EDIT -->
                  <form action="promptCategory" method="post">
                    <input type="hidden" name="id" value="<?= $prompt['id'] ?>">
                    <input type="hidden" name="title" value="<?= htmlspecialchars($prompt['title']) ?>">
                    <input type="hidden" name="content" value="<?= htmlspecialchars($prompt['content']) ?>">
                    <input type="hidden" name="from" value="dashboard">
                    <input type="hidden" name="category_id" value="<?= $prompt['category_id'] ?>">
                    <button type="submit" name="action" value="editPrompt" class="btn btn-edit">Edit</button>
                  </form>

                  <!-- DELETE -->
                  <form action="promptCategory" method="post" onsubmit="return confirm('Delete this prompt?');">
                    <input type="hidden" name="from" value="dashboard">
                    <input type="hidden" name="id" value="<?= $prompt['id'] ?>">
                    <button type="submit" name="operation" value="deletePrompt" class="btn btn-delete">Delete</button>
                  </form>
                <?php endif; ?>

                <!-- DETAILS -->
                <button type="button" class="btn btn-details"
                        onclick="openDetailsModal(<?= $prompt['id'] ?>)">
                  Details
                </button>

                <!-- LOGS -->
                <form action="promptsLogs" method="get">
                <input type="hidden" name="prompt_id" value="<?= $prompt['id'] ?>">
                <button class="btn btn-logs" type="submit">
                Logs
              </button>
              </form>

              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="table-empty-row">
          <td colspan="4">
            No recent prompts yet.
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
      <?php if (!empty($recentPrompts)): ?>
        <?php foreach ($recentPrompts as $prompt): ?>
                    <!-- ✅ DETAILS MODAL — ONE PER PROMPT, FULLY POPULATED BY YOUR BACKEND -->
          <div id="details-modal-<?= $prompt['id'] ?>" class="modal-overlay">
              <div class="modal">
                  <div class="modal-header">
                      <h2>Prompt Details</h2>
                      <button type="button" class="modal-close"
                              onclick="closeDetailsModal(<?= $prompt['id'] ?>)">&times;</button>
                  </div>

                  <div class="modal-body">
                      <!-- Title -->
                      <div class="modal-details-title">
                          <?= htmlspecialchars($prompt['title']) ?>
                      </div>

                      <!-- Meta: Category + Created at -->
                      <div class="modal-details-meta">
                          <span>
                              <strong>Category:</strong>
                              <?= htmlspecialchars($prompt['category_name']) ?>
                          </span>
                          <span>
                              <strong>Added by:</strong>
                              <?= htmlspecialchars($prompt['creator']) ?>
                          </span>
                          <span>
                              <strong>Created at:</strong>
                              <?= htmlspecialchars($prompt['created_at'] ?? '') ?>
                          </span>
                          <span>
                              <strong>Prompt ID:</strong>
                              <?= htmlspecialchars($prompt['id'] ?? '') ?>
                          </span>
                      </div>

                      <!-- Content (scrolls if too long) -->
                      <div class="modal-details-content">
                          <?= nl2br(htmlspecialchars($prompt['content'])) ?>
                      </div>
                  </div>

                  <div class="modal-footer">
                      <!-- ✅ COPY BUTTON — copies the content text -->
                      <button type="button" class="btn btn-copy"
                              onclick="copyPromptContent(<?= $prompt['id'] ?>)">
                          Copy Content
                      </button>

                      <button type="button" class="btn btn-secondary"
                              onclick="closeDetailsModal(<?= $prompt['id'] ?>)">
                          Close
                      </button>
                  </div>
              </div>
          </div>
        <?php endforeach;?>
      <?php endif;  ?>
    </div>
  </main>
  <script>
    /* ——— NEW: Details modal — JS ONLY OPENS / CLOSES / COPIES ——— */
        function openDetailsModal(id) {
            document.getElementById('details-modal-' + id).classList.add('active');
        }

        function closeDetailsModal(id) {
            document.getElementById('details-modal-' + id).classList.remove('active');
        }

        // ✅ Copy the prompt content to clipboard
        function copyPromptContent(id) {
            const modal = document.getElementById('details-modal-' + id);
            const contentDiv = modal.querySelector('.modal-details-content');
            const textToCopy = contentDiv.innerText; // plain text, no HTML tags

            navigator.clipboard.writeText(textToCopy).then(() => {
                const copyBtn = modal.querySelector('.btn-copy');
                // Visual feedback: turn green + show "Copied!"
                copyBtn.classList.add('copied');
                const originalText = copyBtn.textContent;
                copyBtn.textContent = 'Copied!';

                setTimeout(() => {
                    copyBtn.classList.remove('copied');
                    copyBtn.textContent = originalText;
                }, 1600); // reset after 1.6s
            });
        }

        // Close details modal when clicking backdrop or pressing Escape
        document.addEventListener('click', function(e) {
            // If the click target is an overlay that is active, close it
            if (e.target.classList.contains('modal-overlay') && e.target.classList.contains('active')) {
                // Find the ID from the overlay (e.g., details-modal-123)
                const id = e.target.id.replace('details-modal-', '');
                if (id) closeDetailsModal(id);
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any open details modal
                const openModals = document.querySelectorAll('.modal-overlay.active');
                openModals.forEach(modal => {
                    if (modal.id.startsWith('details-modal-')) {
                        const id = modal.id.replace('details-modal-', '');
                        closeDetailsModal(id);
                    }
                });
            }
        });
  </script>
</body>
</html>