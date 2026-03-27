<?php 
include 'app/model/promptModel.php';
include 'app/model/userModel.php';
include 'app/model/CategoryModel.php';
include 'app/model/staticsModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}

$errors = [];
$success = [];

function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

function validatePromptInput($title, $content, $category_id, $excludeId = null) {
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Title cannot be empty.";
    } elseif (strlen($title) > 200) {
        $errors[] = "Title cannot exceed 200 characters.";
    }
    
    if (empty($content)) {
        $errors[] = "Content cannot be empty.";
    }
    
    if (empty($category_id)) {
        $errors[] = "Please select a category.";
    }
    
    if (empty($errors) && promptTitleExists($title, $excludeId)) {
        $errors[] = "A prompt with this title already exists.";
    }
    
    return $errors;
}

if (isset($_GET['search']) ) {
    $search = $_GET['search'];
    $category = $_GET['category'];
    $user = $_GET['user'];
    $date_from = $_GET['date_from'];
    $date_to = $_GET['date_to'];
    $sort = $_GET['sort'];
    $prompts = getSearchedPrompts($search, $category, $user, $date_from, $date_to, $sort);
} else {
    $prompts = getPromptsAllInfo();
}


$totalPrompts = totalPrompts();
$totalCategories = totalCategories();
$activeUsers = totalUsers();
$categories = getCategories();
$users = getUsers();
$user = $_SESSION['user'];
$isAdmin = ($user['role'] == 'admin' || $user['role'] == 'superAdmin');
$isSuperAdmin = ($user['role'] == 'superAdmin');

$userId = $user['id'];
include 'app/view/promptsView.php';
exit;