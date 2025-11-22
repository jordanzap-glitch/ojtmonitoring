<?php
$sessionPath = __DIR__ . '/session_tmp';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true); // will only run once
}
session_save_path($sessionPath);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('setUserId')) {
    function setUserId($id)
    {
        $_SESSION['userId'] = (int)$id;
    }
}

if (!function_exists('getUserId')) {
    function getUserId()
    {
        return isset($_SESSION['userId']) ? (int)$_SESSION['userId'] : 0;
    }
}

$userId = getUserId();
if ($userId === 0) {
  header('Location: ../index.php');
  exit();
}
?>