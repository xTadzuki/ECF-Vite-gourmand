# ECF-Vite-gourmand
Projet ECF – Titre professionnel
Ce document décrit la démarche à suivre pour installer et lancer l’application **Vite & Gourmand** en local sur un environnement Windows avec XAMPP.

---

## 1. Prérequis

- Système : **Windows**
- **XAMPP** (Apache + MySQL/MariaDB)
- Navigateur web (Chrome, Firefox…)
- (Optionnel) **MongoDB** si les statistiques admin sont utilisées

---

## 2. Installation du projet

1. Copier le dossier du projet dans le répertoire :
C:\xampp\htdocs\

2. L’arborescence attendue est :
C:\xampp\htdocs\vite-gourmand\

---

## 3. Démarrage des services

1. Ouvrir **XAMPP Control Panel**
2. Démarrer les services :
- **Apache**
- **MySQL**

---

## 4. Base de données MySQL

### 4.1 Création de la base
1. Accéder à phpMyAdmin :
http://localhost/phpmyadmin
2. Créer une base de données :
- Nom : `vite_gourmand`
- Interclassement : `utf8mb4_general_ci`

### 4.2 Import du schéma
1. Sélectionner la base `vite_gourmand`
2. Onglet **Importer**
3. Importer le fichier :
schema.sql

### 4.3 Import des données de démonstration
1. Onglet **Importer**
2. Importer le fichier :
seed.sql

---

## 5. Configuration de la connexion MySQL

Vérifier le fichier :
app/Models/Database.php

Configuration par défaut sous XAMPP :
- Hôte : `127.0.0.1`
- Base : `vite_gourmand`
- Utilisateur : `root`
- Mot de passe : *(vide)*

---

## 6. Lancement de l’application

L’application est accessible via Apache à l’URL suivante :
http://localhost/vite-gourmand/public/

Exemples de routes :
- Accueil :
http://localhost/vite-gourmand/public/?r=home
- Menus :
http://localhost/vite-gourmand/public/?r=menus

---

## 7. Comptes et tests

### 7.1 Inscription
http://localhost/vite-gourmand/public/?r=register

### 7.2 Connexion
http://localhost/vite-gourmand/public/?r=login

Les comptes de test (utilisateur, employé, admin) sont fournis dans le fichier `seed.sql`.

---

## 8. MongoDB (optionnel)

Certaines fonctionnalités statistiques (espace admin) utilisent MongoDB.

### 8.1 Installation MongoDB
- Installer **MongoDB Community Server**
- Démarrer le service MongoDB

### 8.2 Extension PHP MongoDB
1. Copier `php_mongodb.dll` dans :
C:\xampp\php\ext\
2. Activer l’extension dans :
C:\xampp\php\php.ini
Ajouter :
extension=mongodb
3. Redémarrer Apache

### 8.3 Désactivation de MongoDB (si non utilisé)
Si MongoDB n’est pas disponible, les appels peuvent être neutralisés dans `MongoService` afin d’éviter toute erreur.

---

## 9. Problèmes courants

### Page XAMPP au lieu du site
- Toujours accéder au site via :
http://localhost/vite-gourmand/public/
- Éviter les liens commençant par `/` dans les vues.
- Utiliser uniquement des liens relatifs :
- `?r=menus`
- `?r=menu_show&id=1`

### Base inconnue
- Vérifier que la base `vite_gourmand` existe
- Vérifier l’import de `schema.sql`

### Menu introuvable
- Vérifier que l’URL contient bien un `id`
- Vérifier que la table `menus` contient des données

### Erreur BASE_PATH
- `BASE_PATH` doit être défini une seule fois, dans `public/index.php`

---

## 10. Structure du projet

vite-gourmand/
├─ app/
│ ├─ Controllers/
│ ├─ Models/
│ ├─ Services/
│ ├─ Views/
│ │ ├─ layouts/
│ │ ├─ menus/
│ │ └─ ...
├─ public/
│ ├─ assets/
│ │ ├─ css/
│ │ ├─ js/
│ │ └─ images/
│ └─ index.php
├─ schema.sql
└─ seed.sql

---

## 11. Accès rapides

- phpMyAdmin :
http://localhost/phpmyadmin
- Application :
http://localhost/vite-gourmand/public/

---

© Vite & Gourmand — Déploiement local
