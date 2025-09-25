<?php
session_start();
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Routeur simple
$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

switch($page) {
    case 'cours':
        // Inclure le modèle Cours depuis l'emplacement correct
        if (file_exists(__DIR__ . '/config/models/Cours.php')) {
            require_once __DIR__ . '/config/models/Cours.php';
        } elseif (file_exists(__DIR__ . '/models/Cours.php')) {
            require_once __DIR__ . '/models/Cours.php';
        }
        $coursModel = new Cours($db);
        $cours = $coursModel->read()->fetchAll(PDO::FETCH_ASSOC);
        break;
        
    case 'contact':
        // Inclure le contrôleur uniquement si nécessaire
        require_once __DIR__ . '/controllers/ContactController.php';
        $message_sent = false;
        $error_message = '';
        
        if($_POST) {
            $contactController = new ContactController($db);
            $result = $contactController->envoyerMessage($_POST);
            
            if ($result['success']) {
                $message_sent = true;
            } else {
                $error_message = $result['message'];
            }
        }
        break;
        
    case 'api-cours':
        // Inclure le modèle Cours depuis l'emplacement correct
        if (file_exists(__DIR__ . '/config/models/Cours.php')) {
            require_once __DIR__ . '/config/models/Cours.php';
        } elseif (file_exists(__DIR__ . '/models/Cours.php')) {
            require_once __DIR__ . '/models/Cours.php';
        }
        header('Content-Type: application/json');
        $coursModel = new Cours($db);
        $cours = $coursModel->read()->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cours);
        exit;
}

// Inclure la vue correspondante
include __DIR__ . '/views/' . $page . '.php';
?>