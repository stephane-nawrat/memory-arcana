-- ====================================
-- Base de données Memory Arcana
-- MySQL 9.4.0
-- Version simplifiée (mode basique uniquement)
-- ====================================

-- Supprimer la base si elle existe (DEV uniquement)
DROP DATABASE IF EXISTS phase01_memory;

-- Créer la base de données
CREATE DATABASE phase01_memory 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Utiliser la base
USE phase01_memory;

-- ====================================
-- Table: players
-- Stocke les joueurs
-- ====================================
CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Table: games
-- Stocke les parties terminées
-- ====================================
CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    pairs INT NOT NULL,
    mode VARCHAR(20) NOT NULL DEFAULT 'basique',
    moves INT NOT NULL,
    time_seconds INT NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    
    INDEX idx_mode_pairs (mode, pairs),
    INDEX idx_completed_at (completed_at),
    INDEX idx_time_moves (time_seconds, moves)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Données de test (optionnel)
-- ====================================

-- Insérer quelques joueurs de test
INSERT INTO players (name) VALUES 
('Alice'),
('Joe'),
('Charlie');

-- Insérer quelques parties de test (mode basique uniquement)
INSERT INTO games (player_id, pairs, mode, moves, time_seconds) VALUES
(1, 3, 'basique', 8, 45),
(1, 6, 'basique', 15, 120),
(2, 3, 'basique', 10, 60),
(2, 6, 'basique', 18, 180),
(3, 12, 'basique', 30, 300);

-- ====================================
-- Vérification
-- ====================================
SELECT 'Base de données créée avec succès !' AS message;
SELECT COUNT(*) AS nb_players FROM players;
SELECT COUNT(*) AS nb_games FROM games;