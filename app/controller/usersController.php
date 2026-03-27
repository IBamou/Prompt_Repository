<?php 
include 'app/model/userModel.php';
include 'app/model/staticsModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}
if ($_SESSION['user']['role'] != 'superAdmin') {
    header('Location: dashboard');
    exit;
}

$success = isset($_SESSION['success']) ? (is_array($_SESSION['success']) ? $_SESSION['success'] : [$_SESSION['success']]) : [];
$error = isset($_SESSION['error']) ? (is_array($_SESSION['error']) ? $_SESSION['error'] : [$_SESSION['error']]) : [];
unset($_SESSION['success'], $_SESSION['error']);

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $role = $_GET['role'] ?? null;
    $status = $_GET['status'] ?? null;
    $users = getSearchUsers($search, $role, $status);
    
} else {
    $users = getUsersAllInfo();
}

if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];
    $user_id = (int)$_POST['user_id'];

    if ($user_id === $_SESSION['user']['id']) {
        $_SESSION['error'] = "You cannot modify your own account.";
        header('Location: users');
        exit;
    }

    switch ($operation) {
        case 'makeAdmin':
            if (makeAdmin($user_id)) {
                $_SESSION['success'] = "User promoted to admin successfully.";
            } else {
                $_SESSION['error'] = "Failed to promote user to admin.";
            }
            break;
        case 'revokeAdmin':
            if (revokeAdmin($user_id)) {
                $_SESSION['success'] = "Admin privileges revoked successfully.";
            } else {
                $_SESSION['error'] = "Failed to revoke admin privileges.";
            }
            break;
        case 'blockUser':
            if (blockUser($user_id)) {
                $_SESSION['success'] = "User blocked successfully.";
            } else {
                $_SESSION['error'] = "Failed to block user.";
            }
            break;
        case 'unblockUser':
            if (unblockUser($user_id)) {
                $_SESSION['success'] = "User unblocked successfully.";
            } else {
                $_SESSION['error'] = "Failed to unblock user.";
            }
            break;
    }
    header('Location: users');
    exit;
}

$totalUsers = totalUsers();
$adminCount = totalAdmins();
$blockedCount = totalBlockedUsers();
$user = $_SESSION['user'];
$isAdmin = ($user['role'] == 'admin' || $user['role'] == 'superAdmin');
$isSuperAdmin = ($user['role'] == 'superAdmin');

include 'app/view/users.php';
exit;