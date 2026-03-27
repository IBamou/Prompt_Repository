<?php
// ✅ Set this per page:
$showSearch = true;   // ← change to false on pages where you don't want the search bar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categoryName) ?></title>
    <link rel="stylesheet" href="app/view/css/promptCategorystyle.css">
    <link rel="stylesheet" href="app/view/css/navbar.css">
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
          <form action="promptCategory" method="get">   <!-- point to your real search route -->
            <input type="text" name="search" placeholder="Search prompts in category..." autocomplete="off" value="<?=$search?? ''?>">
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
        <!-- Header -->
        <div class="page-header">
            <h1><?= htmlspecialchars($categoryName) ?></h1>
            <div class="action-buttons">
             <!-- “Add Prompt” button (unchanged) -->
        
                    <form action="promptCategory" method="post">
                        <input type="hidden" name="category_id" value="<?= $categoryId ?>">
                        <button type="submit" name="action" value="addPrompt" class="btn btn-primary">
                            + Add Prompt
                        </button>
                    </form>

                    <a href="categories" class="btn btn-back">← Go Back</a>
            </div>
        </div>

        <!-- “No categories available” warning (shown by your Add‑to‑Category logic if needed) -->
        <div id="emptyCategories">
            <p>No valid categories available. Please add some categories first in <a href="categories">Categories</a>.</p>
        </div>

        <?php if (!empty($prompts)): ?>
            <div class="prompts-list">
                <?php foreach ($prompts as $prompt): ?>
                    <div class="prompt-card">
                        <h3><?= htmlspecialchars($prompt['title']) ?></h3>

                        <div class="card-actions">
                            <?php if ($userId == $prompt['user_id'] || $isAdmin): ?>
                            <!-- EDIT PROMPT -->
                            <form action="promptCategory" method="post">
                                <input type="hidden" name="id" value="<?= $prompt['id'] ?>">
                                <input type="hidden" name="title" value="<?= htmlspecialchars($prompt['title']) ?>">
                                <input type="hidden" name="content" value="<?= htmlspecialchars($prompt['content']) ?>">
                                <input type="hidden" name="category_id" value="<?= $prompt['category_id'] ?>">
                                <button type="submit" name="action" value="editPrompt" class="btn btn-edit">Edit</button>
                            </form>

                            <!-- DELETE PROMPT -->
                            <form action="promptCategory" method="post" onsubmit="return confirm('Delete this prompt?');">
                                <input type="hidden" name="id" value="<?= $prompt['id'] ?>">
                                <button type="submit" name="operation" value="deletePrompt" class="btn btn-delete">Delete</button>
                            </form>

                            <?php if ($categoryId != 1): ?>
                                <!-- UNCATEGORIZE PROMPT -->
                                <form action="promptCategory" method="post">
                                    <input type="hidden" name="id" value="<?= $prompt['id'] ?>">
                                    <button type="submit" name="operation" value="uncategorizePrompt" class="btn btn-uncategorize">Uncategorize</button>
                                </form>
                            <?php else: ?>
                                <!-- ADD TO CATEGORY (your existing modal logic — unchanged) -->
                                <button type="button" class="btn btn-toggle-category"
                                        onclick="openAddToCategoryModal(<?= $prompt['id'] ?>, <?= count($validCategories) ?>)">
                                    Add to category
                                </button>
                            <?php endif; ?>
                            <?php endif?>
                            <!-- ✅ DETAILS BUTTON — opens the pre‑filled modal below -->
                            <button type="button" class="btn btn-details"
                                    onclick="openDetailsModal(<?= $prompt['id'] ?>)">
                                Details
                            </button>
                        </div>
                    </div>

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
                                        <?= htmlspecialchars($prompt['creator'] ?? '') ?>
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

                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <p>No prompts found.</p>
            </div>
        <?php endif; ?>
    </main>
     <!-- ====================== EXISTING: “Add to Category” Modal (unchanged) ====================== -->
    <div id="addToCategoryModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2>Add Prompt to Category</h2>
                <button type="button" class="modal-close" onclick="closeAddToCategoryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="modalAddCategoryForm" action="promptCategory" method="post">
                    <input type="hidden" name="id" id="modalPromptId">
                    <select name="category_id" id="modalCategorySelect" onchange="updateModalCategoryDesc(this)">
                        <option value="" disabled selected>Select a category</option>
                        <?php foreach ($validCategories as $category): ?>
                            <option value="<?= $category['id'] ?>"
                                    data-description="<?= htmlspecialchars($category['description'] ?? '') ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="modalCategoryDesc" class="modal-category-desc"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddToCategoryModal()">Cancel</button>
                        <button type="submit" name="operation" value="addPromptToCategory" class="btn btn-primary">
                            Add to Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        /* ——— Existing: Add to Category modal logic (unchanged) ——— */
        function openAddToCategoryModal(promptId, categoryCount) {
            if (categoryCount === 0) {
                document.getElementById('emptyCategories').style.display = 'block';
                return;
            }
            document.getElementById('emptyCategories').style.display = 'none';
            document.getElementById('modalPromptId').value = promptId;
            const select = document.getElementById('modalCategorySelect');
            select.selectedIndex = 0;
            document.getElementById('modalCategoryDesc').classList.remove('visible');
            document.getElementById('modalCategoryDesc').textContent = '';
            document.getElementById('addToCategoryModal').classList.add('active');
        }
        function closeAddToCategoryModal() {
            document.getElementById('addToCategoryModal').classList.remove('active');
            const select = document.getElementById('modalCategorySelect');
            select.selectedIndex = 0;
            document.getElementById('modalCategoryDesc').classList.remove('visible');
            document.getElementById('modalCategoryDesc').textContent = '';
        }
        function updateModalCategoryDesc(selectElem) {
            const selectedOption = selectElem.options[selectElem.selectedIndex];
            const description = selectedOption.getAttribute('data-description') || '';
            const descBox = document.getElementById('modalCategoryDesc');
            if (description.trim() !== '') {
                descBox.textContent = description;
                descBox.classList.add('visible');
            } else {
                descBox.classList.remove('visible');
                descBox.textContent = '';
            }
        }
        document.getElementById('addToCategoryModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddToCategoryModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAddToCategoryModal();
        });

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
<body>
    