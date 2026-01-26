START TRANSACTION;


-- ROLES

INSERT INTO roles (name) VALUES ('user'), ('employee'), ('admin');


-- THEMES / DIETS

INSERT INTO themes (name) VALUES ('Noël'), ('Pâques'), ('Classique');
INSERT INTO diets (name) VALUES ('Classique'), ('Végétarien'), ('Vegan');


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


-- MENUS (6 menus)
-- theme_id: Noël=1, Pâques=2, Classique=3
-- diet_id : Classique=1, Végétarien=2, Vegan=3

INSERT INTO menus (title, description, price, min_people, max_price, stock, theme_id, diet_id) VALUES
('Menu de Noël – Tradition & Élégance',
'Menu festif idéal pour familles & entreprises. Allergènes consultables sur chaque plat.',
39.90, 10, 59.90, 12, 1, 1),

('Menu de Noël – Végétarien Festif',
'Menu végétarien de fête, généreux et raffiné. Allergènes consultables sur chaque plat.',
34.90, 8, 49.90, 10, 1, 2),

('Menu de Pâques – Printemps Gourmand',
'Menu de saison : asperges, œufs, plat du printemps, dessert chocolat.',
32.90, 10, 49.90, 14, 2, 1),

('Menu Classique – Saveurs du Terroir',
'Menu convivial : entrées, plat, dessert.',
24.90, 8, 39.90, 25, 3, 1),

('Menu Classique – Vegan & Énergie',
'Menu 100% vegan : entrée, plat, dessert.',
22.90, 6, 34.90, 18, 3, 3),

('Menu Classique – Végétarien Fraîcheur',
'Menu végétarien : entrée, plat, dessert.',
21.90, 6, 32.90, 20, 3, 2);


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


-- PLATS - colonne type

INSERT INTO dishes (name, type) VALUES
-- Noël normal
('Verrine saumon fumé','entrée'),
('Foie gras mi-cuit','entrée'),
('Velouté potimarron','entrée'),
('Suprême de volaille aux morilles','plat'),
('Gratin dauphinois','plat'),
('Légumes rôtis d’hiver','plat'),
('Bûche chocolat-noisette','dessert'),
('Mignardises','dessert'),

-- Noël végétarien
('Wellington végétarien','plat'),
('Bûche vanille-framboise','dessert'),

-- Pâques
('Œufs mimosa','entrée'),
('Asperges vinaigrette','entrée'),
('Plat de Pâques (agneau ou option)','plat'),
('Nid de Pâques chocolat','dessert'),

-- Classique
('Salade gourmande','entrée'),
('Quiche légumes','entrée'),
('Rôti de porc moutarde','plat'),
('Tarte aux pommes','dessert'),
('Moelleux chocolat','dessert'),

-- Vegan
('Houmous & crudités','entrée'),
('Dahl lentilles coco','plat'),
('Mousse chocolat vegan','dessert'),

-- Végé classique
('Lasagnes de légumes','plat'),
('Panna cotta vanille','dessert');


-- DISH ALLERGENS (dish_allergen) - exemples

-- Verrine saumon fumé: poisson + lait
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Verrine saumon fumé' AND a.name IN ('Poisson','Lait');

-- Foie gras: gluten + lait + œufs
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Foie gras mi-cuit' AND a.name IN ('Gluten','Lait','Œufs');

-- Gratin dauphinois: lait
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Gratin dauphinois' AND a.name IN ('Lait');

-- Œufs mimosa: œufs + moutarde
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Œufs mimosa' AND a.name IN ('Œufs','Moutarde');

-- Rôti porc moutarde: moutarde
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Rôti de porc moutarde' AND a.name IN ('Moutarde');

-- Houmous: sésame
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Houmous & crudités' AND a.name IN ('Sésame');

-- Mousse vegan: soja
INSERT INTO dish_allergen (dish_id, allergen_id)
SELECT d.id, a.id FROM dishes d, allergens a
WHERE d.name='Mousse chocolat vegan' AND a.name IN ('Soja');


-- MENU <-> DISH (menu_dish) 

-- Noël normal
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id, 10
FROM menus m JOIN dishes d
WHERE m.title='Menu de Noël – Tradition & Élégance'
  AND d.name IN ('Verrine saumon fumé','Foie gras mi-cuit','Velouté potimarron',
                'Suprême de volaille aux morilles','Gratin dauphinois','Légumes rôtis d’hiver',
                'Bûche chocolat-noisette','Mignardises');

-- Noël végétarien
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id, 10
FROM menus m JOIN dishes d
WHERE m.title='Menu de Noël – Végétarien Festif'
  AND d.name IN ('Velouté potimarron','Wellington végétarien','Bûche vanille-framboise','Mignardises');

-- Pâques
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id, 10
FROM menus m JOIN dishes d
WHERE m.title='Menu de Pâques – Printemps Gourmand'
  AND d.name IN ('Œufs mimosa','Asperges vinaigrette','Plat de Pâques (agneau ou option)','Nid de Pâques chocolat');

-- Classique normal
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id, 10
FROM menus m JOIN dishes d
WHERE m.title='Menu Classique – Saveurs du Terroir'
  AND d.name IN ('Salade gourmande','Quiche légumes','Rôti de porc moutarde','Tarte aux pommes','Moelleux chocolat');

-- Classique vegan
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id, 10
FROM menus m JOIN dishes d
WHERE m.title='Menu Classique – Vegan & Énergie'
  AND d.name IN ('Houmous & crudités','Dahl lentilles coco','Mousse chocolat vegan');

-- Classique végétarien
INSERT INTO menu_dish (menu_id, dish_id, sort_order)
SELECT m.id, d.id, 10
FROM menus m JOIN dishes d
WHERE m.title='Menu Classique – Végétarien Fraîcheur'
  AND d.name IN ('Salade gourmande','Lasagnes de légumes','Panna cotta vanille');


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

COMMIT;
