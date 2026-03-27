<?php 
include 'app/model/promptLogsModel.php';
include 'app/model/userModel.php';
if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}

if (isset($_GET['prompt_id'])) {
    global $logs;
    $promptId = $_GET['prompt_id'];
    $action = $_GET['action'] ?? '';
    $dateFrom = $_GET['date_from'] ?? '';
    $dateTo = $_GET['date_to'] ?? '';
    $sort = $_GET['sort'] ??'recent';
    $logs = getPromptLogs($promptId, $action, $dateFrom, $dateTo, $sort);
}

$user = $_SESSION['user'];
$isAdmin = $user['role'] == 'admin';


include 'app/view/promptsLogs.php';
exit;