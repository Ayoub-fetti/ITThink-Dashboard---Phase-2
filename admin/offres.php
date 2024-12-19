<?php
require_once '../config.php';
session_start();

// check si user est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Recuperer toutes les offres avec les informations forein key
$stmt = $pdo->query("
    SELECT o.*, p.titre_projet,
           f.nom_freelance,
           u.nom_utilisateur as client_name
    FROM offres o
    LEFT JOIN projets p ON o.id_projet = p.id_projet
    LEFT JOIN freelances f ON o.id_freelance = f.id_freelance
    LEFT JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
    ORDER BY o.id_offre DESC
");
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la modification du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $offre_id = $_POST['offre_id'];
    $nouveau_statut = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE offres SET status = ? WHERE id_offre = ?");
        $stmt->execute([$nouveau_statut, $offre_id]);
        header("Location: offres.php?success=1");
        exit();
    } catch(PDOException $e) {
        $error = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Offres</title>
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
                        <a href="offres.php" class="flex items-center text-green-500">
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
                <h1 class="text-2xl font-bold mb-6">Gestion des Offres</h1>

                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Statut mis à jour avec succès !
                    </div>
                <?php endif; ?>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Projet</th>
                                <th class="px-4 py-2 text-left">Client</th>
                                <th class="px-4 py-2 text-left">Freelance</th>
                                <th class="px-4 py-2 text-left">Montant</th>
                                <th class="px-4 py-2 text-left">Délai (jours)</th>
                                <th class="px-4 py-2 text-left">Statut</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offres as $offre): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2"><?php echo $offre['id_offre']; ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($offre['titre_projet']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($offre['client_name']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($offre['nom_freelance']); ?></td>
                                <td class="px-4 py-2"><?php echo $offre['montant']; ?> €</td>
                                <td class="px-4 py-2"><?php echo $offre['delai']; ?></td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-sm
                                        <?php 
                                        switch($offre['status']) {
                                            case 'accepter':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'refuser':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            case 'en_cours':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?php echo $offre['status'] ?: 'En attente'; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="offre_id" value="<?php echo $offre['id_offre']; ?>">
                                        <input type="hidden" name="update_status" value="1">
                                        <select name="status" onchange="this.form.submit()" 
                                                class="border rounded px-2 py-1 text-sm">
                                            <option value="">Changer le statut</option>
                                            <option value="en_cours">En cours</option>
                                            <option value="accepter">Accepter</option>
                                            <option value="refuser">Refuser</option>
                                        </select>
                                    </form>
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