<?php
session_start();
require_once 'config/database.php';

// Simple authentification (à améliorer)
$admin_logged = isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true;

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Authentification simple (à sécuriser davantage)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged'] = true;
        $admin_logged = true;
    } else {
        $error = "Identifiants incorrects";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if ($admin_logged) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Récupérer les messages
    $stmt = $db->prepare("SELECT * FROM contacts ORDER BY id DESC");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Pas de fonctionnalité de marquage pour cette version simple
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Plateforme Académique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <?php if (!$admin_logged): ?>
        <!-- Page de connexion -->
        <div class="min-h-screen flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
                <h1 class="text-2xl font-bold text-center mb-6">🔐 Administration</h1>
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom d'utilisateur</label>
                        <input type="text" name="username" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button type="submit" name="login" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Se connecter
                    </button>
                </form>
                
                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>Identifiants par défaut :</p>
                    <p>Utilisateur: <strong>admin</strong></p>
                    <p>Mot de passe: <strong>admin123</strong></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Dashboard admin -->
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <h1 class="text-2xl font-bold text-gray-900">📊 Administration</h1>
                        <div class="flex items-center space-x-4">
                            <a href="index.html" class="text-blue-600 hover:underline">← Retour au site</a>
                            <a href="?logout=1" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenu principal -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Statistiques -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">📧 Messages reçus</h3>
                        <p class="text-3xl font-bold text-blue-600"><?php echo count($messages); ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">📅 Aujourd'hui</h3>
                        <p class="text-3xl font-bold text-green-600">
                            <?php 
                            $today = date('Y-m-d');
                            echo count(array_filter($messages, function($m) { 
                                return isset($m['created_at']) && date('Y-m-d', strtotime($m['created_at'])) === date('Y-m-d'); 
                            })); 
                            ?>
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">📊 Total</h3>
                        <p class="text-3xl font-bold text-purple-600">
                            <?php echo count($messages); ?>
                        </p>
                    </div>
                </div>

                <!-- Liste des messages -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Messages de contact</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($messages)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Aucun message reçu pour le moment.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($messages as $message): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($message['nom']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="text-blue-600 hover:underline">
                                                    <?php echo htmlspecialchars($message['email']); ?>
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo htmlspecialchars($message['sujet']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Message
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="showMessage(<?php echo htmlspecialchars(json_encode($message)); ?>)" 
                                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                                    Voir
                                                </button>
                                                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" 
                                                   class="text-green-600 hover:text-green-900">
                                                    Répondre
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <!-- Modal pour afficher le message -->
        <div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Détails du message</h3>
                        <button onclick="closeMessage()" class="float-right text-gray-400 hover:text-gray-600">
                            ✕
                        </button>
                    </div>
                    <div class="px-6 py-4" id="messageContent">
                        <!-- Contenu du message -->
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showMessage(message) {
                document.getElementById('messageContent').innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom</label>
                            <p class="mt-1 text-sm text-gray-900">${message.nom}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <a href="mailto:${message.email}" class="text-blue-600 hover:underline">${message.email}</a>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sujet</label>
                            <p class="mt-1 text-sm text-gray-900">${message.sujet}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Message</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">${message.message}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date d'envoi</label>
                            <p class="mt-1 text-sm text-gray-900">${new Date(message.created_at).toLocaleString('fr-FR')}</p>
                        </div>
                    </div>
                `;
                document.getElementById('messageModal').classList.remove('hidden');
            }

            function closeMessage() {
                document.getElementById('messageModal').classList.add('hidden');
            }
        </script>
    <?php endif; ?>
</body>
</html>
