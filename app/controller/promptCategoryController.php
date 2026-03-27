<?php
include 'app/model/promptModel.php';
include 'app/model/CategoryModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}




if (isset($_POST["showCategory"])) {
    $_SESSION["id"] = $_POST["id"];
    $_SESSION["name"] = $_POST["name"];
    $_SESSION["description"] = $_POST["description"];
}

$user = $_SESSION['user'];
$isAdmin = ($user['role'] == 'admin');
$userId = $user['id'];

$categoryId = $_SESSION["id"] ?? '';
$categoryName = $_SESSION["name"] ?? '';
$categoryDescription = $_SESSION["description"] ?? '';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $prompts = searchPrompt($search, $categoryId);
} else {
    $prompts = getPromptsByCategory($categoryId);
}

$categories = getCategories();
$validCategories = array_filter($categories, function ($category) {
    return $category['id'] !== 1;
});

// Handle actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'addPrompt':
            $categories = getCategories();
            $categoryId = $_POST['category_id'] ?? '';
            $fromCategoriesPage = $_POST['fromCategoriesPage'] ?? '';
            include 'app/view/promptFormView.php';
            exit;
        case 'editPrompt':
            $promptId = $_POST['id'];
            $promptTitle = $_POST['title'];
            $promptContent = $_POST['content'];
            $categoryId = $_POST['category_id'];
            $categories = getCategories();
            $isEditing = true;
            include 'app/view/promptFormView.php';
            exit;
    }
    header('Location: promptCategory');
    exit;
}
// Handle operations
if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];
    $info = [
        'id'=> $_POST['id'] ?? '',
        'title'=> $_POST['title'] ?? '',
        'content'=> $_POST['content'] ?? '',
        'category_id'=> $_POST['category_id'] ?? '',
        'user_id' => $_SESSION['user']['id'] ?? ''
    ];
    switch ($operation) {
        case 'addPrompt':
            addPrompt($info);
            $prompt_id = $GLOBALS['db']->lastInsertId();
            addPromptLog($_SESSION['user']['id'], $_SESSION['user']['name'], $prompt_id, 'CREATE', null, null, null, 'Prompt added');
            break;
        case 'editPrompt':
            $changes = getPromptChanges($info);
            if (!empty($changes)) {
                foreach ($changes as $change) {
                    addPromptLog(
                        $_SESSION['user']['id'],
                        $_SESSION['user']['name'],
                        $info['id'],
                        'UPDATE',
                        $change['field'],
                        $change['old'],
                        $change['new'],
                        "Prompt updated, updated field '{$change['field']}'"
                    );        
                }
                updatePrompt($info);
                }
            break;
        case 'deletePrompt':
            deletePrompt($info);
            addPromptLog($_SESSION['user']['id'], $_SESSION['user']['name'], $info['id'], 'DELETE', null, null, null, 'Prompt deleted');
            break;
        case 'uncategorizePrompt':
            uncategorizePrompt($info);
            addPromptLog($_SESSION['user']['id'], $_SESSION['user']['name'], $info['id'], 'UPDATE', 'category', categoryName($categoryId), categoryName(1), 'Prompt uncategorized');
            break;
        case 'addPromptToCategory':
            addPromptToCategory($info);
            addPromptLog($_SESSION['user']['id'], $_SESSION['user']['name'], $info['id'], 'UPDATE', 'category', categoryName($categoryId), categoryName($info['category_id']), "Prompt added to " . categoryName($info['category_id']) . " category");
            break;

    }
    if (isset($_POST['from'])) {
        header('Location: ' . $_POST['from']);
        exit;
    }

    header('Location: promptCategory');
    exit;
}




include 'app/view/promptCategoryView.php';
exit;