<?php
require_once 'config.php';
session_start();

// Débogage
var_dump($_SESSION);

// Vérification si l'utilisateur est connecté ET est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Accès refusé: ";
    echo "user_id set: " . isset($_SESSION['user_id']);
    echo "role is admin: " . ($_SESSION['role'] === 'admin');
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="/Config_tailwind/tailwind.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Dashboard Administrateur - Bienvenue <?php echo htmlspecialchars($username); ?></h1>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Panel d'administration</h2>
                <ul class="list-disc pl-5">
                    <li><a href="#" class="text-blue-500 hover:underline">Gérer les utilisateurs</a></li>
                    <li><a href="#" class="text-blue-500 hover:underline">Voir les statistiques</a></li>
                    <li><a href="#" class="text-blue-500 hover:underline">Paramètres du site</a></li>
                </ul>
            </div>

            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Déconnexion</a>
        </div>
    </div>
</body>
</html>
