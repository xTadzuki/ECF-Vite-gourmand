# Vite Gourmand — ECF (Traiteur événementiel)

Vite Gourmand est une application web de traiteur événementiel : l’utilisateur peut parcourir des menus, filtrer rapidement selon ses besoins (budget, thème, régime…), consulter le détail d’un menu (plats + allergènes), et passer commande.  
Côté administration, l’application propose un tableau de bord avec statistiques, ainsi qu’une gestion des menus (création / édition / suppression).

Ce projet a été réalisé dans le cadre de l’ECF afin de démontrer : base de données SQL, composants d’accès aux données (DAL), composants métier, interface responsive, DOM + requêtes asynchrones, et documentation de déploiement.

---

## Fonctionnalités

### Côté utilisateur
- Liste des menus
- Filtrage **dynamique** (sans rechargement) via requête asynchrone
- Détail d’un menu : plats associés + allergènes
- Interface responsive (desktop / tablette / mobile)

### Côté admin
- Dashboard avec graphiques (statistiques)
- CRUD Menus (create / edit / delete)
- Données de démo complètes (menus, plats, allergènes, avis)

---

## Stack technique
- **Back-end** : PHP (architecture MVC)
- **Base de données** : MySQL
- **Front-end** : HTML / CSS / JavaScript
- **Asynchrone** : `fetch()` + endpoint JSON
- **Graphiques** : Chart.js (CDN)
- **Environnement local** : XAMPP (Apache + MySQL)

---

## Architecture du projet (repères)
- `public/` : point d’entrée (front controller), assets (CSS/JS/images)
- `app/Controllers/` : contrôleurs (logique de routes + orchestration)
- `app/Models/` : DAL / accès aux données (PDO, requêtes préparées)
- `app/Views/` : vues (pages)
- `sql/` : scripts SQL (`schema.sql`, `seed.sql`)
- `docs/` : documentation (UML, déploiement, maquettes)

---

## Fonction “dynamique” (preuve ECF)
Le filtrage des menus est géré **sans recharger la page** :
1) le front déclenche un `fetch()` sur un endpoint JSON  
2) la réponse est rendue en DOM (réécriture de la liste)

- Endpoint : `/?r=menus_json` (retour JSON)
- Script front : `public/assets/js/...` (fetch + render)

Pour vérifier :
- ouvrir DevTools → **Network**
- modifier un filtre → voir la requête JSON en 200 + réponse JSON

---

## Base de données (SQL)
Les scripts sont fournis dans `sql/` :
- `schema.sql` : création des tables
- `seed.sql` : jeu de données de démonstration (menus, plats, allergènes, avis, etc.)

### Import (phpMyAdmin)
1) Créer la base : `vite_gourmand`
2) Importer `schema.sql`
3) Importer `seed.sql`


---

## Installation en local (XAMPP)
1) Placer le projet dans `htdocs/`  
2) Démarrer Apache + MySQL depuis XAMPP  
3) Importer `schema.sql` puis `seed.sql` dans la base `vite_gourmand`  
4) Accéder au site :
- `http://localhost/vite-gourmand/public/`

---

## Accès de démonstration
Comptes créés par le seed :

- Utilisateur : `user@test.fr` / `Password!123`
- Employé : `employee@test.fr` / `Password!123`
- Admin : `admin@test.fr` / `Password!123`

---

## UML & Maquettes
- UML (Use case / Classes / Sequence) : `docs/uml/`
- Maquettes (exports PNG ou lien Figma) : `docs/mockups/`

---

## Déploiement
Application déployée : **(mettre ici l’URL)**  
Procédure détaillée : `docs/deployment.md`

Checklist post-déploiement (recommandée) :
- Accueil OK
- Liste menus OK
- Filtre async OK (Network 200 JSON)
- Détail menu OK (plats + allergènes)
- Admin dashboard OK (graphiques)

---

## Choix techniques (résumé)
- Utilisation de **requêtes préparées PDO** pour sécuriser les requêtes avec paramètres
- Mise en place d’une couche DAL (Models) pour centraliser le SQL
- Front dynamique via `fetch()` + rendu DOM
- Design tokens CSS (`--vg-*`) pour garder une charte cohérente et maintenir le responsive

---

## Axes d’amélioration (si plus de temps)
- Authentification renforcée (RBAC plus strict, CSRF si formulaires critiques)
- Pagination côté menus + cache côté API JSON
- Tests (unitaires sur la DAL et tests fonctionnels de routes)
- Gestion complète des uploads (images menus) + validation avancée
- Logs applicatifs et monitoring

---

## Licence
Projet pédagogique réalisé dans le cadre d’une évaluation (ECF).
