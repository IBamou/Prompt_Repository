<?php
include 'app/config/db.php'; // $db is PDO

// function setAdmin($name, $email, $password) {
//     global $db; // PDO connection

//     if (!$db);

//     // hash password
//     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

//     try {
//         // start transaction
//         $db->beginTransaction();

//         // check if user exists
//         $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
//         $stmt->execute([$email]);
//         $userExists = $stmt->rowCount() > 0;

//         if ($userExists) {
//             // user exists → make all users 'user'
//             $db->exec("UPDATE users SET role='user'");

//             // set this user to 'admin'
//             $stmt = $db->prepare("UPDATE users SET role='admin' WHERE email = ?");
//             $stmt->execute([$email]);

//             $db->commit();

//         } else {
//             // new user → make all existing users 'user' just in case
//             $db->exec("UPDATE users SET role='user'");

//             // insert new admin
//             $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
//             $stmt->execute([$name, $email, $hashedPassword]);

//             $db->commit();
//         }
//     } catch (Exception $e) {
//         $db->rollBack();
//         return "Error: " . $e->getMessage();
//     }
// }
function setAdmin($name, $email, $password) {
    global $db; // PDO connection

    if (!$db) {
        return "Error: Database connection not available.";
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $db->beginTransaction();

        // Check if user exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $userExists = $stmt->rowCount() > 0;

        // Demote ALL existing mainadmins and admins (only 1 mainadmin allowed)
        // $db->exec("UPDATE users SET role='user' WHERE role IN ('superAdmin', 'admin')");

        if ($userExists) {
            // User exists → promote to mainadmin
            $stmt = $db->prepare("UPDATE users SET role='superAdmin' WHERE email = ?");
            $stmt->execute([$email]);
        } else {
            // New user → insert as mainadmin
            $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'superAdmin')");
            $stmt->execute([$name, $email, $hashedPassword]);
        }

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return "Error: " . $e->getMessage();
    }
}
function verifyLogInData($email, $password) {
    try {
        // fetch the user by email
        $query = "SELECT id, email, password, status FROM users WHERE email = :email LIMIT 1";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // check if blocked
            if ($user['status'] === 'blocked') {
                return [
                    'success' => false,
                    'email' => true,
                    'password' => false,
                    'blocked' => true
                ];
            }

            // verify password
            if (password_verify($password, $user['password'])) {
                return [
                    'success' => true,
                    'user_id' => $user['id'],
                    'email' => true,
                    'password' => true,
                    'blocked' => false
                ];
            } else {
                return [
                    'success' => false,
                    'email' => true,
                    'password' => false,
                    'blocked' => false
                ];
            }
        } else {
            return [
                'success' => false,
                'email' => false,
                'password' => false,
                'blocked' => false
            ];
        }

    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'blocked' => false
        ];
    }
}


function createSession($userInfo) {
    $sql = "INSERT INTO user_sessions (user_id, login_time, ip_address, user_agent)
            VALUES (:user_id, NOW(), :ip, :ua)";

    $stmt = $GLOBALS['db']->prepare($sql);
    $stmt->execute([
        ':user_id' => $userInfo['id'],
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ]);

    // Save session ID to PHP session
    $_SESSION['user'] = $userInfo;
}



function closeSession($userId) {
    $sql = "UPDATE user_sessions 
            SET logout_time = NOW() 
            WHERE user_id = :user_id";

    $stmt = $GLOBALS['db']->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId
    ]);
    session_destroy();
}