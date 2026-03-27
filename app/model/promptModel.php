<?php
include 'app/config/db.php';


function addPrompt($info) {
        try {
            $query = "INSERT INTO prompts (title, content, category_id, user_id) VALUES (:title, :content, :category_id, :user_id)";
            $stmt = $GLOBALS['db']->prepare($query);
            $stmt->execute([
                ':title' => $info['title'],
                ':content' => $info['content'],
                ':category_id' => $info['category_id'],
                ':user_id' => $info['user_id']
        ]);
        } catch (Exception $e) {
            echo ''. $e->getMessage() .'';
        }
}

function updatePrompt($info) {
    try {
        $query = "UPDATE prompts SET title = :title, content = :content, category_id = :category_id WHERE id = :id";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([
            ':title' => $info['title'],
            ':content' => $info['content'],
            ':category_id' => $info['category_id'],
            ':id' => $info['id']
        ]);
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}

function deletePrompt($info) {
    try {
        $query = "UPDATE prompts SET category_id = NULL WHERE id = :id";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([
            ':id' => $info['id']
        ]);
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}

function  getPromptsByCategory($categoryId) {
    try {
        $query = 'SELECT
            p.*,
            c.name AS category_name,
            c.description AS category_description,
            u.name AS creator,
            u.email AS creator_email FROM prompts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.category_id = :category_id';

        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}

// function checkPromptUpdate($newinfo) {
//     try {
//         $sql = 'SELECT * FROM prompts where id = :id';
//         $stmt = $GLOBALS['db']->prepare($sql);
//         $stmt->execute([':id' => $newinfo['id']]);
//         $oldinfo = $stmt->fetch(PDO::FETCH_ASSOC);
//         $Updates = array_filter($oldinfo, function($value, $key) use ($newinfo) {
//             return isset($newinfo[$key]) && !empty($newinfo[$key]) && $newinfo[$key] != $value;
//         }, ARRAY_FILTER_USE_BOTH);
//         return !empty($Updates);
//     } catch (Exception $e) {
//         echo $e->getMessage();
//     }
// }

function getPromptChanges($newinfo) {
    try {
        $sql = 'SELECT * FROM prompts WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':id' => $newinfo['id']]);
        $oldinfo = $stmt->fetch(PDO::FETCH_ASSOC);

        $changes = [];

        foreach ($newinfo as $key => $newValue) {
            if (array_key_exists($key, $oldinfo) && $newValue != $oldinfo[$key] && $key != 'category_id' && $key != 'user_id') {
                    $changes[] = [
                        'field' => $key,
                        'old' => $oldinfo[$key],
                        'new' => $newValue
                    ];
            }
        }

        return $changes; // empty array if no changes
    } catch (Exception $e) {
        echo $e->getMessage();
        return [];
    }
}

function uncategorizePrompt($info) {
    try {
        $query = "UPDATE prompts SET category_id = 1 WHERE id = :id";
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':id' => $info['id']]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


function addPromptToCategory($info) {
    try {
        $query = 'UPDATE prompts SET category_id = :category_id WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([
            ':category_id' => $info['category_id'],
            ':id' => $info['id']
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


function searchPrompt($search, $categoryId) {
    try {
        $query = 'SELECT * FROM prompts WHERE title LIKE :search AND category_id = :id';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':search'=> "%$search%", ':id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  catch (Exception $e) {
        echo $e->getMessage();
    }
}


function addPromptLog($user_id, $username, $prompt_id, $action, $field_name, $old_value, $new_value, $message){
    try {
        $query = 'INSERT INTO prompt_logs (user_id, username, prompt_id, field_name, old_value, new_value, action, message) VALUES (:user_id, :username, :prompt_id, :field_name, :old_value, :new_value, :action, :message)';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([
            ':action' => $action,
            ':user_id' => $user_id,
            ':username' => $username,
            ':prompt_id' => $prompt_id,
            ':field_name' => $field_name,
            ':old_value' => $old_value,
            ':new_value' => $new_value,
            ':message' => $message,
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


function getAllPrompts(){
    try {
        $query = 'SELECT * from prompts WHERE category_id IS NOT NULL';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


// function getpromptCreator(){
//     try {
//         $query = 'SELECT user_id FROM prompts WHERE id = :id';
//         $stmt = $GLOBALS['db']->prepare($query);
//         $stmt->execute([':id' => $prompt_id]);
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     } catch (Exception $e) {
//         echo $e->getMessage();
//     }

// }



function promptTitleExists($title, $excludeId = null) {
    try {
        if ($excludeId) {
            $query = 'SELECT id FROM prompts WHERE title = :title AND id != :id LIMIT 1';
            $stmt = $GLOBALS['db']->prepare($query);
            $stmt->execute([':title' => $title, ':id' => $excludeId]);
        } else {
            $query = 'SELECT id FROM prompts WHERE title = :title LIMIT 1';
            $stmt = $GLOBALS['db']->prepare($query);
            $stmt->execute([':title' => $title]);
        }
        return $stmt->fetch() !== false;
    } catch (Exception $e) {
        error_log('promptTitleExists error: ' . $e->getMessage());
        return false;
    }
}

function getSearchedPrompts($search, $category, $user, $date_from, $date_to, $sort) {
    try {
        $select = 'SELECT
            p.*,
            c.name AS category_name,
            c.description AS category_description,
            u.name AS creator,
            u.email AS creator_email';

        $from = 'FROM prompts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id';

        $where = [];
        $params = [];

        // Search in title OR category name (skips if $search null/empty/'')
        if (!empty($search)) {
            $where[] = '(p.title LIKE :search OR c.name LIKE :search)';
            $params[':search'] = "%$search%";
        }

        // Other filters: apply only if truthy/non-empty (matches original logic)
        if ($category) {
            $where[] = 'p.category_id = :category';
            $params[':category'] = $category;
        }

        if ($user) {
            $where[] = 'p.user_id = :user';
            $params[':user'] = $user;
        }

        if ($date_from) {
            $where[] = 'p.created_at >= :date_from';
            $params[':date_from'] = $date_from;
        }

        if ($date_to) {
            $where[] = 'p.created_at <= :date_to';
            $params[':date_to'] = $date_to;
        }

        $query = $select . ' ' . $from;
        if (!empty($where)) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }

        // Sorting (default: recent)
        $sort = $sort ?? 'recent';
        switch ($sort) {
            case 'recent':
                $query .= ' ORDER BY p.created_at DESC';
                break;
            case 'oldest':
                $query .= ' ORDER BY p.created_at ASC';
                break;
            case 'title':
                $query .= ' ORDER BY p.title ASC';
                break;
            case 'author':
                $query .= ' ORDER BY u.name ASC';
                break;
            default:
                $query .= ' ORDER BY p.created_at DESC';
        }

        $query .= ' LIMIT 100';  // Safe limit; remove if unwanted

        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('getSearchedPrompts error: ' . $e->getMessage());
        return [];
    }
}


