<?php
$showSearch = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prompt Logs — Activity Timeline</title>
  <link rel="stylesheet" href="app/view/css/navbar.css">
  <link rel="stylesheet" href="app/view/css/promptsLogs.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="app-navbar">
    <div class="nav-brand">Prompts Manager</div>

    <div class="nav-actions">
      <a href="profile.php" class="btn btn-secondary">Profile</a>
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

  <!-- Page -->
  <main class="logs-page">

    <!-- Hero -->
    <section class="logs-hero">
      <h1>📊 Prompt Activity Logs</h1>
      <p>Complete timeline of all prompt changes and actions</p>
    </section>

    <!-- Filters -->
    <section class="logs-filters">
      <form action="promptsLogs" method="get">
        <div class="filters-row">
          <!-- Action Filter -->
          <div class="filter-group">
            <label for="filterAction">Action</label>
            <select name="action" id="filterAction">
              <option value="">All Actions</option>
              <option value="CREATE" <?= (isset($_GET['action']) && $_GET['action'] == 'CREATE') ? 'selected' : '' ?>>Create</option>
              <option value="UPDATE" <?= (isset($_GET['action']) && $_GET['action'] == 'UPDATE') ? 'selected' : '' ?>>Update</option>
              <option value="DELETE" <?= (isset($_GET['action']) && $_GET['action'] == 'DELETE') ? 'selected' : '' ?>>Delete</option>
            </select>
          </div>

          <!-- User Filter
          <div class="filter-group">
            <label for="filterUser">User</label>
            <select name="user_id" id="filterUser">
              <option value="">All Users</option>
              <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                  <option value="<?= $user['id'] ?>"
                          <?= (isset($_GET['user_id']) && $_GET['user_id'] == $user['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['name']) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div> -->

          <!-- Prompt Filter -->
          <div class="filter-group">
            <label for="filterPrompt">Prompt ID</label>
            <input type="number" name="prompt_id" id="filterPrompt" placeholder="e.g. 123"
                   value="<?= htmlspecialchars($_GET['prompt_id'] ?? '') ?>">
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
            </select>
          </div>

          <!-- Buttons -->
          <button type="submit" class="btn-apply">Apply Filters</button>
          <button type="button" class="btn-clear" onclick="window.location.href='promptsLogs'">Clear</button>
        </div>
      </form>
    </section>

    <!-- Timeline -->
    <?php if (!empty($logs)): ?>
      <section class="logs-timeline">
        <?php foreach ($logs as $log): ?>
          <article class="log-item">
            <div class="log-header">
              <div class="log-action">
                <div class="log-icon <?= strtolower($log['action']) ?>">
                  <?php
                    $icons = ['CREATE' => '➕', 'UPDATE' => '✏️', 'DELETE' => '🗑️'];
                    echo $icons[$log['action']] ?? '📝';
                  ?>
                </div>
                <div class="log-action-text">
                  <div class="log-action-type">
                    <?= htmlspecialchars($log['action']) ?> 
                    <?php if ($log['field_name']): ?>
                      — <?= htmlspecialchars($log['field_name']) ?>
                    <?php endif; ?>
                  </div>
                  <div class="log-user">
                    by <?= htmlspecialchars($log['username']) ?> (ID: <?= $log['user_id'] ?>)
                  </div>
                </div>
              </div>
              <div class="log-time">
                <?= date('M d, Y • h:i A', strtotime($log['created_at'])) ?>
              </div>
            </div>

            <div class="log-details">
              <div class="log-detail-row">
                <span class="log-detail-label">Prompt ID:</span>
                <span class="log-detail-value">#<?= $log['prompt_id'] ?></span>
              </div>

              <?php if ($log['field_name']): ?>
                <div class="log-detail-row">
                  <span class="log-detail-label">Field:</span>
                  <span class="log-detail-value"><?= htmlspecialchars($log['field_name']) ?></span>
                </div>
              <?php endif; ?>

              <?php if ($log['old_value']): ?>
                <div class="log-detail-row">
                  <span class="log-detail-label">Old Value:</span>
                  <span class="log-detail-value log-detail-old"><?= htmlspecialchars($log['old_value']) ?></span>
                </div>
              <?php endif; ?>

              <?php if ($log['new_value']): ?>
                <div class="log-detail-row">
                  <span class="log-detail-label">New Value:</span>
                  <span class="log-detail-value log-detail-new"><?= htmlspecialchars($log['new_value']) ?></span>
                </div>
              <?php endif; ?>
            </div>

            <?php if ($log['message']): ?>
              <div class="log-message">
                💬 <?= htmlspecialchars($log['message']) ?>
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </section>

    <?php else: ?>
      <section class="logs-empty">
        <div class="logs-empty-icon">📋</div>
        <h3>No logs found</h3>
        <p>No activity matches your current filters.</p>
      </section>
    <?php endif; ?>

  </main>

</body>
</html>