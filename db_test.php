<?php
header('Content-Type: application/json');

include_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo json_encode([
            "success" => true,
            "message" => "✅ Connexion à la base de données réussie!",
            "database" => "platforme_academique"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "❌ Échec de la connexion"
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "❌ Erreur: " . $e->getMessage()
    ]);
}
?>