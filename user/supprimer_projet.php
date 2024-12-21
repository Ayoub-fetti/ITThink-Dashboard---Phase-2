<?php
require_once '../config.php';
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Vérifier que le projet appartient bien à l'utilisateur avant de le supprimer
        $stmt = $pdo->prepare("DELETE FROM projets WHERE id_projet = ? AND id_utilisateur = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        
        header("Location: mes_projets.php?success=1");
        exit();
    } catch(PDOException $e) {
        header("Location: mes_projets.php?error=delete_failed");
        exit();
    }
}

header("Location: mes_projets.php");
exit();