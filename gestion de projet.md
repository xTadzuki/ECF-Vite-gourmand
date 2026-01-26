# Documentation – Gestion de projet  
**Projet : Vite & Gourmand – Application de traiteur événementiel**  
**Titre professionnel : Développeur Web et Web Mobile (DWWM)**

---

## 1. Contexte du projet

Le projet **Vite & Gourmand** s’inscrit dans le cadre de l’ECF du titre professionnel Développeur Web et Web Mobile.  
Il consiste à concevoir et développer une application web permettant à un traiteur événementiel de gérer ses menus, ses commandes et ses utilisateurs, tout en proposant une expérience fluide aux clients.

Le projet est réalisé **en autonomie**, avec une approche professionnelle inspirée des méthodes agiles.

---

## 2. Méthodologie de gestion de projet

### 2.1 Approche choisie

La gestion de projet repose sur une **méthode agile simplifiée**, adaptée à un projet individuel :

- Découpage du projet en fonctionnalités
- Avancement progressif par itérations
- Tests réguliers des fonctionnalités développées
- Ajustements continus en fonction des contraintes techniques

Cette approche permet :
- Une meilleure maîtrise du temps
- Une réduction des risques techniques
- Une amélioration continue de la qualité du code

---

## 3. Analyse des besoins

### 3.1 Identification des acteurs
- **Visiteur** : consulte les menus et informations générales
- **Client** : passe commande et suit ses prestations
- **Employé** : gère les commandes et les avis
- **Administrateur** : supervise l’application et les utilisateurs

### 3.2 Besoins fonctionnels principaux
- Consultation et filtrage des menus
- Gestion des commandes
- Gestion des utilisateurs et des rôles
- Suivi des statuts de commande
- Sécurité des accès
- Gestion des avis clients
- Statistiques (optionnelles via MongoDB)

---

## 4. Découpage du projet en lots

Le projet a été structuré en plusieurs **lots fonctionnels** :

### Lot 1 – Architecture et base
- Mise en place de l’architecture MVC
- Création du routeur
- Connexion à la base de données MySQL

### Lot 2 – Base de données
- Conception du schéma relationnel
- Respect des formes normales
- Mise en place des relations et clés étrangères

### Lot 3 – Fonctionnalités publiques
- Page d’accueil
- Liste des menus
- Détail d’un menu
- Filtres dynamiques

### Lot 4 – Authentification et sécurité
- Inscription
- Connexion
- Gestion des rôles
- Protection des routes sensibles

### Lot 5 – Commandes
- Création de commande
- Calcul des prix et livraisons
- Gestion du stock
- Historique des statuts

### Lot 6 – Espaces métier
- Espace client
- Espace employé
- Espace administrateur

### Lot 7 – Améliorations et qualité
- Refonte graphique
- Responsive design
- Nettoyage du code
- Gestion des erreurs

---

## 5. Planification et organisation

La planification a été réalisée de manière itérative :

1. Développement des fonctionnalités essentielles
2. Tests manuels à chaque étape
3. Corrections immédiates des bugs
4. Ajout progressif des fonctionnalités avancées
5. Phase finale de stabilisation et documentation

Cette organisation a permis d’avoir une application fonctionnelle rapidement, puis de l’améliorer progressivement.

---

## 6. Outils utilisés

- **Langages** : PHP, HTML, CSS, JavaScript
- **Framework CSS** : Bootstrap
- **Base de données** : MySQL (relationnelle), MongoDB (statistiques optionnelles)
- **Serveur local** : XAMPP
- **Navigateur** : Chrome
- **Gestion du code** : organisation MVC et bonnes pratiques PHP

---

## 7. Gestion des risques

### Risques identifiés
- Problèmes de configuration serveur local
- Erreurs de routes
- Incohérences entre base de données et code
- Complexité de certaines fonctionnalités (statuts, rôles)

### Solutions mises en place
- Tests réguliers sur chaque fonctionnalité
- Messages d’erreurs explicites
- Simplification des fonctionnalités complexes
- Documentation claire pour le déploiement et l’utilisation

---

## 8. Qualité et maintenabilité

Une attention particulière a été portée à :
- La lisibilité du code
- La séparation des responsabilités (MVC)
- L’utilisation de constantes pour les routes
- La validation des données
- La sécurité des accès et des mots de passe

Le projet est structuré pour pouvoir évoluer facilement (ajout de menus, de rôles ou de fonctionnalités).

---

## 9. Bilan du projet

Ce projet a permis de mettre en œuvre :
- Une gestion de projet structurée
- Une approche professionnelle du développement web
- Des compétences techniques et organisationnelles
- Une capacité à analyser, concevoir et livrer une application complète

Le résultat est une application fonctionnelle, cohérente et adaptée aux besoins d’un traiteur événementiel.

---

**Projet réalisé dans le cadre de l’ECF – Titre professionnel DWWM**  
© Vite & Gourmand
