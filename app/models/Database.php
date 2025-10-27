<?php

/**
 * Classe Database - Singleton pour connexion MySQL
 * 
 * Pattern Singleton : une seule instance de connexion pour toute l'application
 * Évite les connexions multiples inutiles
 */

class Database
{
    /**
     * @var Database|null Instance unique de la classe
     */
    private static ?Database $instance = null;

    /**
     * @var PDO Objet PDO pour la connexion
     */
    private PDO $pdo;

    /**
     * Constructeur privé (empêche l'instanciation directe)
     */
    private function __construct()
    {
        // Charger la configuration
        $config = require __DIR__ . '/../../config/database.php';

        // Construire le DSN (Data Source Name)
        $dsn = sprintf(
            "mysql:host=%s;port=%s;dbname=%s;charset=%s",
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            // Créer la connexion PDO
            $this->pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            // En cas d'erreur, arrêter l'application
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Récupère l'instance unique de Database
     * 
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Récupère l'objet PDO
     * 
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Empêche le clonage de l'instance
     */
    private function __clone() {}

    /**
     * Empêche la désérialisation
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
