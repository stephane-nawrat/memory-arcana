<?php

/**
 * Classe Deck - Paquet de cartes du jeu Memory Arcana
 * 
 * Responsabilités :
 * - Sélectionner N arcanes aléatoires parmi les 22
 * - Créer chaque arcane 2 fois (paire)
 * - Mélanger les cartes
 * - Donner accès aux cartes pour l'affichage
 */

require_once __DIR__ . '/Card.php';

class Deck
{
    // === PROPRIÉTÉS ===

    /**
     * @var array Tableau de toutes les cartes du plateau
     */
    private array $cards;

    /**
     * @var int Nombre de paires choisies par le joueur
     */
    private int $pairsCount;

    /**
     * @var string Couleur des cartes (mode basique uniquement pour l'instant)
     */
    private string $color;


    // === CONSTRUCTEUR ===

    /**
     * Crée un nouveau paquet de cartes
     * 
     * @param int $pairsCount Nombre de paires (3-12)
     */
    public function __construct(int $pairsCount)
    {
        $this->pairsCount = $pairsCount;
        $this->color = '#00ff00'; // Vert pour mode basique
        $this->cards = [];

        // Générer les cartes
        $this->generateCards();
    }


    // === MÉTHODE PRINCIPALE : GÉNÉRATION DES CARTES ===

    /**
     * Génère toutes les cartes du jeu
     * 
     * Algorithme :
     * 1. Charger les 22 arcanes depuis le fichier
     * 2. Sélectionner N arcanes aléatoires (selon pairsCount)
     * 3. Créer chaque arcane 2 fois (paire)
     * 4. Mélanger toutes les cartes
     * 
     * @return void
     */
    private function generateCards(): void
    {
        // 1. Charger les 22 arcanes
        $allArcanes = require __DIR__ . '/../../data/arcanes.php';

        // 2. Sélectionner N arcanes aléatoires
        $selectedArcanes = $this->selectRandomArcanes($allArcanes, $this->pairsCount);

        // 3. Créer les paires
        $cardId = 0;
        foreach ($selectedArcanes as $arcane) {
            // Créer l'arcane 2 fois (paire)
            for ($i = 0; $i < 2; $i++) {
                $card = new Card(
                    $cardId,
                    $arcane['name'],
                    $arcane['number'],
                    $arcane['image'],
                    $this->color
                );

                $this->cards[] = $card;
                $cardId++;
            }
        }

        // 4. Mélanger les cartes
        $this->shuffle();
    }


    // === MÉTHODES UTILITAIRES ===

    /**
     * Sélectionne N arcanes aléatoires parmi tous les arcanes
     * 
     * @param array $allArcanes Tableau des 22 arcanes
     * @param int $count Nombre d'arcanes à sélectionner
     * @return array Arcanes sélectionnés
     */
    private function selectRandomArcanes(array $allArcanes, int $count): array
    {
        // Mélanger les arcanes
        shuffle($allArcanes);

        // Prendre les N premiers
        return array_slice($allArcanes, 0, $count);
    }

    /**
     * Mélange toutes les cartes du paquet
     * 
     * @return void
     */
    private function shuffle(): void
    {
        shuffle($this->cards);
    }


    // === GETTERS ===

    /**
     * Récupère toutes les cartes du paquet
     * 
     * @return array
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Récupère une carte par son ID
     * 
     * @param int $id
     * @return Card|null
     */
    public function getCardById(int $id): ?Card
    {
        foreach ($this->cards as $card) {
            if ($card->getId() === $id) {
                return $card;
            }
        }

        return null;
    }

    /**
     * Récupère le nombre de paires
     * 
     * @return int
     */
    public function getPairsCount(): int
    {
        return $this->pairsCount;
    }

    /**
     * Récupère le nombre total de cartes
     * 
     * @return int
     */
    public function getTotalCards(): int
    {
        return count($this->cards);
    }
}
