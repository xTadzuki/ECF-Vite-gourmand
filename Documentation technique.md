# Documentation technique  
**Projet : Vite & Gourmand – Application de traiteur événementiel**  
**Titre professionnel : Développeur Web et Web Mobile (DWWM)**

---

## 1. Réflexions initiales technologiques

### 1.1 Choix des technologies

Le projet **Vite & Gourmand** a été conçu comme une application web dynamique avec une architecture claire, maintenable et évolutive.

Les technologies choisies sont :

- **PHP** (programmation serveur)
- **MySQL** (base de données relationnelle)
- **MongoDB** (statistiques – optionnel)
- **HTML / CSS / JavaScript**
- **Bootstrap** (mise en forme et responsive design)

### 1.2 Justification des choix

- **PHP** : langage adapté aux applications web serveur, maîtrisé et compatible avec XAMPP
- **MySQL** : base relationnelle robuste, idéale pour la gestion des commandes, utilisateurs et menus
- **MongoDB** : base NoSQL adaptée aux statistiques et données agrégées
- **Bootstrap** : gain de temps, responsive natif, cohérence visuelle
- **Architecture MVC** : séparation des responsabilités, lisibilité et maintenabilité

---

## 2. Configuration de l’environnement de travail

### 2.1 Environnement local

- Système : Windows
- Serveur local : **XAMPP**
  - Apache
  - MySQL
- Navigateur : Google Chrome

### 2.2 Structure du projet

vite-gourmand/
├─ app/
│ ├─ Controllers/
│ ├─ Models/
│ ├─ Services/
│ ├─ Core/
│ └─ Views/
│ ├─ layouts/
│ ├─ menus/
│ ├─ orders/
│ └─ ...
├─ public/
│ ├─ assets/
│ │ ├─ css/
│ │ ├─ js/
│ │ └─ images/
│ └─ index.php
├─ schema.sql
├─ seed.sql

### 2.3 Configuration serveur

- Apache pointe vers le dossier :
public/
- Le routage est centralisé dans :
public/index.php

---

## 3. Modèle conceptuel de données (MCD)

### 3.1 Principales entités

- **users**
- **roles**
- **menus**
- **themes**
- **diets**
- **orders**
- **order_statuses**
- **order_status_history**
- **dishes**
- **allergens**
- **reviews**

### 3.2 Relations principales

- Un **utilisateur** possède un **rôle**
- Un **menu** est associé à un **thème** et un **régime**
- Une **commande** appartient à un **utilisateur** et un **menu**
- Une **commande** possède plusieurs statuts via un historique
- Un **menu** est composé de plusieurs **plats**
- Un **plat** peut contenir plusieurs **allergènes**

### 3.3 Respect des formes normales

- Séparation des statuts dans une table dédiée (`order_statuses`)
- Tables de liaison (`menu_dish`, `dish_allergen`)
- Pas de données redondantes
- Clés primaires et étrangères systématiques

---

## 4. Diagramme de cas d’utilisation (Use Case)

### 4.1 Acteurs
- Visiteur
- Client
- Employé
- Administrateur

### 4.2 Cas d’utilisation principaux

**Visiteur**
- Consulter les menus
- Voir le détail d’un menu

**Client**
- Créer un compte
- Se connecter
- Passer une commande
- Suivre ses commandes
- Modifier son profil

**Employé**
- Voir les commandes
- Modifier le statut d’une commande
- Annuler une commande
- Gérer les avis clients

**Administrateur**
- Gérer les employés
- Consulter les statistiques
- Superviser l’application

---

## 5. Diagramme de séquence (exemple : passer une commande)

1. Le client se connecte
2. Il sélectionne un menu
3. Il renseigne les informations de commande
4. L’application :
 - vérifie la disponibilité
 - calcule le prix
 - enregistre la commande
5. Le stock est décrémenté
6. Un email de confirmation est envoyé
7. La commande apparaît dans l’espace employé

---

## 6. Architecture logicielle

### 6.1 MVC

- **Models** : accès aux données (MySQL / MongoDB)
- **Views** : affichage HTML
- **Controllers** : logique métier

### 6.2 Services

- Authentification (`Auth`)
- Envoi d’emails (`Mailer`)
- Statistiques (`MongoService`)

### 6.3 Sécurité

- Hashage des mots de passe (`password_hash`)
- Vérification des rôles
- Protection des routes
- Validation des données utilisateurs

---

## 7. Déploiement de l’application

### 7.1 Étapes de déploiement local

1. Copier le projet dans :
C:\xampp\htdocs\
2. Démarrer Apache et MySQL
3. Créer la base `vite_gourmand`
4. Importer `schema.sql`
5. Importer `seed.sql`
6. Accéder à :
http://localhost/vite-gourmand/public/

### 7.2 Configuration BASE_PATH

- `BASE_PATH` est défini une seule fois dans `public/index.php`
- Tous les includes utilisent ce point d’entrée

### 7.3 MongoDB (optionnel)

- Installation du serveur MongoDB
- Activation de l’extension PHP
- Utilisation uniquement pour les statistiques

---

## 8. Tests et validation

- Tests manuels de chaque fonctionnalité
- Vérification des parcours utilisateurs
- Tests de sécurité (accès non autorisés)
- Validation des données en entrée

---

## 9. Évolutivité

L’architecture permet :
- Ajout de nouveaux menus
- Ajout de nouveaux rôles
- Extension des statistiques
- Déploiement futur sur un serveur distant

---

## 10. Conclusion

Cette documentation technique décrit les choix, l’architecture et le fonctionnement de l’application **Vite & Gourmand**.  
Le projet respecte les bonnes pratiques du développement web et répond aux exigences du titre professionnel **DWWM**.

---

© Vite & Gourmand – Documentation technique
