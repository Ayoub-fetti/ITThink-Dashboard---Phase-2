<?php
require_once '../config.php';
session_start();

// check admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM freelances WHERE id_freelance = ?");
        $stmt->execute([$id]);
        
        header("Location: freelances.php?success=1");
        exit();
    } catch(PDOException $e) {
        header("Location: freelances.php?error=delete_failed");
        exit();
    }
}

header("Location: freelances.php");
exit();