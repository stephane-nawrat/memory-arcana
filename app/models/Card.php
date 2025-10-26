<?php

/**
 * Classe Card - Représente une carte du jeu Memory Arcana
 * 
 * Une carte possède :
 * - Un identifiant unique (id)
 * - Un nom d'arcane (ex: "Le Mat")
 * - Un numéro d'arcane (1-22)
 * - Une image (ex: "a22.jpg")
 * - Une couleur selon le mode (vert/bleu/rouge)
 * - Deux états : retournée (flipped) et trouvée (matched)
 */
class Card
{
    // === PROPRIÉTÉS ===

    /**
     * @var int Identifiant unique de la carte dans le plateau
     */
    private int $id;

    /**
     * @var string Nom de l'arcane (ex: "Le Mat", "Le Bateleur")
     */
    private string $name;

    /**
     * @var int Numéro de l'arcane (1-22 pour les arcanes majeurs)
     */
    private int $number;

    /**
     * @var string Nom du fichier image (ex: "a22.jpg")
     */
    private string $image;

    /**
     * @var string Couleur de la carte selon le mode (hex: #00ff00, #0066ff, #ff0000)
     */
    private string $color;

    /**
     * @var bool État : la carte est-elle retournée (face visible) ?
     */
    private bool $isFlipped;

    /**
     * @var bool État : la paire est-elle trouvée (reste visible définitivement) ?
     */
    private bool $isMatched;


    // === CONSTRUCTEUR ===

    /**
     * Crée une nouvelle carte
     * 
     * @param int $id Identifiant unique
     * @param string $name Nom de l'arcane
     * @param int $number Numéro de l'arcane (1-22)
     * @param string $image Fichier image
     * @param string $color Couleur (hex)
     */
    public function __construct(int $id, string $name, int $number, string $image, string $color)
    {
        $this->id = $id;
        $this->name = $name;
        $this->number = $number;
        $this->image = $image;
        $this->color = $color;

        // Par défaut, une carte est cachée et non trouvée
        $this->isFlipped = false;
        $this->isMatched = false;
    }


    // === MÉTHODES - ACTIONS SUR LA CARTE ===

    /**
     * Retourne la carte (passe de cachée à visible)
     * 
     * @return void
     */
    public function flip(): void
    {
        $this->isFlipped = true;
    }

    /**
     * Cache la carte (remet face cachée)
     * Utilisé quand la paire n'est pas correcte
     * 
     * @return void
     */
    public function hide(): void
    {
        $this->isFlipped = false;
    }

    /**
     * Marque la carte comme trouvée (paire validée)
     * La carte reste visible définitivement
     * 
     * @return void
     */
    public function match(): void
    {
        $this->isMatched = true;
        $this->isFlipped = true; // Une carte trouvée reste retournée
    }


    // === MÉTHODES - VÉRIFICATIONS ===

    /**
     * Vérifie si la carte est retournée (face visible)
     * 
     * @return bool
     */
    public function isFlipped(): bool
    {
        return $this->isFlipped;
    }

    /**
     * Vérifie si la carte est trouvée (paire validée)
     * 
     * @return bool
     */
    public function isMatched(): bool
    {
        return $this->isMatched;
    }


    // === GETTERS - ACCÈS AUX PROPRIÉTÉS ===

    /**
     * Récupère l'ID de la carte
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Récupère le nom de l'arcane
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Récupère le numéro de l'arcane
     * 
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Récupère le nom du fichier image
     * 
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Récupère la couleur de la carte
     * 
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }


    // === MÉTHODE UTILITAIRE ===

    /**
     * Exporte la carte sous forme de tableau associatif
     * Utile pour JSON/AJAX
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'image' => $this->image,
            'color' => $this->color,
            'isFlipped' => $this->isFlipped,
            'isMatched' => $this->isMatched
        ];
    }
}
