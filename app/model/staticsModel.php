<?php
include 'app/config/db.php';


function totalPrompts() {
    try {
        $query = "SELECT COUNT(*) AS total_prompts FROM prompts WHERE category_id IS NOT NULL";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total_prompts'];
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}
function totalCategories() {
    try {
        $query = "SELECT COUNT(*) AS total_categories FROM categories";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total_categories'];
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}

function totalUsers() {
    try {
        $query = "SELECT COUNT(*) AS total_users FROM users";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total_users'];
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}


// function getRecentPrompts() {
//     try {
//         $query = 'SELECT
//             p.*,
//             c.name AS category_name,
//             c.description AS category_description,
//             u.name AS creator,
//             u.email AS creator_email
//         FROM prompts p
//         LEFT JOIN categories c ON p.category_id = c.id
//         LEFT JOIN users u ON p.user_id = u.id
//         ORDER BY p.created_at DESC
//         LIMIT 3';

//         $stmt = $GLOBALS['db']->prepare($query);
//         $stmt->execute();
//         $recentPrompts = array_filter($stmt->fetchAll(PDO::FETCH_ASSOC), function ($prompt) {
//             return $prompt['category_id'] != null;
//         });
//         return $recentPrompts;
//     } catch (Exception $e) {
//         error_log('getRecentPrompts error: ' . $e->getMessage());
//         return []; // Return empty array on error instead of echoing
//     }
// }

function getRecentPrompts() {
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
        WHERE p.category_id IS NOT NULL
        ORDER BY p.created_at DESC
        LIMIT 5';

        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('getRecentPrompts error: ' . $e->getMessage());
        return [];
    }
}

function promptsThisMonth() {
    try {
        $query = "SELECT COUNT(*) AS prompts_this_month FROM prompts WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) AND category_id IS NOT NULL";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['prompts_this_month'];
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}

function mostActiveUser(){
    try {
        $query = 'SELECT user_id, COUNT(*) AS total_prompts FROM prompts WHERE user_id IS NOT NULL GROUP BY user_id ORDER BY total_prompts DESC LIMIT 1';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!isset($row['user_id'])) {
            return false;
        }
        $query = 'SELECT name FROM users WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':id' => $row['user_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);   
        return $row['name'];
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}


// ABOUT CATEGORIES
// function addedRecently() {
//     try {
//         $query = 'SELECT COUNT(*) AS addedRecently FROM prompts WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) AND DAY(created_at) = DAY(CURDATE()) AND category_id IS NOT NULL';
//         $stmt = $GLOBALS['db']->prepare($query);
//         $stmt->execute();
//         $row = $stmt->fetch(PDO::FETCH_ASSOC);
//         return $row['addedRecently'];
//     } catch (Exception $e) {
//         echo ''. $e->getMessage() .'';
//     }
// }


function totalAdmins() {
    try {
        $query = 'SELECT COUNT(*) AS total_admins FROM users WHERE role = :role';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':role' => 'admin']);
        $row = $stmt->fetch();
        return $row['total_admins'];
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}

function totalBlockedUsers() {
    try {
        $query = 'SELECT COUNT(*) AS total_blocked FROM users WHERE status = :status';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':status' => 'blocked']);
        $row = $stmt->fetch();
        return $row['total_blocked'];
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}
