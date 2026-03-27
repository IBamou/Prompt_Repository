<?php 
include 'app/model/promptModel.php';
include 'app/model/userModel.php';
include 'app/model/CategoryModel.php';
include 'app/model/staticsModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
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
$isAdmin = $user['role'] == 'admin';
$userId = $user['id'];
include 'app/view/promptsView.php';
exit;