<?php
require_once 'config/database.php';

echo "<h2>🔍 Vérification de la structure de la table 'contacts'</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Connexion à la base de données réussie<br><br>";
        
        // Vérifier si la table contacts existe
        $stmt = $db->query("SHOW TABLES LIKE 'contacts'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table 'contacts' existe<br><br>";
            
            // Afficher la structure de la table
            $stmt = $db->query("DESCRIBE contacts");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>📋 Structure actuelle de la table 'contacts' :</h3>";
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr style='background-color: #f0f0f0;'><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
            foreach ($columns as $column) {
                echo "<tr>";
                echo "<td><strong>{$column['Field']}</strong></td>";
                echo "<td>{$column['Type']}</td>";
                echo "<td>{$column['Null']}</td>";
                echo "<td>{$column['Key']}</td>";
                echo "<td>{$column['Default']}</td>";
                echo "<td>{$column['Extra']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Vérifier les données existantes
            $stmt = $db->query("SELECT COUNT(*) as total FROM contacts");
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<br><strong>📊 Nombre de messages dans la table :</strong> {$count['total']}<br><br>";
            
            if ($count['total'] > 0) {
                echo "<h3>📧 Derniers messages :</h3>";
                $stmt = $db->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 5");
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                echo "<tr style='background-color: #f0f0f0;'>";
                foreach (array_keys($messages[0]) as $header) {
                    echo "<th>{$header}</th>";
                }
                echo "</tr>";
                
                foreach ($messages as $message) {
                    echo "<tr>";
                    foreach ($message as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // Test d'insertion
            echo "<br><h3>🧪 Test d'insertion :</h3>";
            $testData = [
                'nom' => 'Test ' . date('H:i:s'),
                'email' => 'test@example.com',
                'sujet' => 'Test automatique',
                'message' => 'Message de test créé le ' . date('Y-m-d H:i:s')
            ];
            
            $query = "INSERT INTO contacts (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
            $stmt = $db->prepare($query);
            
            if ($stmt->execute($testData)) {
                echo "✅ Test d'insertion réussi !<br>";
                echo "📧 Message de test inséré avec succès.<br>";
            } else {
                echo "❌ Test d'insertion échoué.<br>";
                $errorInfo = $stmt->errorInfo();
                echo "Erreur : " . $errorInfo[2] . "<br>";
            }
            
        } else {
            echo "❌ Table 'contacts' n'existe pas<br>";
            echo "<p>💡 <strong>Solution :</strong> Créez la table 'contacts' avec les colonnes suivantes :</p>";
            echo "<pre style='background-color: #f5f5f5; padding: 10px; border-radius: 5px;'>";
            echo "CREATE TABLE contacts (\n";
            echo "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
            echo "    nom VARCHAR(255) NOT NULL,\n";
            echo "    email VARCHAR(255) NOT NULL,\n";
            echo "    sujet VARCHAR(255) NOT NULL,\n";
            echo "    message TEXT NOT NULL,\n";
            echo "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
            echo ");";
            echo "</pre>";
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
echo "<li><a href='contact_direct.php'>📞 Page de contact directe</a></li>";
echo "<li><a href='test_contact.php'>🧪 Test complet</a></li>";
echo "<li><a href='admin.php'>👨‍💼 Administration</a></li>";
echo "<li><a href='index.html'>🏠 Accueil</a></li>";
echo "</ul>";
?>

