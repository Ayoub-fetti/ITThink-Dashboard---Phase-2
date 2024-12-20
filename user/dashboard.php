<?php
require_once '../config.php';
session_start();

// check si user est connecte
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Recuperer les categories pour le formulaire
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Si une categorie est selectionnee, recuperer leurs sous-categories
$sous_categories = [];
if (isset($_POST['categorie'])) {
    $stmt = $pdo->prepare("SELECT * FROM souscategorie WHERE id_categorie = ?");
    $stmt->execute([$_POST['categorie']]);
    $sous_categories = $stmt->fetchAll();
}

// Traitement du formulaire de creation de projet
if (isset($_POST['submit_project'])) {
    $titre = trim($_POST['titre_projet']);
    $description = trim($_POST['DESCRIPTION']);
    $categorie = $_POST['categorie'];
    $sous_categorie = $_POST['sous_categorie'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO projets (titre_projet, DESCRRIPTION, id_categorie, id_sous_categorie, id_utilisateur) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $categorie, $sous_categorie, $user_id]);
        $success = "Projet créé avec succès!";
    } catch(PDOException $e) {
        $error = "Erreur lors de la création du projet: " . $e->getMessage();
    }
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                <a class="flex items-center  hover:text-green-500 text-white" href="mes_projets.php">
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
  
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="container p-6">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <?php if (isset($success)): ?>
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <!-- etape 1: Selection de la categorie -->
                    <?php if (!isset($_POST['categorie'])): ?>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold mb-4">Étape 1: Choisir une catégorie</h2>
                            <form method="POST" class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 mb-2">Catégorie</label>
                                    <select name="categorie" required class="w-full p-2 border rounded">
                                        <option value="">Sélectionnez une catégorie</option>
                                        <?php foreach ($categories as $categorie): ?>
                                            <option value="<?php echo $categorie['id_categorie']; ?>">
                                                <?php echo htmlspecialchars($categorie['nom_categorie']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                                    Suivant
                                </button>
                            </form>
                        </div>

                    <!-- Étape 2: Creation du projet -->
                    <?php else: ?>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold mb-4">Étape 2: Créer votre projet</h2>
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="categorie" value="<?php echo htmlspecialchars($_POST['categorie']); ?>">
                                
                                <div>
                                    <label class="block text-gray-700 mb-2">Titre du projet</label>
                                    <input type="text" name="titre_projet" required
                                           class="w-full p-2 border rounded">
                                </div>

                                <div>
                                    <label class="block text-gray-700 mb-2">Description</label>
                                    <textarea name="description" required rows="4"
                                              class="w-full p-2 border rounded"></textarea>
                                </div>

                                <div>
                                    <label class="block text-gray-700 mb-2">Sous-catégorie</label>
                                    <select name="sous_categorie" required class="w-full p-2 border rounded">
                                        <option value="">Sélectionnez une sous-catégorie</option>
                                        <?php foreach ($sous_categories as $sous_cat): ?>
                                            <option value="<?php echo $sous_cat['id_sous_categorie']; ?>">
                                                <?php echo htmlspecialchars($sous_cat['nom_sous_categorie']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="flex space-x-4">
                                    <button type="submit" name="submit_project" 
                                            class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                                        Créer le projet
                                    </button>
                                    <a href="dashboard.php" 
                                       class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                                        Retour
                                    </a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
