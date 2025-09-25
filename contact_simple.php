<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message_sent = false;
$error_message = '';
$success_message = '';

// Traitement du formulaire
if ($_POST && isset($_POST['submit'])) {
    try {
        require_once 'config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception("Erreur de connexion à la base de données");
        }
        
        // Validation simple
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $sujet = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            throw new Exception("Tous les champs sont obligatoires");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Adresse email invalide");
        }
        
        // Insertion directe
        $query = "INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$nom, $email, $sujet, $message])) {
            $message_sent = true;
            $success_message = "Message envoyé avec succès ! ID: " . $db->lastInsertId();
        } else {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erreur SQL : " . $errorInfo[2]);
        }
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Simple - Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    📞 Test Contact Simple
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Version simplifiée pour tester l'insertion
                </p>
            </div>
            
            <?php if ($message_sent): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <strong>✅ Succès !</strong> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>❌ Erreur :</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom complet *</label>
                        <input id="nom" name="nom" type="text" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="sujet" class="block text-sm font-medium text-gray-700">Sujet *</label>
                        <select id="sujet" name="sujet" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionnez un sujet</option>
                            <option value="Question générale" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Question générale') ? 'selected' : ''; ?>>Question générale</option>
                            <option value="Admission" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Admission') ? 'selected' : ''; ?>>Admission</option>
                            <option value="Cours" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Cours') ? 'selected' : ''; ?>>Cours</option>
                            <option value="Support technique" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] == 'Support technique') ? 'selected' : ''; ?>>Support technique</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message *</label>
                        <textarea id="message" name="message" rows="4" required 
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                </div>
                
                <div>
                    <button type="submit" name="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Envoyer le message
                    </button>
                </div>
            </form>
            
            <div class="text-center">
                <a href="debug_contact.php" class="text-blue-600 hover:underline">🔍 Diagnostic complet</a> |
                <a href="admin.php" class="text-blue-600 hover:underline">👨‍💼 Administration</a> |
                <a href="index.html" class="text-blue-600 hover:underline">🏠 Accueil</a>
            </div>
        </div>
    </div>
</body>
</html>

