<?php

/**
 * Classe Score - Gestion des scores
 * 
 * Sauvegarde les scores en base de données
 * Récupère les classements avec filtres
 */

require_once __DIR__ . '/Database.php';

class Score
{
    /**
     * @var PDO Connexion à la base de données
     */
    private PDO $pdo;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Sauvegarde un score en base de données
     * 
     * @param string $playerName Nom du joueur
     * @param int $pairs Nombre de paires
     * @param string $mode Mode de jeu
     * @param int $moves Nombre de coups
     * @param int $timeSeconds Temps en secondes
     * @return bool Succès ou échec
     */
    public function save(string $playerName, int $pairs, string $mode, int $moves, int $timeSeconds): bool
    {
        try {
            // 1. Vérifier si le joueur existe déjà
            $stmt = $this->pdo->prepare("SELECT id FROM players WHERE name = ?");
            $stmt->execute([$playerName]);
            $player = $stmt->fetch();

            if (!$player) {
                // 2a. Créer le joueur s'il n'existe pas
                $stmt = $this->pdo->prepare("INSERT INTO players (name) VALUES (?)");
                $stmt->execute([$playerName]);
                $playerId = $this->pdo->lastInsertId();
            } else {
                // 2b. Utiliser l'ID existant
                $playerId = $player['id'];
            }

            // 3. Enregistrer le score
            $stmt = $this->pdo->prepare(
                "INSERT INTO games (player_id, pairs, mode, moves, time_seconds) 
                 VALUES (?, ?, ?, ?, ?)"
            );

            return $stmt->execute([$playerId, $pairs, $mode, $moves, $timeSeconds]);
        } catch (PDOException $e) {
            error_log("Erreur sauvegarde score : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les meilleurs scores
     * 
     * @param int $limit Nombre de résultats (1-100)
     * @param string|null $mode Filtrer par mode (null = tous)
     * @param int|null $pairs Filtrer par nombre de paires (null = tous)
     * @return array Liste des scores
     */
    public function getTopScores(int $limit = 10, ?string $mode = null, ?int $pairs = null): array
    {
        try {
            // Construction de la requête SQL
            $sql = "SELECT 
                        p.name AS player_name,
                        g.pairs,
                        g.mode,
                        g.moves,
                        g.time_seconds,
                        g.completed_at
                    FROM games g
                    JOIN players p ON g.player_id = p.id
                    WHERE 1=1";

            $params = [];

            // Filtre par mode
            if ($mode !== null) {
                $sql .= " AND g.mode = ?";
                $params[] = $mode;
            }

            // Filtre par nombre de paires
            if ($pairs !== null) {
                $sql .= " AND g.pairs = ?";
                $params[] = $pairs;
            }

            // Tri : d'abord par temps, puis par coups
            $sql .= " ORDER BY g.time_seconds ASC, g.moves ASC LIMIT ?";
            $params[] = min($limit, 100); // Max 100 résultats

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur récupération scores : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les statistiques d'un joueur
     * 
     * @param string $playerName Nom du joueur
     * @return array Statistiques
     */
    public function getPlayerStats(string $playerName): array
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT 
                    COUNT(*) AS total_games,
                    MIN(g.time_seconds) AS best_time,
                    MIN(g.moves) AS best_moves,
                    AVG(g.time_seconds) AS avg_time,
                    AVG(g.moves) AS avg_moves
                FROM games g
                JOIN players p ON g.player_id = p.id
                WHERE p.name = ?"
            );

            $stmt->execute([$playerName]);
            return $stmt->fetch() ?: [];
        } catch (PDOException $e) {
            error_log("Erreur stats joueur : " . $e->getMessage());
            return [];
        }
    }
}
