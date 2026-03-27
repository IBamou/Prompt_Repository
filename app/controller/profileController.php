<?php 
include 'app/model/userModel.php';

if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}

$errors = [];
$success = [];

function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'updateProfile':
            $name = cleanInput($_POST['name']);
            $email = cleanInput($_POST['email']);
            
            if (empty($name)) {
                $errors[] = "Name cannot be empty.";
            } elseif (strlen($name) > 100) {
                $errors[] = "Name cannot exceed 100 characters.";
            }
            
            if (empty($email)) {
                $errors[] = "Email cannot be empty.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Please enter a valid email address.";
            }
            
            if (empty($errors) && emailExistsForOther($email, $_SESSION['user']['id'])) {
                $errors[] = "This email is already in use by another user.";
            }
            
            if (empty($errors)) {
                $result = updateUserProfile($_SESSION['user']['id'], $name, $email);
                if ($result['success']) {
                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['email'] = $email;
                    $success[] = "Profile updated successfully.";
                } else {
                    $errors[] = $result['error'];
                }
            }
            break;
            
        case 'changePassword':
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if (empty($currentPassword)) {
                $errors[] = "Current password is required.";
            }
            
            if (empty($newPassword)) {
                $errors[] = "New password is required.";
            } elseif (strlen($newPassword) < 6) {
                $errors[] = "New password must be at least 6 characters.";
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors[] = "New passwords do not match.";
            }
            
            if (empty($errors)) {
                $query = 'SELECT password FROM users WHERE id = :id';
                $stmt = $GLOBALS['db']->prepare($query);
                $stmt->execute([':id' => $_SESSION['user']['id']]);
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$userData || !password_verify($currentPassword, $userData['password'])) {
                    $errors[] = "Current password is incorrect.";
                } else {
                    $result = updateUserPassword($_SESSION['user']['id'], $newPassword);
                    if ($result['success']) {
                        $success[] = "Password changed successfully.";
                    } else {
                        $errors[] = $result['error'];
                    }
                }
            }
            break;
    }
}

$user = $_SESSION["user"];
$title = 'Profile';

$isAdmin = ($user['role'] == 'admin' || $user['role'] == 'superAdmin');
$isSuperAdmin = ($user['role'] == 'superAdmin');

include 'app/view/profileView.php';
?>