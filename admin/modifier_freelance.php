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

// Récupérer les informations du freelance
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("
        SELECT f.*, u.nom_utilisateur, u.email 
        FROM freelances f
        JOIN utilisateurs u ON f.id_utilisateur = u.id_utilisateur
        WHERE f.id_freelance = ?
    ");
    $stmt->execute([$id]);
    $freelance = $stmt->fetch();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $competences = $_POST['competences'];

    try {
        $stmt = $pdo->prepare("UPDATE freelances SET competences = ? WHERE id_freelance = ?");
        $stmt->execute([$competences, $id]);
        $success = "Freelance mis à jour avec succès";
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
    <title>Modifier le freelance</title>
    <script src="../Config_tailwind/tailwind.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier le freelance</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $freelance['id_freelance']; ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nom d'utilisateur</label>
                <input type="text" value="<?php echo htmlspecialchars($freelance['nom_utilisateur']); ?>" 
                       class="w-full p-2 border rounded" disabled>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" value="<?php echo htmlspecialchars($freelance['email']); ?>" 
                       class="w-full p-2 border rounded" disabled>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Compétences</label>
                <input type="text" name="competences" value="<?php echo htmlspecialchars($freelance['competences']); ?>" 
                       class="w-full p-2 border rounded">
            </div>
            
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Sauvegarder
                </button>
                <a href="freelances.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Retour
                </a>
            </div>
        </form>
    </div>
</body>
</html> 