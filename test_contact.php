<?php
require_once 'config/database.php';
require_once 'controllers/ContactController.php';

echo "<h2>🔍 Test de la fonctionnalité de contact</h2>";

try {
    // Test de connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Connexion à la base de données réussie<br>";
        
        // Vérifier si la table contacts existe
        $stmt = $db->query("SHOW TABLES LIKE 'contacts'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table 'contacts' existe<br>";
            
            // Afficher la structure de la table
            $stmt = $db->query("DESCRIBE contacts");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<h3>📋 Structure de la table contacts :</h3>";
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
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
            echo "</table>";
            
            // Test d'insertion d'un message
            echo "<h3>🧪 Test d'insertion d'un message :</h3>";
            
            $testData = [
                'nom' => 'Test Utilisateur',
                'email' => 'test@example.com',
                'sujet' => 'Test de contact',
                'message' => 'Ceci est un message de test pour vérifier le fonctionnement.'
            ];
            
            $contactController = new ContactController($db);
            $result = $contactController->envoyerMessage($testData);
            
            if ($result['success']) {
                echo "✅ Test d'insertion réussi : {$result['message']}<br>";
                
                // Vérifier que le message a bien été inséré
                $stmt = $db->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
                $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($lastMessage) {
                    echo "<h3>📧 Dernier message inséré :</h3>";
                    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                    echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Sujet</th><th>Message</th><th>Date</th></tr>";
                    echo "<tr>";
                    echo "<td>{$lastMessage['id']}</td>";
                    echo "<td>{$lastMessage['nom']}</td>";
                    echo "<td>{$lastMessage['email']}</td>";
                    echo "<td>{$lastMessage['sujet']}</td>";
                    echo "<td>{$lastMessage['message']}</td>";
                    echo "<td>{$lastMessage['created_at']}</td>";
                    echo "</tr>";
                    echo "</table>";
                }
            } else {
                echo "❌ Test d'insertion échoué : {$result['message']}<br>";
            }
            
        } else {
            echo "❌ Table 'contacts' n'existe pas<br>";
            echo "<p>💡 <strong>Solution :</strong> Exécutez d'abord <a href='setup_database.php'>setup_database.php</a> pour créer les tables.</p>";
        }
        
    } else {
        echo "❌ Erreur de connexion à la base de données<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

echo "<br><hr><br>";
echo "<h3>🔗 Liens utiles :</h3>";
echo "<ul>";
echo "<li><a href='setup_database.php'>🔧 Configurer la base de données</a></li>";
echo "<li><a href='views/contact.php'>📞 Page de contact</a></li>";
echo "<li><a href='admin.php'>👨‍💼 Administration</a></li>";
echo "<li><a href='index.html'>🏠 Accueil</a></li>";
echo "</ul>";
?>
