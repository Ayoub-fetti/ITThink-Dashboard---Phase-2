<?php
require_once 'config.php';
session_start();

// Verification si l'utilisateur est connecte
if (!isset($_SESSION['user_id'])) {
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
    <title>Dashboard</title>
    <script src="./Config_tailwind/tailwind.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Bienvenue <?php echo htmlspecialchars($username); ?></h1>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Déconnexion</a>
        </div>
    </div>
</body>
</html>
