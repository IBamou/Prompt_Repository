<?php
include 'app/model/staticsModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}

$recentPrompts = getRecentPrompts();  

$user = $_SESSION["user"];
$userId = $user["id"];
$isAdmin = ($user['role'] == 'admin' || $user['role'] == 'superAdmin');
$isSuperAdmin = ($user['role'] == 'superAdmin');
$totalPrompts = totalPrompts();
$promptsThisMonth = promptsThisMonth();
$totalCategories = totalCategories();
$totalUsers = totalUsers();
$mostActiveUser = mostActiveUser();
$title = 'Dashboard';
include 'app/view/dashboardView.php';