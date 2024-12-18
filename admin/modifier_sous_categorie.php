<?php
require_once '../config.php';
session_start();

// check admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$error = '';
$success = '';

// Recuperer toutes les categories pour le formulaire
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Recuperer la sous-categorie
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM souscategorie WHERE id_sous_categorie = ?");
    $stmt->execute([$id]);
    $souscategorie = $stmt->fetch();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom_sous_categorie'];
    $id_categorie = $_POST['id_categorie'];

    try {
        $stmt = $pdo->prepare("UPDATE souscategorie SET nom_sous_categorie = ?, id_categorie = ? WHERE id_sous_categorie = ?");
        $stmt->execute([$nom, $id_categorie, $id]);
        header("Location: sous_categories.php?success=1");
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
    <title>Modifier la sous-catégorie</title>
    <script src="../Config_tailwind/tailwind.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier la sous-catégorie</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $souscategorie['id_sous_categorie']; ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nom de la sous-catégorie</label>
                <input type="text" name="nom_sous_categorie" 
                       value="<?php echo htmlspecialchars($souscategorie['nom_sous_categorie']); ?>" 
                       class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Catégorie parente</label>
                <select name="id_categorie" class="w-full p-2 border rounded">
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['id_categorie']; ?>"
                                <?php echo $souscategorie['id_categorie'] == $categorie['id_categorie'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categorie['nom_categorie']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Sauvegarder
                </button>
                <a href="sous_categories.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Retour
                </a>
            </div>
        </form>
    </div>
</body>
</html> 