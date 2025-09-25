# 🎓 Plateforme Académique SITD

Une plateforme moderne pour la filière **Systèmes d'Information et Transformation Digitale (SITD)** avec une interface utilisateur élégante et des fonctionnalités complètes.

## ✨ Fonctionnalités

### 🏠 Interface Utilisateur
- **Design moderne** avec Tailwind CSS
- **Navigation fluide** entre les sections
- **Responsive design** pour tous les appareils
- **Animations** et effets visuels

### 📚 Gestion des Cours
- **Catalogue complet** des modules S5 et S6
- **Filtrage par semestre**
- **Informations détaillées** (durée, professeur, etc.)
- **Interface intuitive** pour la navigation

### 📞 Système de Contact
- **Formulaire de contact** fonctionnel
- **Validation des données** côté serveur
- **Interface d'administration** pour gérer les messages
- **Statuts des messages** (nouveau, lu, répondu)

### 🗄️ Base de Données
- **Structure complète** avec toutes les tables nécessaires
- **Données de test** pré-remplies
- **Relations** entre les entités
- **Sécurité** avec requêtes préparées

## 🚀 Installation

### Prérequis
- **XAMPP** (Apache + MySQL + PHP)
- **Navigateur web** moderne

### Étapes d'installation

1. **Cloner le projet** dans le dossier `htdocs` de XAMPP
   ```
   C:\xampp\htdocs\site_web\
   ```

2. **Démarrer XAMPP**
   - Lancer Apache et MySQL
   - Accéder à phpMyAdmin

3. **Créer la base de données**
   - Nom : `platforme_academique`
   - Charset : `utf8mb4_unicode_ci`

4. **Configurer la base de données**
   - Ouvrir `setup_database.php` dans votre navigateur
   - Suivre les instructions pour créer les tables
   - Les données de test seront automatiquement insérées

5. **Accéder à la plateforme**
   - URL : `http://localhost/site_web/`
   - Interface principale : `index.html`

## 📁 Structure du Projet

```
site_web/
├── config/
│   └── database.php          # Configuration de la base de données
├── controllers/
│   ├── ContactController.php # Gestion des messages de contact
│   └── UploadController.php  # Gestion des uploads
├── models/
│   ├── Cours.php            # Modèle pour les cours
│   └── api/                 # Modèles API
├── views/
│   ├── accueil.php          # Page d'accueil
│   ├── cours.php            # Page des cours
│   └── contact.php          # Page de contact
├── index.php                # Routeur principal
├── index.html               # Interface utilisateur
├── admin.php                # Interface d'administration
├── setup_database.php       # Script d'installation
└── README.md               # Documentation
```

## 🔧 Configuration

### Base de Données
Modifiez `config/database.php` si nécessaire :
```php
private $host = "localhost";
private $db_name = "platforme_academique";
private $username = "root";
private $password = "";
```

### Administration
- **URL** : `http://localhost/site_web/admin.php`
- **Identifiants par défaut** :
  - Utilisateur : `admin`
  - Mot de passe : `admin123`

## 📊 Tables de la Base de Données

### `cours`
- Informations sur les modules (nom, description, semestre, durée, professeur)

### `messages_contact`
- Messages reçus via le formulaire de contact

### `fichiers`
- Gestion des fichiers uploadés (TD, TP, PDF)

### `actualites`
- Actualités et annonces

### `utilisateurs`
- Gestion des utilisateurs (admin, professeur, étudiant)

## 🎨 Personnalisation

### Couleurs et Thème
Modifiez les classes Tailwind CSS dans `index.html` :
```css
.gradient-header { 
    background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #7c3aed 50%, #db2777 75%, #dc2626 100%); 
}
```

### Contenu
- **Actualités** : Modifiez la section actualités dans `index.html`
- **Cours** : Ajoutez/modifiez les cours via la base de données
- **Informations** : Mettez à jour les coordonnées de contact

## 🔒 Sécurité

### Mesures Implémentées
- ✅ **Validation des données** côté serveur
- ✅ **Requêtes préparées** pour éviter les injections SQL
- ✅ **Échappement HTML** pour prévenir les XSS
- ✅ **Validation des emails**
- ✅ **Gestion des erreurs**

### Recommandations
- 🔐 **Changer les mots de passe** par défaut
- 🔐 **Utiliser HTTPS** en production
- 🔐 **Limiter les uploads** de fichiers
- 🔐 **Sauvegarder régulièrement** la base de données

## 🚀 Fonctionnalités Futures

### Améliorations Possibles
- [ ] **Système d'authentification** complet
- [ ] **Upload de fichiers** pour les cours
- [ ] **Système de notifications**
- [ ] **Chat en temps réel**
- [ ] **API REST** pour mobile
- [ ] **Système de notes** et évaluations
- [ ] **Planning interactif**
- [ ] **Forum de discussion**

## 📞 Support

### Contact
- **Email** : makout.mohamed.amine@gmail.com
- **Téléphone** : +212 647645823

### Problèmes Courants

**Erreur de connexion à la base de données**
- Vérifiez que MySQL est démarré dans XAMPP
- Vérifiez les paramètres dans `config/database.php`

**Page blanche**
- Activez l'affichage des erreurs PHP
- Vérifiez les logs d'erreur Apache

**Styles non chargés**
- Vérifiez la connexion internet (Tailwind CSS via CDN)
- Ou téléchargez Tailwind CSS localement

## 📄 Licence

Ce projet est développé pour la filière SITD. Tous droits réservés.

---

**🎓 Bonne utilisation de votre plateforme académique !**

