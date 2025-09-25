<?php
require_once 'config/database.php';

echo "<h2>🧪 Test d'insertion directe dans la table contacts</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        echo "❌ Erreur de connexion à la base de données";
        exit;
    }
    
    echo "✅ Connexion à la base de données réussie<br><br>";
    
    // Vérifier la structure de la table
    $stmt = $db->query("DESCRIBE contacts");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>📋 Structure de la table contacts :</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Test d'insertion
    $testData = [
        'nom' => 'Test ' . date('H:i:s'),
        'email' => 'test@example.com',
        'sujet' => 'Test automatique',
        'message' => 'Message de test créé le ' . date('Y-m-d H:i:s')
    ];
    
    echo "<h3>🧪 Test d'insertion :</h3>";
    echo "Données à insérer :<br>";
    echo "<pre>" . print_r($testData, true) . "</pre>";
    
    $query = "INSERT INTO contacts (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute($testData)) {
        echo "✅ <strong>Insertion réussie !</strong><br>";
        echo "ID du nouveau message : " . $db->lastInsertId() . "<br><br>";
        
        // Vérifier l'insertion
        $stmt = $db->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
        $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>📧 Dernier message inséré :</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Champ</th><th>Valeur</th></tr>";
        foreach ($lastMessage as $key => $value) {
            echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
        
    } else {
        echo "❌ <strong>Insertion échouée !</strong><br>";
        $errorInfo = $stmt->errorInfo();
        echo "Erreur SQL : " . $errorInfo[2] . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ <strong>Erreur :</strong> " . $e->getMessage() . "<br>";
}

echo "<br><hr><br>";
echo "<h3>🔗 Liens utiles :</h3>";
echo "<ul>";
echo "<li><a href='contact_direct.php'>📞 Page de contact directe</a></li>";
echo "<li><a href='admin.php'>👨‍💼 Administration</a></li>";
echo "<li><a href='index.html'>🏠 Accueil</a></li>";
echo "</ul>";
?>



// Notification améliorée
function showNotification(message, type = 'info') {
    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-md ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                ✕
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}
