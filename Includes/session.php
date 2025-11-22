<?php
$sessionPath = dirname(__DIR__) . '/session_tmp'; 
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
session_save_path($sessionPath);

session_start();

if (!isset($_SESSION['userId'])) {

    header('Location: ../index.php');
    exit;
}


?>