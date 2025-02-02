<?php
require_once '../config.php';
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$error = '';
$success = '';

// Récupérer les catégories et sous-catégories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$souscategories = $pdo->query("SELECT * FROM souscategorie")->fetchAll();

// Récupérer le projet
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM projets WHERE id_projet = ? AND id_utilisateur = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $projet = $stmt->fetch();

    // Vérifier si le projet existe et appartient à l'utilisateur
    if (!$projet) {
        header("Location: mes_projets.php");
        exit();
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];
    $souscategorie = $_POST['souscategorie'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE projets SET titre_projet = ?, DESCRIPTION = ?, id_categorie = ?, id_sous_categorie = ?, status = ? WHERE id_projet = ? AND id_utilisateur = ?");
        $stmt->execute([$titre, $description, $categorie, $souscategorie, $status, $id, $_SESSION['user_id']]);
        header("Location: mes_projets.php?success=1");
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
    <title>Modifier le projet</title>
    <script src="../Config_tailwind/tailwind.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier le projet</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $projet['id_projet']; ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Titre du projet</label>
                <input type="text" name="titre" value="<?php echo htmlspecialchars($projet['titre_projet']); ?>" 
                       class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea name="description" class="w-full p-2 border rounded" rows="4"><?php echo htmlspecialchars($projet['DESCRIPTION']); ?></textarea>
            </div>

            <!-- Catégories et sous-catégories comme dans l'admin -->
            <!-- ... copier le code des select depuis admin/modifier_projet.php ... -->

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full p-2 border rounded">
                    <option value="en_cours" <?php echo $projet['status'] === 'en_cours' ? 'selected' : ''; ?>>En cours</option>
                    <option value="termine" <?php echo $projet['status'] === 'termine' ? 'selected' : ''; ?>>Terminé</option>
                    <option value="annule" <?php echo $projet['status'] === 'annule' ? 'selected' : ''; ?>>Annulé</option>
                    <option value="en_pause" <?php echo $projet['status'] === 'en_pause' ? 'selected' : ''; ?>>En pause</option>
                </select>
            </div>
            
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Sauvegarder
                </button>
                <a href="mes_projets.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Retour
                </a>
            </div>
        </form>
    </div>
</body>
</html>