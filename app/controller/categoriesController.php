<?php
include 'app/model/categoryModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}

$user = $_SESSION['user'];
$isAdmin = $user['role'] == 'admin';
$title = 'Categories';

// Handle actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'addCategory':
            include 'app/view/categoryFormView.php';
            exit;
        case 'editCategory':
            $categoryId = $_POST['id'];
            $categoryName = $_POST['name'];
            $categoryDescription = $_POST['description'];
            $isEditing = true;
            include 'app/view/categoryFormView.php';
            exit;
    }
    header('Location: categories');
    exit;
}
// Handle operations
if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];
    $info = [
        'id'=> $_POST['id'] ?? '',
        'name'=> $_POST['name'] ?? '',
        'description'=> $_POST['description'] ?? ''
    ];
    switch ($operation) {
        case 'addCategory':
            addCategory($info);
            break;
        case 'editCategory':
            if (checkCategoryUpdate($info)) {
                updateCategory($info);
            }
            break;
        case 'deleteCategory':
            deleteCategory($info);
            break;
    }
    if (isset($_POST['from'])) {
        header('Location: ' . $_POST['from']);
        exit;
    }
    header('Location: categories');
    exit;
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $categories = searchCategories($search);
} else {
    $categories = getCategories();
}



include 'app/view/categoriesView.php';
exit;


