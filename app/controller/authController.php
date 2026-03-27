<?php
include 'app/model/authenModel.php';
include 'app/model/userModel.php';

setAdmin('Ilyas', 'ilyas0bmp@gmail.com', 'password123');


/**
 * Validate user inputs
 */
function verifyInputs($name, $email, $password) {
    // basic validation
    if (empty($name) || empty($email) || empty($password)) {
        return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    if (strlen($password) < 6) { // enforce minimum password length
        return false;
    }
    return true;
}

/**
 * Sanitize POST inputs
 */
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        // LOG OUT
        case 'logout':
            closeSession($_SESSION['user']['id']);
            header('Location: auth');
            exit;

        // SIGN UP
        case 'signUp':
            $name = cleanInput($_POST['name']);
            $email = cleanInput($_POST['email']);
            $password = $_POST['password']; // will hash later

            if (!verifyInputs($name, $email, $password)) {
                echo "Invalid input. Please fill all fields correctly.";
                break;
            }

            // Hash password before storing
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Add user via model
            if (addUser($name, $email, $hashedPassword)) {
                echo "Account created successfully!";
            } else {
                echo "Email already exists or something went wrong.";
            }
            break;

        // LOGIN
        case 'signIn':
            $email = cleanInput($_POST['email']);
            $password = $_POST['password'];

            $user = verifyLogInData($email, $password);
            if ($user) {
                $userInfo = getUserInfo($email);
                createSession($userInfo);
                // optional: regenerate session ID for security
                session_regenerate_id(true);

                header('Location: dashboard');
                exit;
            } else {
                echo "Invalid email or password.";
            }
            break;

        default:
            echo "Unknown action!";
            break;
    }
}

// Load authentication view
include 'app/view/authen.php';



