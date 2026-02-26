START TRANSACTION;

-- ROLES
INSERT INTO roles (name) VALUES ('user'), ('employee'), ('admin');

-- THEMES / DIETS
INSERT INTO themes (name) VALUES
('Noël'), ('Pâques'), ('Classique'), ('Entreprise'), ('Mariage'), ('Anniversaire'), ('Cocktail');

INSERT INTO diets (name) VALUES
('Classique'), ('Végétarien'), ('Vegan');

-- ALLERGENES
INSERT INTO allergens (name) VALUES
('Gluten'),
('Lait'),
('Œufs'),
('Poisson'),
('Fruits à coque'),
('Soja'),
('Moutarde'),
('Sésame');

-- HORAIRES
INSERT INTO opening_hours (day_of_week, open_time, close_time, is_closed) VALUES
(1,'09:00:00','18:00:00',0),
(2,'09:00:00','18:00:00',0),
(3,'09:00:00','18:00:00',0),
(4,'09:00:00','18:00:00',0),
(5,'09:00:00','18:00:00',0),
(6,'09:00:00','12:00:00',0),
(7,NULL,NULL,1);

-- USERS (démo)
-- Mot de passe : Password!123 (hash bcrypt)
INSERT INTO users (role_id, firstname, lastname, email, phone, address, password) VALUES
(1,'Marie','Durand','user@test.fr','0612345678','10 rue de Bordeaux','$2y$10$jhKLPRW9yqoQJ/r5qmlK/O.iVAwpNRcoEM0y8ls8yb14E.G.dn.qa'),
(2,'Julie','Martin','employee@test.fr','0622334455','20 avenue de la Gironde','$2y$10$jhKLPRW9yqoQJ/r5qmlK/O.iVAwpNRcoEM0y8ls8yb14E.G.dn.qa'),
(3,'José','Admin','admin@test.fr','0699887766','30 boulevard Maritime','$2y$10$jhKLPRW9yqoQJ/r5qmlK/O.iVAwpNRcoEM0y8ls8yb14E.G.dn.qa');

-- MENUS (14 menus)
-- theme_id: Noël=1, Pâques=2, Classique=3, Entreprise=4, Mariage=5, Anniversaire=6, Cocktail=7
-- diet_id : Classique=1, Végétarien=2, Vegan=3
INSERT INTO menus (title, description, price, min_people, max_price, stock, theme_id, diet_id) VALUES
('Menu de Noël – Tradition & Élégance',
'Menu festif idéal pour familles & entreprises. Entrées/Plats/Desserts au choix. Allergènes consultables sur chaque plat.',
39.90, 10, 59.90, 12, 1, 1),

('Menu de Noël – Végétarien Festif',
'Menu végétarien de fête, généreux et raffiné. Entrées/Plats/Desserts au choix. Allergènes consultables sur chaque plat.',
34.90, 8, 49.90, 10, 1, 2),

('Menu de Pâques – Printemps Gourmand',
'Menu de saison : asperges, œufs, plat du printemps, dessert chocolat. Options disponibles.',
32.90, 10, 49.90, 14, 2, 1),

('Menu Classique – Saveurs du Terroir',
'Menu convivial : entrées, plat, dessert. 2–3 options par catégorie.',
24.90, 8, 39.90, 25, 3, 1),

('Menu Classique – Vegan & Énergie',
'Menu 100% vegan : entrée, plat, dessert. 2 options par catégorie.',
22.90, 6, 34.90, 18, 3, 3),

('Menu Classique – Végétarien Fraîcheur',
'Menu végétarien : entrée, plat, dessert. 2 options par catégorie.',
21.90, 6, 32.90, 20, 3, 2),

('Menu Entreprise – Buffet Pro',
'Buffet froid & chaud idéal pour réunions, séminaires et afterworks. 2–3 options par catégorie.',
19.90, 15, 29.90, 40, 4, 1),

('Menu Entreprise – Cocktail Déjeuner',
'Mini-bouchées, verrines et pièce sucrée. Parfait pour événements corporate. 2–3 options au choix.',
16.90, 20, 24.90, 50, 4, 1),

('Menu Mariage – Prestige',
'Entrée raffinée, plat signature, dessert de saison. Options disponibles pour s’adapter aux invités.',
49.90, 40, 69.90, 10, 5, 1),

('Menu Mariage – Végétarien Chic',
'Alternative végétarienne premium, gourmande et colorée. Options disponibles.',
44.90, 35, 64.90, 10, 5, 2),

('Menu Anniversaire – Convivial',
'Menu festif : assortiment d’entrées, plat familial, desserts gourmands. 2–3 options.',
27.90, 12, 39.90, 25, 6, 1),

('Menu Anniversaire – Kids Party',
'Mini portions adaptées, saveurs simples, dessert gourmand. Options kids-friendly.',
14.90, 10, 19.90, 30, 6, 1),

('Menu Cocktail – Apéritif Dînatoire',
'Assortiment de pièces salées + sucrées pour un cocktail dînatoire réussi. Plusieurs options.',
21.90, 20, 32.90, 35, 7, 1),

('Menu Été – Fraîcheur & Grillades',
'Menu estival : fraîcheur, grillades, dessert léger. Options disponibles.',
29.90, 15, 44.90, 20, 3, 1);

-- MENU IMAGES
INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/noel.jpg', 'Menu de Noël', 1
FROM menus m WHERE m.title='Menu de Noël – Tradition & Élégance';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/noel-vege.jpg', 'Menu de Noël végétarien', 1
FROM menus m WHERE m.title='Menu de Noël – Végétarien Festif';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/paques.jpg', 'Menu de Pâques', 1
FROM menus m WHERE m.title='Menu de Pâques – Printemps Gourmand';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/entreprise.jpg', 'Menu Entreprise', 1
FROM menus m WHERE m.title='Menu Entreprise – Buffet Pro';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/cocktail.jpg', 'Menu Cocktail', 1
FROM menus m WHERE m.title='Menu Entreprise – Cocktail Déjeuner';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/mariage.jpg', 'Menu Mariage', 1
FROM menus m WHERE m.title='Menu Mariage – Prestige';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/mariage-vege.jpg', 'Menu Mariage végétarien', 1
FROM menus m WHERE m.title='Menu Mariage – Végétarien Chic';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/anniversaire.jpg', 'Menu Anniversaire', 1
FROM menus m WHERE m.title='Menu Anniversaire – Convivial';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/kids.jpg', 'Menu Kids', 1
FROM menus m WHERE m.title='Menu Anniversaire – Kids Party';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/cocktail.jpg', 'Menu Cocktail', 1
FROM menus m WHERE m.title='Menu Cocktail – Apéritif Dînatoire';

INSERT INTO menu_images (menu_id, image_path, alt_text, sort_order)
SELECT m.id, 'assets/img/menus/ete.jpg', 'Menu Été', 1
FROM menus m WHERE m.title='Menu Été – Fraîcheur & Grillades';

-- DISHES (avec options 2–3 choix par catégorie)
INSERT INTO dishes (name, type) VALUES
-- Noël (classique)
('Verrine saumon fumé','entrée'),
('Foie gras mi-cuit','entrée'),
('Velouté potimarron','entrée'),
('Suprême de volaille aux morilles','plat'),
('Gratin dauphinois','plat'),
('Légumes rôtis d’hiver','plat'),
('Bûche chocolat-noisette','dessert'),
('Mignardises','dessert'),

-- Noël (options supplémentaires)
('Option Entrée – Saint-Jacques snackées','entrée'),
('Option Plat – Filet de bœuf sauce truffe','plat'),
('Option Dessert – Pavlova fruits rouges','dessert'),

-- Noël végétarien
('Wellington végétarien','plat'),
('Bûche vanille-framboise','dessert'),
('Option Entrée – Risotto potimarron & noisettes','entrée'),
('Option Plat – Parmentier végétal aux champignons','plat'),
('Option Dessert – Poire pochée & chocolat','dessert'),

-- Pâques
('Œufs mimosa','entrée'),
('Asperges vinaigrette','entrée'),
('Plat de Pâques (agneau ou option)','plat'),
('Option Plat – Filet de poisson printanier','plat'),
('Nid de Pâques chocolat','dessert'),
('Option Dessert – Cheesecake citron','dessert'),

-- Classique
('Salade gourmande','entrée'),
('Quiche légumes','entrée'),
('Rôti de porc moutarde','plat'),
('Option Plat – Poulet rôti & jus court','plat'),
('Tarte aux pommes','dessert'),
('Moelleux chocolat','dessert'),

-- Vegan
('Houmous & crudités','entrée'),
('Option Entrée – Salade quinoa & herbes','entrée'),
('Dahl lentilles coco','plat'),
('Option Plat – Curry de légumes au lait de coco','plat'),
('Mousse chocolat vegan','dessert'),
('Option Dessert – Compote pomme-cannelle','dessert'),

-- Végé classique
('Lasagnes de légumes','plat'),
('Option Plat – Risotto champignons','plat'),
('Panna cotta vanille','dessert'),
('Option Dessert – Tiramisu sans alcool','dessert'),

-- Entreprise / Mariage / Cocktail / Été
('Plateau mini-sandwichs','entrée'),
('Verrines légumes croquants','entrée'),
('Option Entrée – Mini wraps poulet','entrée'),
('Option Entrée – Mini wraps veggie','entrée'),

('Saumon rôti sauce citron','plat'),
('Poulet basquaise','plat'),
('Risotto champignons','plat'),
('Option Plat – Bœuf mijoté & écrasé de pommes de terre','plat'),

('Cheesecake fruits rouges','dessert'),
('Tiramisu café','dessert'),
('Assortiment mini-pâtisseries','dessert'),
('Option Dessert – Mousse chocolat praliné','dessert'),

-- Été (options)
('Option Entrée – Salade tomate mozzarella','entrée'),
('Option Plat – Brochettes de poulet marinées','plat'),
('Option Plat – Légumes grillés & halloumi','plat'),
('Option Dessert – Salade de fruits frais','dessert');

-- ALLERGENS : mapping complet

-- Poisson + Lait
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Verrine saumon fumé' AND a.name IN ('Poisson','Lait');

-- Gluten + Lait + Œufs
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Foie gras mi-cuit' AND a.name IN ('Gluten','Lait','Œufs');

-- Lait
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Gratin dauphinois' AND a.name IN ('Lait');

-- Œufs + Moutarde
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Œufs mimosa' AND a.name IN ('Œufs','Moutarde');

-- Moutarde
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Rôti de porc moutarde' AND a.name IN ('Moutarde');

-- Sésame
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Houmous & crudités' AND a.name IN ('Sésame');

-- Soja
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Mousse chocolat vegan' AND a.name IN ('Soja');

-- Desserts / pâtisserie : Gluten + Œufs + Lait (générique)
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name IN ('Bûche chocolat-noisette','Moelleux chocolat','Assortiment mini-pâtisseries','Tiramisu café','Cheesecake fruits rouges','Option Dessert – Mousse chocolat praliné')
  AND a.name IN ('Gluten','Œufs','Lait');

-- Fruits à coque (noisettes / praliné)
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name IN ('Option Entrée – Risotto potimarron & noisettes','Bûche chocolat-noisette','Option Dessert – Mousse chocolat praliné')
  AND a.name IN ('Fruits à coque');

-- Quiche / lasagnes / panna cotta : Gluten + Lait + Œufs (selon recettes)
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name IN ('Quiche légumes','Lasagnes de légumes','Panna cotta vanille')
  AND a.name IN ('Gluten','Lait','Œufs');

-- Sandwichs / wraps (gluten) + option lait (wraps poulet souvent sauce)
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name IN ('Plateau mini-sandwichs','Option Entrée – Mini wraps poulet','Option Entrée – Mini wraps veggie')
  AND a.name IN ('Gluten');

-- Saumon rôti : Poisson
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Saumon rôti sauce citron' AND a.name IN ('Poisson');

-- Tiramisu “sans alcool” : Gluten + Œufs + Lait
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Option Dessert – Tiramisu sans alcool' AND a.name IN ('Gluten','Œufs','Lait');

-- Salade tomate mozzarella : Lait
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Option Entrée – Salade tomate mozzarella' AND a.name IN ('Lait');

-- Noël – Tradition & Élégance
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu de Noël – Tradition & Élégance'
  AND d.name IN (
    'Verrine saumon fumé','Foie gras mi-cuit','Velouté potimarron','Option Entrée – Saint-Jacques snackées',
    'Suprême de volaille aux morilles','Option Plat – Filet de bœuf sauce truffe','Gratin dauphinois','Légumes rôtis d’hiver',
    'Bûche chocolat-noisette','Option Dessert – Pavlova fruits rouges','Mignardises'
  );

-- Noël – Végétarien Festif
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu de Noël – Végétarien Festif'
  AND d.name IN (
    'Velouté potimarron','Option Entrée – Risotto potimarron & noisettes',
    'Wellington végétarien','Option Plat – Parmentier végétal aux champignons',
    'Bûche vanille-framboise','Option Dessert – Poire pochée & chocolat','Mignardises'
  );

-- Pâques – Printemps Gourmand
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu de Pâques – Printemps Gourmand'
  AND d.name IN (
    'Œufs mimosa','Asperges vinaigrette',
    'Plat de Pâques (agneau ou option)','Option Plat – Filet de poisson printanier',
    'Nid de Pâques chocolat','Option Dessert – Cheesecake citron'
  );

-- Classique – Saveurs du Terroir
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Classique – Saveurs du Terroir'
  AND d.name IN (
    'Salade gourmande','Quiche légumes',
    'Rôti de porc moutarde','Option Plat – Poulet rôti & jus court',
    'Tarte aux pommes','Moelleux chocolat'
  );

-- Classique – Vegan & Énergie
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Classique – Vegan & Énergie'
  AND d.name IN (
    'Houmous & crudités','Option Entrée – Salade quinoa & herbes',
    'Dahl lentilles coco','Option Plat – Curry de légumes au lait de coco',
    'Mousse chocolat vegan','Option Dessert – Compote pomme-cannelle'
  );

-- Classique – Végétarien Fraîcheur
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Classique – Végétarien Fraîcheur'
  AND d.name IN (
    'Salade gourmande','Quiche légumes',
    'Lasagnes de légumes','Option Plat – Risotto champignons',
    'Panna cotta vanille','Option Dessert – Tiramisu sans alcool'
  );

-- Entreprise – Buffet Pro
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Entreprise – Buffet Pro'
  AND d.name IN (
    'Plateau mini-sandwichs','Verrines légumes croquants','Option Entrée – Mini wraps poulet','Option Entrée – Mini wraps veggie',
    'Poulet basquaise','Risotto champignons','Option Plat – Bœuf mijoté & écrasé de pommes de terre',
    'Assortiment mini-pâtisseries','Tiramisu café','Cheesecake fruits rouges'
  );

-- Entreprise – Cocktail Déjeuner
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Entreprise – Cocktail Déjeuner'
  AND d.name IN (
    'Plateau mini-sandwichs','Verrines légumes croquants','Option Entrée – Mini wraps veggie',
    'Cheesecake fruits rouges','Assortiment mini-pâtisseries','Option Dessert – Mousse chocolat praliné'
  );

-- Mariage – Prestige
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Mariage – Prestige'
  AND d.name IN (
    'Foie gras mi-cuit','Option Entrée – Saint-Jacques snackées','Verrine saumon fumé',
    'Suprême de volaille aux morilles','Option Plat – Filet de bœuf sauce truffe','Saumon rôti sauce citron',
    'Cheesecake fruits rouges','Assortiment mini-pâtisseries','Option Dessert – Pavlova fruits rouges'
  );

-- Mariage – Végétarien Chic
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Mariage – Végétarien Chic'
  AND d.name IN (
    'Velouté potimarron','Option Entrée – Risotto potimarron & noisettes','Verrines légumes croquants',
    'Wellington végétarien','Option Plat – Parmentier végétal aux champignons','Risotto champignons',
    'Cheesecake fruits rouges','Option Dessert – Poire pochée & chocolat','Assortiment mini-pâtisseries'
  );

-- Anniversaire – Convivial
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Anniversaire – Convivial'
  AND d.name IN (
    'Salade gourmande','Quiche légumes','Verrines légumes croquants',
    'Poulet basquaise','Option Plat – Poulet rôti & jus court','Gratin dauphinois',
    'Moelleux chocolat','Tarte aux pommes','Assortiment mini-pâtisseries'
  );

-- Anniversaire – Kids Party
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Anniversaire – Kids Party'
  AND d.name IN (
    'Quiche légumes','Plateau mini-sandwichs',
    'Gratin dauphinois','Option Plat – Brochettes de poulet marinées',
    'Moelleux chocolat','Assortiment mini-pâtisseries','Option Dessert – Compote pomme-cannelle'
  );

-- Cocktail – Apéritif Dînatoire
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Cocktail – Apéritif Dînatoire'
  AND d.name IN (
    'Plateau mini-sandwichs','Verrines légumes croquants','Option Entrée – Mini wraps poulet',
    'Mignardises','Assortiment mini-pâtisseries','Cheesecake fruits rouges'
  );

-- Été – Fraîcheur & Grillades
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id,
  CASE d.type WHEN 'entrée' THEN 10 WHEN 'plat' THEN 20 WHEN 'dessert' THEN 30 ELSE 99 END
FROM menus m JOIN dishes d
WHERE m.title='Menu Été – Fraîcheur & Grillades'
  AND d.name IN (
    'Option Entrée – Salade tomate mozzarella','Salade gourmande','Verrines légumes croquants',
    'Option Plat – Brochettes de poulet marinées','Saumon rôti sauce citron','Option Plat – Légumes grillés & halloumi',
    'Option Dessert – Salade de fruits frais','Cheesecake fruits rouges','Panna cotta vanille'
  );

-- COMMANDES TEST + HISTORIQUE + AVIS

INSERT INTO orders (user_id, menu_id, event_date, event_time, people_count, delivery_city, delivery_distance, delivery_price, discount, total_price, status)
VALUES (
  (SELECT id FROM users WHERE email='user@test.fr'),
  (SELECT id FROM menus WHERE title='Menu de Noël – Tradition & Élégance'),
  '2026-12-24',
  '19:30:00',
  12,
  'Bordeaux',
  0.00,
  0.00,
  0.00,
  (12 * 39.90),
  'accepté'
);

INSERT INTO order_status_history (order_id, status)
VALUES
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 'créée'),
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 'accepté'),
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 'en préparation');

INSERT INTO reviews (order_id, rating, comment, is_validated)
VALUES (
  (SELECT id FROM orders ORDER BY id DESC LIMIT 1),
  5,
  'Super prestation, très bon et ponctuel. Je recommande !',
  1
);

-- 2e commande (à modérer)
INSERT INTO orders (user_id, menu_id, event_date, event_time, people_count, delivery_city, delivery_distance, delivery_price, discount, total_price, status)
VALUES (
  (SELECT id FROM users WHERE email='user@test.fr'),
  (SELECT id FROM menus WHERE title='Menu Classique – Vegan & Énergie'),
  '2026-10-10',
  '12:30:00',
  8,
  'Mérignac',
  8.50,
  (5.00 + (0.59 * 8.50)),
  0.00,
  (8 * 22.90) + (5.00 + (0.59 * 8.50)),
  'créée'
);

INSERT INTO reviews (order_id, rating, comment, is_validated)
VALUES (
  (SELECT id FROM orders ORDER BY id DESC LIMIT 1),
  4,
  'Très bon, juste un peu léger sur un plat.',
  0
);

-- Commande entreprise (pour avis “corporate” validé)
INSERT INTO orders (user_id, menu_id, event_date, event_time, people_count, delivery_city, delivery_distance, delivery_price, discount, total_price, status)
VALUES (
  (SELECT id FROM users WHERE email='user@test.fr'),
  (SELECT id FROM menus WHERE title='Menu Entreprise – Buffet Pro'),
  '2026-06-15',
  '12:15:00',
  25,
  'Bordeaux',
  3.00,
  (5.00 + (0.59 * 3.00)),
  0.00,
  (25 * 19.90) + (5.00 + (0.59 * 3.00)),
  'accepté'
);

INSERT INTO reviews (order_id, rating, comment, is_validated, created_at)
VALUES
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 5, 'Nos collaborateurs ont adoré le buffet entreprise : varié, frais et très bien présenté.', 1, NOW() - INTERVAL 25 DAY);

-- AVIS supplémentaires (validés) pour le carousel Home
INSERT INTO reviews (order_id, rating, comment, is_validated, created_at)
VALUES
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 5, 'Prestations haut de gamme, présentation impeccable.', 1, NOW() - INTERVAL 3 DAY),
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 4, 'Très bon, juste un peu plus de choix veggie serait parfait.', 1, NOW() - INTERVAL 10 DAY),
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 5, 'Service rapide et très bon suivi avant l’événement.', 1, NOW() - INTERVAL 18 DAY),
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 4, 'Desserts excellents, livraison à l’heure.', 1, NOW() - INTERVAL 33 DAY),
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 5, 'Mariage réussi grâce à vous, merci !', 1, NOW() - INTERVAL 45 DAY),
((SELECT id FROM orders ORDER BY id DESC LIMIT 1), 5, 'Cocktail dînatoire parfait, portions généreuses.', 1, NOW() - INTERVAL 60 DAY);

COMMIT;
