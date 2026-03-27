<?php
include 'app/model/CategoryModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}

$user = $_SESSION['user'];
$isAdmin = ($user['role'] == 'admin' || $user['role'] == 'superAdmin');
$isSuperAdmin = ($user['role'] == 'superAdmin');

$title = 'Categories';

$errors = [];

function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

function validateCategoryName($name, $excludeId = null) {
    if (empty($name)) {
        return "Category name cannot be empty.";
    }
    if (strlen($name) > 100) {
        return "Category name cannot exceed 100 characters.";
    }
    if (categoryNameExists($name, $excludeId)) {
        return "A category with this name already exists.";
    }
    return null;
}

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
            $name = cleanInput($_POST['name']);
            $info['name'] = $name;
            $error = validateCategoryName($name);
            if ($error) {
                $_SESSION['error'] = $error;
            } else {
                addCategory($info);
                $_SESSION['success'] = "Category added successfully.";
            }
            break;
        case 'editCategory':
            $name = cleanInput($_POST['name']);
            $info['name'] = $name;
            $error = validateCategoryName($name, $info['id']);
            if ($error) {
                $_SESSION['error'] = $error;
            } elseif (checkCategoryUpdate($info)) {
                updateCategory($info);
                $_SESSION['success'] = "Category updated successfully.";
            }
            break;
        case 'deleteCategory':
            if ($info['id'] == 1) {
                $_SESSION['error'] = "Cannot delete the default 'Uncategorized' category.";
            } else {
                deleteCategory($info);
                $_SESSION['success'] = "Category deleted successfully.";
            }
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


