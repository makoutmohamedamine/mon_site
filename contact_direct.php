<?php
require_once 'config/database.php';
require_once 'controllers/ContactController.php';

$message_sent = false;
$error_message = '';

// Initialiser la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

if ($_POST) {
    $contactController = new ContactController($db);
    $result = $contactController->envoyerMessage($_POST);
    
    if ($result['success']) {
        $message_sent = true;
    } else {
        $error_message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Plateforme Académique SITD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #7c3aed 50%, #db2777 75%, #dc2626 100%); }
        .text-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-header text-white">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <img src="logo.png" alt="Logo">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Plateforme Académique</h1>
                        <p class="text-sm text-blue-100">Système D'Informations Et Transformation Digital</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.html" class="px-4 py-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                        ← Retour à l'accueil
                    </a>
                    <a href="test_contact.php" class="px-4 py-2 bg-yellow-500 bg-opacity-80 rounded-lg hover:bg-opacity-100 transition-colors">
                        🧪 Test
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main class="py-16">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gradient mb-4">📞 Contact</h1>
                <p class="text-xl text-gray-600">Nous sommes là pour vous accompagner</p>
            </div>

            <?php if ($message_sent): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                    <strong>✅ Succès !</strong> Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-8">
                    <strong>❌ Erreur :</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Debug info -->
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-8">
                <strong>🔍 Debug :</strong> 
                <?php 
                if ($db) {
                    echo "Connexion DB: ✅ OK | ";
                } else {
                    echo "Connexion DB: ❌ Erreur | ";
                }
                
                if ($db) {
                    $checkTable = $db->query("SHOW TABLES LIKE 'contacts'");
                    if ($checkTable->rowCount() > 0) {
                        echo "Table contacts: ✅ Existe";
                    } else {
                        echo "Table contacts: ❌ N'existe pas";
                    }
                }
                ?>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-2xl font-bold mb-6">Informations de contact</h2>
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xl">📍</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">Adresse</h3>
                                <p class="text-gray-600">Settat, Maroc</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-xl">📧</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">Email</h3>
                                <p class="text-gray-600">makout.mohamed.amine@gmail.com</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-xl">📱</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">Téléphone</h3>
                                <p class="text-gray-600">+212 647645823</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Envoyez-nous un message</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                            <input type="text" name="nom" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                            <select name="sujet" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Sélectionnez un sujet</option>
                                <option value="Question générale" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Question générale') ? 'selected' : ''; ?>>Question générale</option>
                                <option value="Admission" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Admission') ? 'selected' : ''; ?>>Admission</option>
                                <option value="Cours" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Cours') ? 'selected' : ''; ?>>Cours</option>
                                <option value="Support technique" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Support technique') ? 'selected' : ''; ?>>Support technique</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea name="message" required rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
