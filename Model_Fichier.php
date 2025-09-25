<?php
class Fichier {
    private $conn;
    private $table = "fichiers";

    public $id;
    public $cours_id;
    public $type_fichier;
    public $nom_fichier;
    public $nom_affichage;
    public $taille_fichier;
    public $extension;
    public $semestre;
    public $module;
    public $date_upload;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Uploader un fichier
    public function upload() {
        $query = "INSERT INTO " . $this->table . " 
                 SET cours_id=:cours_id, type_fichier=:type_fichier, nom_fichier=:nom_fichier, 
                     nom_affichage=:nom_affichage, taille_fichier=:taille_fichier, 
                     extension=:extension, semestre=:semestre, module=:module";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":cours_id", $this->cours_id);
        $stmt->bindParam(":type_fichier", $this->type_fichier);
        $stmt->bindParam(":nom_fichier", $this->nom_fichier);
        $stmt->bindParam(":nom_affichage", $this->nom_affichage);
        $stmt->bindParam(":taille_fichier", $this->taille_fichile);
        $stmt->bindParam(":extension", $this->extension);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":module", $this->module);
        
        return $stmt->execute();
    }

    // Récupérer les fichiers par module et type
    public function getFichiersByModule($module, $type_fichier = null) {
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE module = :module";
        
        if($type_fichier) {
            $query .= " AND type_fichier = :type_fichier";
        }
        
        $query .= " ORDER BY date_upload DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":module", $module);
        
        if($type_fichier) {
            $stmt->bindParam(":type_fichier", $type_fichier);
        }
        
        $stmt->execute();
        return $stmt;
    }

    // Récupérer les fichiers par semestre
    public function getFichiersBySemestre($semestre) {
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE semestre = :semestre 
                 ORDER BY module, type_fichier, date_upload DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":semestre", $semestre);
        $stmt->execute();
        return $stmt;
    }
}
?>