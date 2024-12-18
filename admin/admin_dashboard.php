<?php
require_once '../config.php';
session_start();

// le nombre d'utilisateur 
$stmt = $pdo->query("SELECT COUNT(*) as total_utilisateurs FROM utilisateurs");
$userCount = $stmt->fetch()['total_utilisateurs'];

// le nombre des projects
$stmt = $pdo->query("SELECT COUNT(*) as total_projets FROM projets");
$projetsCount = $stmt->fetch()['total_projets'];

// le nombre des freelances 
$stmt = $pdo->query("SELECT COUNT(*) as total_freelances FROM freelances");
$freelancesCount = $stmt->fetch()['total_freelances'];

// le nombre des offres 
$stmt = $pdo->query("SELECT COUNT(*) as total_offres FROM offres");
$offresCount = $stmt->fetch()['total_offres'];

// recuperer  tous les users de DB
$stmt = $pdo->query("SELECT id_utilisateur, nom_utilisateur, email, role FROM utilisateurs");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// check si l'utilisateur est connecte ET est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
?>

<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Dashboard</title>
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
      <span class="text-2xl">
       A
      </span>
     </div>
     <span class="ml-4">
      Admin
     </span>
    </div>
    <nav class="flex-1">
     <ul>
      <li class="mb-4">
       <a class="flex items-center  hover:text-gray-400 text-white" href="projets.php">
       <!-- <i class="fas fa-project-diagram mr-2"> -->
       <i class="fas fa-cogs mr-2">
        </i>Projets</a></li>
     </ul>
     <ul>
      <li class="mb-4">
       <a class="flex items-center  hover:text-gray-400 text-white" href="categories.php">
       <i class="fas fa-list mr-2"></i>
        </i>Categories</a></li>
     </ul>
     <ul>
      <li class="mb-4">
       <a class="flex items-center  hover:text-gray-400 text-white" href="#">
       <!-- <i class="fas fa-list mr-2"></i> -->
       <!-- <i class="fas fa-ellipsis-h mr-2"></i> -->
       <i class="fas fa-layer-group mr-2"></i>
        </i>Sous-Categories</a></li>
     </ul>
     
     <ul>
      <li class="mb-4">
       <a class="flex items-center  hover:text-gray-400 text-white" href="#">
       <i class="fas fa-id-card mr-2">
        </i>Freelances</a></li>
     </ul>
     <ul>
      <li class="mb-4">
       <a class="flex items-center hover:text-gray-400 text-white" href="#">
       <i class="fas fa-comment-dollar mr-2">
        </i>Offres</a></li>
     </ul>
     <ul>
      <li class="mb-4">
       <a class="flex items-center text-red-500 hover:text-white" 
          href="../logout.php">
        <i class="fas fa-sign-out-alt mr-2"></i>
        Déconnexion
       </a>
      </li>
     </ul>
    </nav>
   </div>
   <!-- Main Content -->
   <div class="flex-1 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
     <input class="p-2 rounded border border-gray-300" placeholder="Search" type="text"/>
     <div class="flex items-center">
     </div>
    </div>
    <!-- Dashboard Content -->
    <div>
     <h1 class="text-2xl font-bold mb-4">
      Dashboard
     </h1>
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="bg-white p-4 rounded shadow">
       <div class="flex items-center">
        <i class="far fa-user text-blue-500 text-2xl mr-4">
        </i>
        <div>
         <h2 class="text-xl font-bold">
          <?php echo $userCount; ?>
         </h2>
         <p class="text-gray-500">
         Utilisateurs
         </p>
        </div>
       </div>
      </div>
      <div class="bg-white p-4 rounded shadow">
       <div class="flex items-center">
        <i class="fas fa-cogs text-yellow-500 text-2xl mr-4">
        </i>
        <div>
         <h2 class="text-xl font-bold">
          <?php echo $projetsCount; ?>
         </h2>
         <p class="text-gray-500">
         <i class="fas fa-project-diagram mr-2"></i>
         Projets
         </p>
        </div>
       </div>
      </div>
      <div class="bg-white p-4 rounded shadow">
       <div class="flex items-center">
        <i class="fas fa-id-card text-pink-500 text-2xl mr-4">
        </i>
        <div>
         <h2 class="text-xl font-bold">
          <?php echo $freelancesCount; ?>
         </h2>
         <p class="text-gray-500">
         Freelances
         </p>
        </div>
       </div>
      </div>
      <div class="bg-white p-4 rounded shadow">
       <div class="flex items-center">
        <i class="fas fa-comment-dollar text-blue-500 text-2xl mr-4">
        </i>
        <div>
         <h2 class="text-xl font-bold">
          <?php echo $offresCount; ?>
         </h2>
         <p class="text-gray-500">
          Offres
         </p>
        </div>
       </div>
      </div>
     </div>
     <div class="bg-white p-4 rounded shadow">
      <h2 class="text-xl font-bold mb-4">
       Table
      </h2>
      <table class="w-full">
       <thead>
        <tr class="text-left border-b">
         <th class="pb-2">
         Id d'utilisateur
         </th>
         <th class="pb-2">
         Nom d'utilisateur
         </th>
         <th class="pb-2">
          Role
         </th>
         <th class="pb-2">
          email
         </th>
        </tr>
       </thead>
       <tbody>
        <?php foreach($users as $user) : ?>
        <tr class="border-b">
         <td class="py-2">
          <?php echo htmlspecialchars($user['id_utilisateur']); ?>
         </td>
         <td class="py-2">
          <?php echo htmlspecialchars($user['nom_utilisateur']); ?>
         </td>
         <td class="py-2 text-green-500">
          <?php echo htmlspecialchars($user['role']); ?>
         </td>
         <td class="py-2">
          <?php echo htmlspecialchars($user['email']); ?>
         </td>
         <td class="px-4 py-2">
            <a href="modifier_user.php?id=<?php echo $user['id_utilisateur']; ?>" 
               class="text-blue-500 hover:underline mr-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="supprimer_user.php?id=<?php echo $user['id_utilisateur']; ?>" 
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"
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
  </div>
 </body>
</html>
