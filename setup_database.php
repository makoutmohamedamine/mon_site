<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Connexion à la base de données réussie !<br><br>";
        
        // Créer les tables nécessaires
        $tables = [
            // Table des cours
            "CREATE TABLE IF NOT EXISTS cours (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(255) NOT NULL,
                description TEXT,
                semestre ENUM('5', '6') NOT NULL,
                duree_cours INT DEFAULT 0,
                duree_td INT DEFAULT 0,
                duree_tp INT DEFAULT 0,
                professeur VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            
            // Table des messages de contact
            "CREATE TABLE IF NOT EXISTS messages_contact (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                sujet VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                statut ENUM('nouveau', 'lu', 'repondu') DEFAULT 'nouveau',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            
            // Table des fichiers uploadés
            "CREATE TABLE IF NOT EXISTS fichiers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom_original VARCHAR(255) NOT NULL,
                nom_fichier VARCHAR(255) NOT NULL,
                chemin VARCHAR(500) NOT NULL,
                taille INT NOT NULL,
                type_mime VARCHAR(100) NOT NULL,
                categorie ENUM('cours', 'td', 'tp', 'pdf', 'autre') DEFAULT 'autre',
                cours_id INT,
                uploaded_by VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE SET NULL
            )",
            
            // Table des actualités
            "CREATE TABLE IF NOT EXISTS actualites (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titre VARCHAR(255) NOT NULL,
                contenu TEXT NOT NULL,
                image VARCHAR(255),
                auteur VARCHAR(255),
                statut ENUM('brouillon', 'publie') DEFAULT 'brouillon',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            
            // Table des utilisateurs (pour l'administration)
            "CREATE TABLE IF NOT EXISTS utilisateurs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                mot_de_passe VARCHAR(255) NOT NULL,
                role ENUM('admin', 'professeur', 'etudiant') DEFAULT 'etudiant',
                statut ENUM('actif', 'inactif') DEFAULT 'actif',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )"
        ];
        
        echo "🔧 Création des tables...<br>";
        
        foreach ($tables as $sql) {
            if ($db->exec($sql)) {
                echo "✅ Table créée avec succès<br>";
            } else {
                echo "❌ Erreur lors de la création d'une table<br>";
            }
        }
        
        // Insérer des données de test pour les cours
        echo "<br>📚 Insertion des données de test...<br>";
        
        $cours_data = [
            ['Développement Web', 'Cours complet sur le développement web moderne', '5', 30, 22, 0, 'Prof. Web'],
            ['E-business, E-logistique, E-commerce', 'Module sur le commerce électronique', '5', 34, 12, 6, 'Prof. E-commerce'],
            ['Système d\'information', 'Gestion et conception des systèmes d\'information', '5', 32, 10, 10, 'Prof. SI'],
            ['Marketing Digital et Digital Analytics', 'Marketing numérique et analyse de données', '5', 34, 12, 7, 'Prof. Marketing'],
            ['Programmation orientée objet', 'Java et concepts POO', '5', 28, 10, 14, 'Prof. MAKROUM El MOSTAFA'],
            ['Base de données', 'Conception et gestion des bases de données', '5', 24, 12, 12, 'Prof. BDD'],
            ['Conduite de projet digital', 'Gestion de projets numériques', '6', 30, 12, 10, 'Prof. Projet'],
            ['Outils d\'aide à la décision et Business Intelligence', 'BI et outils décisionnels', '6', 28, 12, 12, 'Prof. BI'],
            ['Développement Mobile et Internet des objets', 'Applications mobiles et IoT', '6', 28, 0, 24, 'Prof. Mobile'],
            ['Projet de Fin d\'Études', 'Stage et projet final', '6', 0, 0, 0, 'Encadrant']
        ];
        
        $stmt = $db->prepare("INSERT IGNORE INTO cours (nom, description, semestre, duree_cours, duree_td, duree_tp, professeur) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($cours_data as $cours) {
            if ($stmt->execute($cours)) {
                echo "✅ Cours '{$cours[0]}' ajouté<br>";
            }
        }
        
        // Insérer des actualités de test
        echo "<br>📢 Insertion des actualités...<br>";
        
        $actualites_data = [
            [
                'Message de la part du prof du JAVA',
                'Bonjour Makout Mohamed Amine,<br>Je vous prie de bien vouloir partager avec vos camarades le lien du formulaire Google Forms suivant :<br>🔗 <a href="https://forms.gle/imUbsoxLJL25ZTof8" target="_blank" class="text-blue-600 underline">Accéder au formulaire</a><br><br>Ce formulaire a pour objectif de permettre aux étudiants d\'accéder à la classe virtuelle de la filière LST SITD sur Google Classroom.<br>✅ Tous les champs obligatoires doivent être remplis avec soin.<br>⚠ Remarque importante : Seules les adresses Gmail (Google) sont acceptées.<br>📌 Important : Tous les supports de cours, les séries de TD et de TP, ainsi que le dépôt des comptes-rendus et des travaux demandés seront effectués exclusivement via Google Classroom.<br><br>Je vous remercie pour votre collaboration et votre réactivité.<br><br>Bien cordialement,<br>Professeur MAKROUM El MOSTAFA<br>Responsable du module Programmation Orientée Objet',
                'images.jpg',
                'Prof. MAKROUM El MOSTAFA',
                'publie'
            ],
            [
                'Important!!!',
                'Bonjour tous le monde j\'espère que vous allez bien j\'aimerais bien vous informer que les séances de cette semaine de chacun de :<br>Marketing digital<br>Développement web<br>E-commerce E-logistique E-business<br>Système d\'information<br>Base de données<br>❌❌ Sont officiellement annulé ❌❌<br>Sauf la séance de la ✅✅programmation orientée objet (java) ✅✅qui est approuvé de la part du professeur le jeudi Inchalah.',
                'images.jpg',
                'Administration',
                'publie'
            ],
            [
                'Partenariat entreprises',
                'Nouveaux partenariats avec les leaders technologiques pour les stages.',
                'images.jpg',
                'Direction',
                'publie'
            ]
        ];
        
        $stmt = $db->prepare("INSERT IGNORE INTO actualites (titre, contenu, image, auteur, statut) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($actualites_data as $actualite) {
            if ($stmt->execute($actualite)) {
                echo "✅ Actualité '{$actualite[0]}' ajoutée<br>";
            }
        }
        
        echo "<br>🎉 <strong>Installation terminée avec succès !</strong><br>";
        echo "Votre base de données est maintenant prête pour la plateforme académique.<br><br>";
        
        echo "<h3>📊 Résumé de l'installation :</h3>";
        echo "• ✅ Tables créées : cours, messages_contact, fichiers, actualites, utilisateurs<br>";
        echo "• ✅ Données de test insérées<br>";
        echo "• ✅ Base de données opérationnelle<br><br>";
        
        echo "<a href='index.html' style='background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Accéder à la plateforme</a>";
        
    } else {
        echo "❌ Erreur de connexion à la base de données";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>

