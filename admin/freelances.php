<?php
require_once '../config.php';
session_start();

// check si user est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// REcuperer tous les freelances
$stmt = $pdo->query("
    SELECT f.*, u.nom_utilisateur, u.email 
    FROM freelances f
    JOIN utilisateurs u ON f.id_utilisateur = u.id_utilisateur
");
$freelances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Freelances</title>
    <script src="../Config_tailwind/tailwind.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-white w-64 p-4 flex flex-col">
            <!-- ... Votre sidebar existant ... -->
            <div class="flex items-center mb-8">
                <span class="text-green-500 text-2xl font-bold">Admin</span>
                <span class="ml-2 text-xl">DASHBOARD</span>
            </div>
            <div class="flex items-center mb-8">
                <div class="w-16 h-16 rounded-full border-4 border-green-500 flex items-center justify-center">
                    <span class="text-2xl">A</span>
                </div>
                <span class="ml-4">Admin</span>
            </div>
            <ul>
                <li class="mb-4">
                    <a href="admin_dashboard.php" class="flex items-center hover:text-gray-400 text-white">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </li>
                <li class="mb-4">
                    <a href="projets.php" class="flex items-center hover:text-gray-400 text-white">
                        <i class="fas fa-cogs mr-2"></i>Projets
                    </a>
                </li>
                <li class="mb-4">
                    <a href="categories.php" class="flex items-center hover:text-gray-400 text-white">
                        <i class="fas fa-list mr-2"></i>Catégories
                    </a>
                </li>
                <li class="mb-4">
                    <a href="sous_categories.php" class="flex items-center hover:text-gray-400 text-white">
                        <i class="fas fa-layer-group mr-2"></i>Sous-Catégories
                    </a>
                </li>
                <li class="mb-4">
                    <a href="freelances.php" class="flex items-center text-green-500">
                    <i class="fas fa-id-card mr-2"></i>Freelances
                    </a>
                </li>
                <li class="mb-4">
                    <a href="../logout.php" class="flex items-center hover:text-white text-red-500">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Gestion des Freelances</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Opération réussie !
                    </div>
                <?php endif; ?>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Nom d'utilisateur</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Compétences</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($freelances as $freelance): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($freelance['id_freelance']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($freelance['nom_utilisateur']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($freelance['email']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($freelance['competences']); ?></td>
                                <td class="px-4 py-2">
                                    <a href="modifier_freelance.php?id=<?php echo $freelance['id_freelance']; ?>" 
                                       class="text-blue-500 hover:underline mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="supprimer_freelance.php?id=<?php echo $freelance['id_freelance']; ?>" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce freelance ?')"
                                       class="text-red-500 hover:underline">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 