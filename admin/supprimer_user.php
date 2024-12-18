<?php
require_once '../config.php';
session_start();

// checker  l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        
        header("Location: admin_dashboard.php?message=deleted");
        exit();
    } catch(PDOException $e) {
        header("Location: admin_dashboard.php?error=delete_failed");
        exit();
    }
}