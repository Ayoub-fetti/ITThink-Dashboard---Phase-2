<?php
require_once '../config.php';
session_start();

// check si user est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Recuperer toutes les categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'ajout d'une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_categorie = trim($_POST['nom_categorie']);
    
    if (!empty($nom_categorie)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (nom_categorie) VALUES (?)");
            $stmt->execute([$nom_categorie]);
            header("Location: categories.php?success=1");
            exit();
        } catch(PDOException $e) {
            $error = "Erreur lors de l'ajout: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories</title>
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
                        <a href="projets.php" class="flex items-center hover:text-gray-400 text-white">
                        <i class="fas fa-cogs mr-2"></i>Projets
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="categories.php" class="flex items-center text-green-500">
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
                        <a href="offres.php" class="flex items-center hover:text-gray-400 text-white">
                        <i class="fas fa-comment-dollar mr-2"></i>Offres
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
                    <h1 class="text-2xl font-bold">Gestion des Catégories</h1>
                    <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        <i class="fas fa-plus mr-2"></i>Ajouter une catégorie
                    </button>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Opération réussie !
                    </div>
                <?php endif; ?>

                <!-- Modal d'ajout -->
                <div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3 text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Ajouter une catégorie</h3>
                            <form method="POST" class="mt-4">
                                <input type="text" name="nom_categorie" 
                                       class="w-full p-2 border rounded" 
                                       placeholder="Nom de la catégorie" required>
                                <div class="mt-4 flex justify-between">
                                    <button type="button" 
                                            onclick="document.getElementById('addModal').classList.add('hidden')"
                                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                        Annuler
                                    </button>
                                    <button type="submit" 
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Ajouter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Nom de la catégorie</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $categorie): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($categorie['id_categorie']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($categorie['nom_categorie']); ?></td>
                                <td class="px-4 py-2">
                                    <a href="modifier_categorie.php?id=<?php echo $categorie['id_categorie']; ?>" 
                                       class="text-blue-500 hover:underline mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="supprimer_categorie.php?id=<?php echo $categorie['id_categorie']; ?>" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')"
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