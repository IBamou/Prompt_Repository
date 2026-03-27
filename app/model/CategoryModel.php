<?php
include 'app/config/db.php';

function addCategory($info) {
    try {
        $query = 'INSERT INTO categories (name, description) VALUES (:name, :description)';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':name' => $info['name'], ':description' => $info['description']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}



function updateCategory($info) {
    try {
        $query = 'UPDATE categories SET name = :name, description = :description WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':id' => $info['id'], ':name' => $info['name'], ':description' => $info['description']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}



function deleteCategory($info) {
    try {
        $query = 'DELETE FROM categories WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':id' => $info['id']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}



function getCategories() {
    try {
        $query = 'SELECT * FROM categories';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}


function checkCategoryUpdate($newinfo) {
    try {
        $sql = 'SELECT * FROM categories where id = :id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':id' => $newinfo['id']]);
        $oldinfo = $stmt->fetch(PDO::FETCH_ASSOC);
        $Updates = array_filter($oldinfo, function($value, $key) use ($newinfo) {
            return isset($newinfo[$key]) && !empty($newinfo[$key]) && $newinfo[$key] != $value;
        }, ARRAY_FILTER_USE_BOTH);
        return !empty($Updates);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function searchCategories($search) {
    try {
        $query = 'SELECT * FROM categories WHERE name LIKE :search';
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([':search'=> "%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


function categoryName($categoryId) {
    try {
        $query = "SELECT name FROM categories WHERE id = :id";
        $stmt = $GLOBALS["db"]->prepare($query);
        $stmt->execute([":id"=> $categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)["name"];
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
