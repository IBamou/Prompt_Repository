<?php
include 'app/config/db.php';


function getPromptLogs($promptId, $action, $dateFrom, $dateTo, $sort = 'recent') {
    try {
        $query = 'SELECT * FROM prompt_logs 
                  WHERE prompt_id = :promptId';

        $extraWhere = [];
        $params = [':promptId' => $promptId];

        if ($action) {
            $extraWhere[] = 'action = :action';
            $params[':action'] = $action;
        }

        if ($dateFrom) {
            $extraWhere[] = 'created_at >= :dateFrom';
            $params[':dateFrom'] = $dateFrom;
        }

        if ($dateTo) {
            $extraWhere[] = 'created_at <= :dateTo';
            $params[':dateTo'] = $dateTo;
        }

        if (!empty($extraWhere)) {
            $query .= ' AND ' . implode(' AND ', $extraWhere);
        }

        // Dynamic sorting (default: recent)
        $sort = $sort ?? 'recent';
        switch ($sort) {
            case 'recent':
                $query .= ' ORDER BY created_at DESC';
                break;
            case 'oldest':
                $query .= ' ORDER BY created_at ASC';
                break;
            default:
                $query .= ' ORDER BY created_at DESC';
        }

        $query .= ' LIMIT 100';  // Safe limit; adjust if needed

        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('getPromptLogs error: ' . $e->getMessage());
        return [];
    }
}