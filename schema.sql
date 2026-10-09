-- Travel Guide Complete Database Schema
-- This creates ALL tables from scratch

-- Drop existing tables if they exist (be careful!)
-- Uncomment these lines if you want to start fresh
-- DROP TABLE IF EXISTS place_categories;
-- DROP TABLE IF EXISTS reviews;
-- DROP TABLE IF EXISTS favorites;
-- DROP TABLE IF EXISTS newsletter_subscribers;
-- DROP TABLE IF EXISTS messages;
-- DROP TABLE IF EXISTS gallery;
-- DROP TABLE IF EXISTS places;
-- DROP TABLE IF EXISTS categories;
-- DROP TABLE IF EXISTS users;
-- DROP TABLE IF EXISTS admin;

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Places table
CREATE TABLE IF NOT EXISTS places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Gallery table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Messages/Contact table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories table (NEW)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50) DEFAULT '📍',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Place categories junction table (NEW)
CREATE TABLE IF NOT EXISTS place_categories (
    place_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (place_id, category_id),
    INDEX idx_place_id (place_id),
    INDEX idx_category_id (category_id)
) ENGINE=InnoDB;

-- Reviews table (NEW)
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_place_id (place_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB;

-- Favorites table (NEW)
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    place_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favorite (user_id, place_id),
    INDEX idx_user_id (user_id),
    INDEX idx_place_id (place_id)
) ENGINE=InnoDB;

-- Newsletter subscribers table (NEW)
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default admin (username: admin, password: admin123)
INSERT IGNORE INTO admin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert default categories
INSERT IGNORE INTO categories (name, icon) VALUES
('Beach', '🏖️'),
('Mountain', '⛰️'),
('City', '🏙️'),
('Historical', '🏛️'),
('Adventure', '🎿'),
('Nature', '🌲'),
('Cultural', '🎭'),
('Relaxation', '🧘');

-- Insert sample places (optional - you can add your own)
INSERT IGNORE INTO places (name, location, description, image) VALUES
('Bali Paradise', 'Bali, Indonesia', 'Experience the ultimate tropical paradise with pristine beaches, lush rice terraces, and vibrant culture. Bali offers perfect harmony between natural beauty and spiritual serenity.', 'bali.jpg'),
('Swiss Alps', 'Switzerland', 'Majestic mountains, charming villages, and breathtaking landscapes. The Swiss Alps offer year-round adventure from skiing to hiking with stunning alpine scenery.', 'swiss-alps.jpg'),
('Tokyo Metropolis', 'Tokyo, Japan', 'A fascinating blend of ultra-modern and traditional. Experience cutting-edge technology, ancient temples, world-class cuisine, and vibrant street culture.', 'tokyo.jpg'),
('Santorini Sunset', 'Santorini, Greece', 'Famous for stunning sunsets, white-washed buildings, and crystal-blue waters. This volcanic island offers romantic views and Mediterranean charm.', 'santorini.jpg'),
('Amazon Rainforest', 'Amazon, Brazil', 'The world\'s largest tropical rainforest, teeming with incredible biodiversity. An adventure into the heart of nature\'s most spectacular ecosystem.', 'amazon.jpg'),
('Paris Romance', 'Paris, France', 'The City of Light captivates with iconic landmarks, world-renowned art, exquisite cuisine, and timeless romance on every cobblestone street.', 'paris.jpg');

-- Associate sample places with categories (optional)
INSERT IGNORE INTO place_categories (place_id, category_id) VALUES
(1, 1), (1, 8),  -- Bali: Beach, Relaxation
(2, 2), (2, 5),  -- Swiss Alps: Mountain, Adventure
(3, 3), (3, 7),  -- Tokyo: City, Cultural
(4, 1), (4, 8),  -- Santorini: Beach, Relaxation
(5, 6), (5, 5),  -- Amazon: Nature, Adventure
(6, 3), (6, 4);  -- Paris: City, Historical
