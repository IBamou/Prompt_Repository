<?php 
if(!isset($_SESSION['user'])) {
    header('Location: auth');
    exit;
}




$user = $_SESSION["user"];
$title = 'Profile';

$isAdmin = $user['role'] == 'admin';

include 'app/view/profileView.php';
?>