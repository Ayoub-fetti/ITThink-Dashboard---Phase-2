<?php
require_once '../config.php';
session_start();

// Vérification admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM projets WHERE id_projet = ?");
        $stmt->execute([$id]);
        
        header("Location: projets.php?message=Projet supprimé avec succès");
        exit();
    } catch(PDOException $e) {
        header("Location: projets.php?error=Erreur lors de la suppression");
        exit();
    }
} 