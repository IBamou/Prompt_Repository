<?php 
include 'app/config/db.php';

function adduser($name, $email, $password) {
    try {
        $query = 'INSERT INTO users (name, email, password) values(:name, :email, :password)';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
    } catch (PDOException $e) {
        echo $e->getMessage();
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


