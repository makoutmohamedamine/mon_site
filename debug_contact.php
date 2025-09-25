<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Diagnostic complet du problème de contact</h2>";

// Test 1: Vérification de la connexion
echo "<h3>1️⃣ Test de connexion à la base de données</h3>";
try {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Connexion réussie<br>";
    } else {
        echo "❌ Échec de la connexion<br>";
        exit;
    }
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "<br>";
    exit;
}

// Test 2: Vérification de la table
echo "<br><h3>2️⃣ Vérification de la table contacts</h3>";
try {
    $stmt = $db->query("SHOW TABLES LIKE 'contacts'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'contacts' existe<br>";
        
        // Structure de la table
        $stmt = $db->query("DESCRIBE contacts");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<br>📋 Structure de la table :<br>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
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
    } else {
        echo "❌ Table 'contacts' n'existe pas<br>";
        echo "💡 Créons la table...<br>";
        
        $createTable = "CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            sujet VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($db->exec($createTable)) {
            echo "✅ Table 'contacts' créée avec succès<br>";
        } else {
            echo "❌ Erreur lors de la création de la table<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

// Test 3: Test d'insertion directe
echo "<br><h3>3️⃣ Test d'insertion directe</h3>";
try {
    $testData = [
        'nom' => 'Test Debug ' . date('H:i:s'),
        'email' => 'debug@test.com',
        'sujet' => 'Test de debug',
        'message' => 'Message de test pour debug - ' . date('Y-m-d H:i:s')
    ];
    
    echo "Données de test :<br>";
    echo "<pre>" . print_r($testData, true) . "</pre>";
    
    $query = "INSERT INTO contacts (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute($testData)) {
        echo "✅ <strong>Insertion directe réussie !</strong><br>";
        echo "ID du nouveau message : " . $db->lastInsertId() . "<br>";
    } else {
        echo "❌ <strong>Insertion directe échouée !</strong><br>";
        $errorInfo = $stmt->errorInfo();
        echo "Erreur SQL : " . $errorInfo[2] . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur d'insertion : " . $e->getMessage() . "<br>";
}

// Test 4: Test via le contrôleur
echo "<br><h3>4️⃣ Test via le contrôleur ContactController</h3>";
try {
    require_once 'controllers/ContactController.php';
    
    $testData = [
        'nom' => 'Test Contrôleur ' . date('H:i:s'),
        'email' => 'controller@test.com',
        'sujet' => 'Test via contrôleur',
        'message' => 'Message de test via contrôleur - ' . date('Y-m-d H:i:s')
    ];
    
    $contactController = new ContactController($db);
    $result = $contactController->envoyerMessage($testData);
    
    if ($result['success']) {
        echo "✅ <strong>Test via contrôleur réussi !</strong><br>";
        echo "Message : " . $result['message'] . "<br>";
    } else {
        echo "❌ <strong>Test via contrôleur échoué !</strong><br>";
        echo "Erreur : " . $result['message'] . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur du contrôleur : " . $e->getMessage() . "<br>";
}

// Test 5: Vérification des messages existants
echo "<br><h3>5️⃣ Messages existants dans la table</h3>";
try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM contacts");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Total des messages : " . $count['total'] . "<br>";
    
    if ($count['total'] > 0) {
        $stmt = $db->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 3");
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<br>📧 Derniers messages :<br>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Sujet</th><th>Date</th></tr>";
        foreach ($messages as $message) {
            echo "<tr>";
            echo "<td>{$message['id']}</td>";
            echo "<td>" . htmlspecialchars($message['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($message['email']) . "</td>";
            echo "<td>" . htmlspecialchars($message['sujet']) . "</td>";
            echo "<td>" . (isset($message['created_at']) ? $message['created_at'] : 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

// Test 6: Simulation du formulaire POST
echo "<br><h3>6️⃣ Simulation du formulaire POST</h3>";
try {
    // Simuler $_POST
    $_POST = [
        'nom' => 'Test POST ' . date('H:i:s'),
        'email' => 'post@test.com',
        'sujet' => 'Test POST',
        'message' => 'Message de test via POST - ' . date('Y-m-d H:i:s')
    ];
    
    echo "Données POST simulées :<br>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    $contactController = new ContactController($db);
    $result = $contactController->envoyerMessage($_POST);
    
    if ($result['success']) {
        echo "✅ <strong>Test POST réussi !</strong><br>";
        echo "Message : " . $result['message'] . "<br>";
    } else {
        echo "❌ <strong>Test POST échoué !</strong><br>";
        echo "Erreur : " . $result['message'] . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur POST : " . $e->getMessage() . "<br>";
}

echo "<br><hr><br>";
echo "<h3>🔗 Liens de test :</h3>";
echo "<ul>";
echo "<li><a href='contact_direct.php'>📞 Page de contact directe</a></li>";
echo "<li><a href='test_insert.php'>🧪 Test d'insertion simple</a></li>";
echo "<li><a href='admin.php'>👨‍💼 Administration</a></li>";
echo "<li><a href='index.html'>🏠 Accueil</a></li>";
echo "</ul>";

echo "<br><h3>📝 Instructions :</h3>";
echo "<ol>";
echo "<li>Exécutez ce script pour voir tous les tests</li>";
echo "<li>Si l'insertion directe fonctionne, le problème est dans le formulaire</li>";
echo "<li>Si l'insertion directe échoue, le problème est dans la base de données</li>";
echo "<li>Regardez les messages d'erreur pour identifier le problème exact</li>";
echo "</ol>";
?>
