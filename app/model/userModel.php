<?php 
include 'app/config/db.php';

function addUser($name, $email, $password) {
    try {
        $checkQuery = 'SELECT id FROM users WHERE email = :email';
        $checkStmt = $GLOBALS['db']->prepare($checkQuery);
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->fetch()) {
            return false;
        }
        
        $query = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        return true;
    } catch (PDOException $e) {
        error_log('addUser error: ' . $e->getMessage());
        return false;
    }
}


function getUserInfo($email) {
    try {
        $query = 'SELECT * FROM users WHERE email = :email';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':email'=> $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function getUsers() {
    try {
        $query = 'SELECT * FROM users';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getPromptsAllInfo() {
    try {
        $query = 'SELECT
            p.*,
            c.name AS category_name,
            c.description AS category_description,
            u.name AS creator,
            u.email AS creator_email
        FROM prompts p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC';

        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        $recentPrompts = array_filter($stmt->fetchAll(PDO::FETCH_ASSOC), function ($prompt) {
            return $prompt['category_id'] !== null;
        });
        return $recentPrompts;
    } catch (Exception $e) {
        error_log('getRecentPrompts error: ' . $e->getMessage());
        return []; // Return empty array on error instead of echoing
    }
}

function getUsersAllInfo($limit = null) {
    try {
        $sql = "SELECT 
                u.id,
                u.name,
                u.email,
                u.role,
                u.status,
                u.created_at,

                COUNT(DISTINCT CASE WHEN p.category_id IS NOT NULL THEN p.id END) AS prompts_count,

                MAX(s.login_time) AS last_login,
                MAX(s.logout_time) AS last_logout

            FROM users u

            LEFT JOIN prompts p 
                ON p.user_id = u.id

            LEFT JOIN user_sessions s 
                ON s.user_id = u.id

            GROUP BY 
                u.id, u.name, u.email, u.role, u.status, u.created_at

            ORDER BY u.created_at DESC
        ";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $GLOBALS['db']->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error fetching users: " . $e->getMessage());
    }
}



function getSearchUsers($search, $role = null, $status = null) {
    try {
        $select = 'SELECT
            u.id,
            u.name,
            u.email,
            u.role,
            u.status,
            u.created_at,
            COUNT(DISTINCT CASE WHEN p.category_id IS NOT NULL THEN p.id END) AS prompts_count,
            MAX(s.login_time) AS last_login,
            MAX(s.logout_time) AS last_logout';

        $from = 'FROM users u
                 LEFT JOIN prompts p ON p.user_id = u.id
                 LEFT JOIN user_sessions s ON s.user_id = u.id';

        $where = [];
        $params = [];

        // Search in name OR email
        if (!empty($search)) {
            $where[] = '(u.name LIKE :search OR u.email LIKE :search)';
            $params[':search'] = "%$search%";
        }

        if ($role) {
            $where[] = 'u.role = :role';
            $params[':role'] = $role;
        }

        if ($status) {
            $where[] = 'u.status = :status';
            $params[':status'] = $status;
        }

        $query = $select . ' ' . $from;
        if (!empty($where)) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }

        $query .= ' GROUP BY u.id, u.name, u.email, u.role, u.status, u.created_at
                    ORDER BY u.created_at DESC
                    LIMIT 100';

        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('getSearchUsers error: ' . $e->getMessage());
        return [];
    }
}



function makeAdmin($user_id) {
    try {
        $stmt = $GLOBALS['db']->prepare("UPDATE users SET role = 'admin' WHERE id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log('makeAdmin error: ' . $e->getMessage());
        return false;
    }
}
function revokeAdmin($user_id) {
    try {
        $stmt = $GLOBALS['db']->prepare("UPDATE users SET role = 'user' WHERE id = :user_id AND role = 'admin'");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log('revokeAdmin error: ' . $e->getMessage());
        return false;
    }
}

function blockUser($user_id) {
    try {
        $stmt = $GLOBALS['db']->prepare("UPDATE users SET status = 'blocked' WHERE id = :user_id AND role != 'mainadmin'");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log('blockUser error: ' . $e->getMessage());
        return false;
    }
}

function unblockUser($user_id) {
    try {
        $stmt = $GLOBALS['db']->prepare("UPDATE users SET status = 'active' WHERE id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log('unblockUser error: ' . $e->getMessage());
        return false;
    }
}

function updateUserProfile($userId, $name, $email) {
    try {
        $checkQuery = 'SELECT id FROM users WHERE email = :email AND id != :id';
        $checkStmt = $GLOBALS['db']->prepare($checkQuery);
        $checkStmt->execute([':email' => $email, ':id' => $userId]);
        if ($checkStmt->fetch()) {
            return ['success' => false, 'error' => 'Email already in use by another user.'];
        }
        
        $query = 'UPDATE users SET name = :name, email = :email WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':id' => $userId
        ]);
        return ['success' => true];
    } catch (PDOException $e) {
        error_log('updateUserProfile error: ' . $e->getMessage());
        return ['success' => false, 'error' => 'Failed to update profile.'];
    }
}

function updateUserPassword($userId, $newPassword) {
    try {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = 'UPDATE users SET password = :password WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $userId
        ]);
        return ['success' => true];
    } catch (PDOException $e) {
        error_log('updateUserPassword error: ' . $e->getMessage());
        return ['success' => false, 'error' => 'Failed to update password.'];
    }
}

function emailExistsForOther($email, $excludeId) {
    try {
        $query = 'SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':email' => $email, ':id' => $excludeId]);
        return $stmt->fetch() !== false;
    } catch (Exception $e) {
        error_log('emailExistsForOther error: ' . $e->getMessage());
        return false;
    }
}