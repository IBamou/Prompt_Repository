<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'app/model/authenModel.php';
include 'app/model/userModel.php';

setAdmin('Ilyas', 'ilyas0bmp@gmail.com', 'password123');

$errors = [];
$success = [];
$loginError = '';
$signupError = '';

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

function verifyInputs($name, $email, $password) {
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (strlen($name) > 100) {
        $errors[] = "Name cannot exceed 100 characters.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    
    return $errors;
}

$csrfToken = generateCSRFToken();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'logout':
            closeSession($_SESSION['user']['id'] ?? null);
            header('Location: auth');
            exit;

        case 'signUp':
            if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid request. Please try again.";
                header('Location: auth');
                exit;
            }
            
            $name = cleanInput($_POST['name']);
            $email = cleanInput($_POST['email']);
            $password = $_POST['password'];

            $validationErrors = verifyInputs($name, $email, $password);
            if (!empty($validationErrors)) {
                $_SESSION['error'] = implode(' ', $validationErrors);
                $_SESSION['form_data'] = ['name' => $name, 'email' => $email];
                header('Location: auth#signup');
                exit;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $result = addUser($name, $email, $hashedPassword);
            
            if ($result === true) {
                $_SESSION['success'] = "Account created successfully! Please sign in.";
                header('Location: auth');
                exit;
            } else {
                $_SESSION['error'] = "Email already exists or something went wrong.";
                $_SESSION['form_data'] = ['name' => $name, 'email' => $email];
                header('Location: auth#signup');
                exit;
            }
            break;

        case 'signIn':
            if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid request. Please try again.";
                header('Location: auth');
                exit;
            }
            
            $email = cleanInput($_POST['email']);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Please enter both email and password.";
                header('Location: auth');
                exit;
            }

            $user = verifyLogInData($email, $password);
            
            if (isset($user['blocked']) && $user['blocked']) {
                $_SESSION['error'] = "Your account has been blocked. Please contact an administrator.";
                header('Location: auth');
                exit;
            }
            
            if ($user && $user['success']) {
                $userInfo = getUserInfo($email);
                if ($userInfo) {
                    createSession($userInfo);
                    session_regenerate_id(true);
                    unset($_SESSION['csrf_token']);
                    header('Location: dashboard');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header('Location: auth');
                exit;
            }
            break;

        default:
            $_SESSION['error'] = "Unknown action!";
            break;
    }
}

$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

include 'app/view/authen.php';


