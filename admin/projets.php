<?php
require_once '../config.php';
session_start();

// checker user est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Recuperer tous les projets avec les informations forein key
$stmt = $pdo->query(" SELECT p.id_projet, p.titre_projet, p.description, c.nom_categorie, sc.nom_sous_categorie, u.nom_utilisateur as createur FROM projets p
    LEFT JOIN categories c ON p.id_categorie = c.id_categorie
    LEFT JOIN souscategorie sc ON p.id_sous_categorie = sc.id_sous_categorie
    LEFT JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur ");
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets</title>
    <script src="../Config_tailwind/tailwind.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
     <div class="flex h-screen">
        <!-- Sidebar -->
            <div class="bg-gray-900 text-white w-64 p-4 flex flex-col">
            <div class="flex items-center mb-8">
            <span class="text-green-500 text-2xl font-bold">
             Admin
            </span>
            <span class="ml-2 text-xl">
            DASHBOARD
            </span>
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
                        <a href="projets.php" class="flex items-center text-green-500">
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
                        <a href="freelances.php" class="flex items-center hover:text-gray-400 text-white">
                        <i class="fas fa-id-card mr-2"></i>Freelances
                     </a>
                    </li>
                    <li class="mb-4">
                        <a href="../logout.php" class="flex items-center hover:text-white text-red-500">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Gestion des Projets</h1>
                </div>

                <?php if (isset($_GET['message'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Titre</th>
                                <th class="px-4 py-2 text-left">Description</th>
                                <th class="px-4 py-2 text-left">Catégorie</th>
                                <th class="px-4 py-2 text-left">Sous-catégorie</th>
                                <th class="px-4 py-2 text-left">Créateur</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projets as $projet): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($projet['id_projet']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($projet['titre_projet']); ?></td>
                                    <td class="px-4 py-2">
                                        <?php 
                                        $description = htmlspecialchars($projet['description']);
                                        echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                                        ?>
                                    </td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($projet['nom_categorie']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($projet['nom_sous_categorie']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($projet['createur']); ?></td>
                                    <td class="px-4 py-2">
                                        <a href="modifier_projet.php?id=<?php echo $projet['id_projet']; ?>" 
                                           class="text-blue-500 hover:underline mr-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="_projet.php?id=<?php echo $projet['id_projet']; ?>" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')"
                                           class="text-red-500 hover:underline">
                                            <i class="fas fa-trash"></i>
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