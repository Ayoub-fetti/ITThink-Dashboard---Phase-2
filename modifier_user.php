<?php
require_once 'config.php';
session_start();

// check si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom_utilisateur = ?, email = ?, role = ? WHERE id_utilisateur = ?");
        $stmt->execute([$username, $email, $role, $id]);
        $success = "Utilisateur mis à jour avec succès";
    } catch(PDOException $e) {
        $error = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer l'utilisateur</title>
    <script src="./Config_tailwind/tailwind.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Éditer l'utilisateur</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id_utilisateur']; ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nom d'utilisateur</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['nom_utilisateur']); ?>" 
                       class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                       class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Rôle</label>
                <select name="role" class="w-full p-2 border rounded">
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="freelancer" <?php echo $user['role'] === 'freelancer' ? 'selected' : ''; ?>>freelancer</option>
                </select>
            </div>
            
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Sauvegarder
                </button>
                <a href="admin_dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Retour
                </a>
            </div>
        </form>
    </div>
</body>
</html>