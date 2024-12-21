<?php
require_once '../config.php';
session_start();

// check user est connecte
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// recuperer toutes les offres liees aux projets de l'user
$stmt = $pdo->prepare("
    SELECT o.*, p.titre_projet,
           f.nom_freelance,
           u.nom_utilisateur as freelance_name
    FROM offres o
    JOIN projets p ON o.id_projet = p.id_projet
    JOIN freelances f ON o.id_freelance = f.id_freelance
    JOIN utilisateurs u ON f.id_utilisateur = u.id_utilisateur
    WHERE p.id_utilisateur = ?
");
$stmt->execute([$_SESSION['user_id']]);
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// traitement de la modification du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $offre_id = $_POST['offre_id'];
    $nouveau_statut = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE offres SET status = ? WHERE id_offre = ?");
        $stmt->execute([$nouveau_statut, $offre_id]);
        header("Location: mes_offres.php?success=1");
        exit();
    } catch(PDOException $e) {
        header("Location: mes_offres.php?error=1");
        exit();
    }
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Offres</title>
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
            <ul>
                <li class="mb-4">
                    <a href="dashboard.php" class="flex items-center text-white hover:text-green-500">
                        <i class="fas fa-home mr-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="mes_projets.php" class="flex items-center text-white hover:text-green-500">
                    <i class="fas fa-cogs mr-2"></i>
                        <span>Mes Projets</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="mes_offres.php" class="flex items-center text-green-500 hover:text-white">
                        <i class="fas fa-comment-dollar mr-2"></i>
                        <span>Mes Offres</span>
                    </a>
                </li>
            </ul>
            <ul>
                <li class="mb-4">
                <a class="flex items-center text-red-500 hover:text-white" 
                    href="../logout.php">
                    <i class="fas fa-sign-out-alt mr-2">
                    </i>Déconnexion</a></li>
                </ul>

        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="container p-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h1 class="text-2xl font-bold mb-6">Mes Offres</h1>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                            Statut mis à jour avec succès
                        </div>
                    <?php endif; ?>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Projet</th>
                                    <th class="px-4 py-2 text-left">Freelance</th>
                                    <th class="px-4 py-2 text-left">Prix</th>
                                    <th class="px-4 py-2 text-left">Délai (jours)</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($offres as $offre): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($offre['titre_projet']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($offre['freelance_name']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($offre['montant']); ?> €</td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($offre['delai']); ?> jours</td>
                                    <td class="px-4 py-2">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="offre_id" value="<?php echo $offre['id_offre']; ?>">
                                            <input type="hidden" name="update_status" value="1">
                                            <select name="status" onchange="this.form.submit()" 
                                                    class="border rounded px-2 py-1 text-sm <?php
                                                        if ($offre['status'] === 'accepter') {
                                                            echo 'bg-green-100 text-green-800';
                                                        } elseif ($offre['status'] === 'refuser') {
                                                            echo 'bg-red-100 text-red-800';
                                                        } else {
                                                            echo 'bg-gray-100 text-gray-800';
                                                        }
                                                    ?>">
                                                <option value="en_attente" <?php echo $offre['status'] === 'en_attente' ? 'selected' : ''; ?>>
                                                    En attente
                                                </option>
                                                <option value="accepter" <?php echo $offre['status'] === 'accepter' ? 'selected' : ''; ?>>
                                                    Acceptée
                                                </option>
                                                <option value="refuser" <?php echo $offre['status'] === 'refuser' ? 'selected' : ''; ?>>
                                                    Refusée
                                                </option>
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
    </div>
</body>
</html> 