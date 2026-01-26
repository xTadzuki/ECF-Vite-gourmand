SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Pour réimport proprement
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS dish_allergen;
DROP TABLE IF EXISTS menu_dish;
DROP TABLE IF EXISTS menu_images;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS order_status_history;
DROP TABLE IF EXISTS order_statuses;   
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS opening_hours;
DROP TABLE IF EXISTS dishes;
DROP TABLE IF EXISTS allergens;
DROP TABLE IF EXISTS menus;
DROP TABLE IF EXISTS diets;
DROP TABLE IF EXISTS themes;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS=1;

START TRANSACTION;


-- ROLES

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- USERS

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  firstname VARCHAR(100),
  lastname VARCHAR(100),
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(30),
  address TEXT,
  password VARCHAR(255) NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  reset_token VARCHAR(255) DEFAULT NULL,
  reset_expires_at DATETIME DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- THEMES / DIETS

CREATE TABLE themes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE diets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- MENUS

CREATE TABLE menus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(190) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  min_people INT NOT NULL,
  max_price DECIMAL(10,2) DEFAULT NULL,
  stock INT NOT NULL DEFAULT 0,
  theme_id INT NULL,
  diet_id INT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_menus_theme FOREIGN KEY (theme_id) REFERENCES themes(id),
  CONSTRAINT fk_menus_diet  FOREIGN KEY (diet_id)  REFERENCES diets(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_menus_theme ON menus(theme_id);
CREATE INDEX idx_menus_diet  ON menus(diet_id);


-- MENU IMAGES

CREATE TABLE menu_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  menu_id INT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255) DEFAULT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_menu_images_menu FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ALLERGENS

CREATE TABLE allergens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DISHES

CREATE TABLE dishes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(190) NOT NULL,
  type ENUM('entrée','plat','dessert') NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- MENUS <-> DISHES

CREATE TABLE menu_dish (
  menu_id INT NOT NULL,
  dish_id INT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (menu_id, dish_id),
  CONSTRAINT fk_menu_dish_menu FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
  CONSTRAINT fk_menu_dish_dish FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- DISHES <-> ALLERGENS

CREATE TABLE dish_allergen (
  dish_id INT NOT NULL,
  allergen_id INT NOT NULL,
  PRIMARY KEY (dish_id, allergen_id),
  CONSTRAINT fk_dish_allergen_dish FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE,
  CONSTRAINT fk_dish_allergen_allergen FOREIGN KEY (allergen_id) REFERENCES allergens(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- OPENING HOURS
-- day_of_week: 1=Lun ... 7=Dim

CREATE TABLE opening_hours (
  id INT AUTO_INCREMENT PRIMARY KEY,
  day_of_week TINYINT NOT NULL,
  open_time TIME NULL,
  close_time TIME NULL,
  is_closed TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ORDER STATUSES (référence)

CREATE TABLE order_statuses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  label VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO order_statuses (code, label) VALUES
('created', 'créée'),
('accepted', 'accepté'),
('preparing', 'en préparation'),
('material_return', 'en attente du retour de matériel'),
('delivered', 'livré'),
('cancelled', 'annulé');


-- ORDERS

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  menu_id INT NOT NULL,

  event_date DATE NOT NULL,
  event_time TIME NOT NULL,
  people_count INT NOT NULL,

  delivery_city VARCHAR(190) NULL,
  delivery_distance DECIMAL(6,2) NULL,
  delivery_price DECIMAL(10,2) DEFAULT 0,

  discount DECIMAL(10,2) DEFAULT 0,
  total_price DECIMAL(10,2) NOT NULL,

  status VARCHAR(100) NOT NULL DEFAULT 'créée',

  
  cancel_contact_mode VARCHAR(50) NULL,
  cancel_reason TEXT NULL,
  cancelled_at DATETIME NULL,

  
  updated_at DATETIME NULL,

  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_orders_menu FOREIGN KEY (menu_id) REFERENCES menus(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_menu ON orders(menu_id);
CREATE INDEX idx_orders_status ON orders(status);


-- ORDER STATUS HISTORY

CREATE TABLE order_status_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  status VARCHAR(255) NOT NULL,
  changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  
  changed_by VARCHAR(50) NULL,

  CONSTRAINT fk_history_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_history_order ON order_status_history(order_id);
CREATE INDEX idx_history_status ON order_status_history(status);


-- REVIEWS
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT NULL,
  is_validated TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reviews_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
