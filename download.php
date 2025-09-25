<?php
include_once 'config/database.php';
include_once 'controllers/UploadController.php';

$database = new Database();
$db = $database->getConnection();
$uploadController = new UploadController($db);

if(isset($_GET['id'])) {
    $uploadController->telechargerFichier($_GET['id']);
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Fichier non trouvé";
}
?>