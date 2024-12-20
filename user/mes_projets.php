<?php
require_once '../config.php';
session_start();

// Ajouter cette fonction au début du fichier, après session_start()
function getStatusColor($status) {
    switch($status) {
        case 'en_cours':
            return 'bg-blue-100 text-blue-800';
        case 'termine':
            return 'bg-green-100 text-green-800';
        case 'annule':
            return 'bg-red-100 text-red-800';
        case 'en_pause':
            return 'bg-yellow-100 text-yellow-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

// Verification si l'utilisateur est connecte
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Récupérer les projets de l'utilisateur
$stmt = $pdo->prepare("
    SELECT p.*, c.nom_categorie, sc.nom_sous_categorie 
    FROM projets p
    LEFT JOIN categories c ON p.id_categorie = c.id_categorie
    LEFT JOIN souscategorie sc ON p.id_sous_categorie = sc.id_sous_categorie
    WHERE p.id_utilisateur = ?
    ORDER BY p.id_projet DESC
");
$stmt->execute([$_SESSION['user_id']]);
$projets = $stmt->fetchAll();

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Projets</title>
    <script src="../Config_tailwind/tailwind.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-white w-64 p-4 flex flex-col">
            <div class="flex items-center mb-8">
                <span class="text-green-500 text-2xl font-bold">User</span>
                <span class="ml-2 text-xl">DASHBOARD</span>
            </div>
            <div class="flex items-center mb-8">
                <div class="w-16 h-16 rounded-full border-4 border-green-500 flex items-center justify-center">
                    <span class="text-2xl"><?php echo strtoupper(substr($username, 0, 1)); ?></span>
                </div>
                <span class="ml-4"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <nav class="flex-1">
                <ul>
                <li class="mb-4">
                <a class="flex items-center  hover:text-green-500  text-white"  href="dashboard.php">
                <i class="fas fa-home mr-2">
                    </i>Dashboard</a></li>
                </ul>
                <ul>
                <li class="mb-4">
                <a class="flex items-center   text-green-500"  href="mes_projets.php">
                <i class="fas fa-cogs mr-2">
                    </i>Mes Projets</a></li>
                </ul>

                <ul>
                <li class="mb-4">
                <a class="flex items-center text-red-500 hover:text-white" 
                    href="../logout.php">
                    <i class="fas fa-sign-out-alt mr-2">
                    </i>Déconnexion</a></li>
                </ul>
            </nav>
            <div class="mt-auto">
                <a href="../logout.php" class="flex items-center space-x-2 p-2 hover:bg-gray-800 rounded text-red-500">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="container p-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-6">Mes Projets</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Titre</th>
                                    <th class="px-4 py-2 text-left">Description</th>
                                    <th class="px-4 py-2 text-left">Catégorie</th>
                                    <th class="px-4 py-2 text-left">Sous-catégorie</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projets as $projet): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($projet['titre_projet']); ?></td>
                                        <td class="px-4 py-2">
                                            <?php 
                                            $description = htmlspecialchars($projet['DESCRIPTION']);
                                            echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                                            ?>
                                        </td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($projet['nom_categorie']); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($projet['nom_sous_categorie']); ?></td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-sm <?php echo getStatusColor($projet['status']); ?>">
                                                <?php 
                                                switch($projet['status']) {
                                                    case 'en_cours':
                                                        echo 'En cours';
                                                        break;
                                                    case 'termine':
                                                        echo 'Terminé';
                                                        break;
                                                    case 'annule':
                                                        echo 'Annulé';
                                                        break;
                                                    case 'en_pause':
                                                        echo 'En pause';
                                                        break;
                                                    default:
                                                        echo 'En cours';
                                                }
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 