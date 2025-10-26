<?php

/**
 * Classe Game - Logique complète du jeu Memory Arcana
 * 
 * Responsabilités :
 * - Orchestrer Card et Deck
 * - Gérer les règles du jeu (comparaison, victoire)
 * - Compter les coups et le temps
 * - Déterminer la fin de partie
 */

require_once __DIR__ . '/Card.php';
require_once __DIR__ . '/Deck.php';

class Game
{
    // === PROPRIÉTÉS ===

    /**
     * @var Deck Le paquet de cartes du jeu
     */
    private Deck $deck;

    /**
     * @var string Nom du joueur
     */
    private string $playerName;

    /**
     * @var int Nombre de coups effectués
     */
    private int $moves;

    /**
     * @var int Timestamp de début de partie
     */
    private int $startTime;

    /**
     * @var array Cartes actuellement retournées (max 2)
     */
    private array $flippedCards;

    /**
     * @var int Nombre de paires trouvées
     */
    private int $matchedCount;

    /**
     * @var bool La partie est-elle terminée ?
     */
    private bool $isGameOver;


    // === CONSTRUCTEUR ===

    /**
     * Crée une nouvelle partie
     * 
     * @param string $playerName Nom du joueur
     * @param int $pairsCount Nombre de paires (3-12)
     */
    public function __construct(string $playerName, int $pairsCount)
    {
        $this->playerName = $playerName;
        $this->deck = new Deck($pairsCount);
        $this->moves = 0;
        $this->startTime = time();
        $this->flippedCards = [];
        $this->matchedCount = 0;
        $this->isGameOver = false;
    }


    // === MÉTHODES PRINCIPALES ===

    /**
     * Retourne une carte (action du joueur)
     * 
     * Règles :
     * - Maximum 2 cartes retournées en même temps
     * - On ne peut pas retourner une carte déjà trouvée
     * - On ne peut pas retourner la même carte 2 fois
     * 
     * @param int $cardId ID de la carte à retourner
     * @return array Résultat de l'action
     */
    public function flipCard(int $cardId): array
    {
        // Vérifier que la partie n'est pas terminée
        if ($this->isGameOver) {
            return [
                'success' => false,
                'message' => 'La partie est terminée'
            ];
        }

        // Vérifier qu'on n'a pas déjà 2 cartes retournées
        if (count($this->flippedCards) >= 2) {
            return [
                'success' => false,
                'message' => 'Deux cartes sont déjà retournées'
            ];
        }

        // Récupérer la carte
        $card = $this->deck->getCardById($cardId);

        if (!$card) {
            return [
                'success' => false,
                'message' => 'Carte introuvable'
            ];
        }

        // Vérifier que la carte n'est pas déjà trouvée
        if ($card->isMatched()) {
            return [
                'success' => false,
                'message' => 'Cette carte est déjà trouvée'
            ];
        }

        // Vérifier que la carte n'est pas déjà retournée
        if ($card->isFlipped()) {
            return [
                'success' => false,
                'message' => 'Cette carte est déjà retournée'
            ];
        }

        // Retourner la carte
        $card->flip();
        $this->flippedCards[] = $card;

        // Si on a 2 cartes retournées, on compare
        if (count($this->flippedCards) === 2) {
            return $this->checkMatch();
        }

        return [
            'success' => true,
            'message' => 'Carte retournée',
            'waitingForSecond' => true
        ];
    }

    /**
     * Compare les 2 cartes retournées
     * 
     * @return array Résultat de la comparaison
     */
    private function checkMatch(): array
    {
        $card1 = $this->flippedCards[0];
        $card2 = $this->flippedCards[1];

        // Incrémenter le nombre de coups
        $this->moves++;

        // Vérifier si les 2 cartes sont identiques (même nom)
        if ($card1->getName() === $card2->getName()) {
            // PAIRE TROUVÉE !
            $card1->match();
            $card2->match();
            $this->matchedCount++;

            // Vider les cartes retournées
            $this->flippedCards = [];

            // Vérifier si victoire
            if ($this->matchedCount === $this->deck->getPairsCount()) {
                $this->isGameOver = true;

                return [
                    'success' => true,
                    'match' => true,
                    'gameOver' => true,
                    'message' => 'Paire trouvée ! Victoire !',
                    'finalScore' => $this->getScore()
                ];
            }

            return [
                'success' => true,
                'match' => true,
                'message' => 'Paire trouvée !'
            ];
        } else {
            // PAS DE PAIRE
            return [
                'success' => true,
                'match' => false,
                'message' => 'Pas de paire, les cartes vont se cacher',
                'cardsToHide' => [
                    $card1->getId(),
                    $card2->getId()
                ]
            ];
        }
    }

    /**
     * Cache les 2 cartes retournées (après échec)
     * Appelé après un délai (côté JavaScript)
     * 
     * @return void
     */
    public function hideFlippedCards(): void
    {
        foreach ($this->flippedCards as $card) {
            $card->hide();
        }

        // Vider les cartes retournées
        $this->flippedCards = [];
    }


    // === MÉTHODES DE VÉRIFICATION ===

    /**
     * Vérifie si la partie est terminée
     * 
     * @return bool
     */
    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }


    // === MÉTHODES DE CALCUL ===

    /**
     * Calcule le temps écoulé depuis le début (en secondes)
     * 
     * @return int
     */
    public function getElapsedTime(): int
    {
        return time() - $this->startTime;
    }

    /**
     * Formate le temps écoulé en minutes:secondes
     * 
     * @return string Format "02:45"
     */
    public function getFormattedTime(): string
    {
        $elapsed = $this->getElapsedTime();
        $minutes = floor($elapsed / 60);
        $seconds = $elapsed % 60;

        return sprintf("%02d:%02d", $minutes, $seconds);
    }

    /**
     * Récupère le score final
     * 
     * @return array
     */
    public function getScore(): array
    {
        return [
            'playerName' => $this->playerName,
            'moves' => $this->moves,
            'time' => $this->getElapsedTime(),
            'formattedTime' => $this->getFormattedTime(),
            'pairs' => $this->deck->getPairsCount()
        ];
    }


    // === GETTERS ===

    /**
     * Récupère le deck
     * 
     * @return Deck
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * Récupère le nom du joueur
     * 
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    /**
     * Récupère le nombre de coups
     * 
     * @return int
     */
    public function getMoves(): int
    {
        return $this->moves;
    }

    /**
     * Récupère le nombre de paires trouvées
     * 
     * @return int
     */
    public function getMatchedCount(): int
    {
        return $this->matchedCount;
    }

    /**
     * Récupère les cartes actuellement retournées
     * 
     * @return array
     */
    public function getFlippedCards(): array
    {
        return $this->flippedCards;
    }
}
