<?php 
include 'app/model/userModel.php';
include 'app/model/staticsModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}
if ($_SESSION['user']['role'] != 'admin') {
    header('Location: dashboard');
    exit;
}
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
    $user_id = $_POST['user_id'];

    switch ($operation) {
        case 'makeAdmin':
            makeAdmin($user_id);
            break;
        case 'revokeAdmin':
            revokeAdmin($user_id);
            break;
        case 'blockUser':
            blockUser($user_id);
            break;
        case 'unblockUser':
            unblockUser($user_id);
            break;
    }
}

$totalUsers = totalUsers();
$adminCount = totalAdmins();
$blockedCount = totalBlockedUsers();
$user = $_SESSION['user'];
$isAdmin = $user['role'] == 'admin';
// $users = getUsersAllInfo();


include 'app/view/users.php';
exit;