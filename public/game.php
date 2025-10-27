<?php

/**
 * Page de jeu Memory Arcana
 * Intègre les classes POO (Game, Deck, Card) tout en respectant le design CSS existant
 */

// Charger les classes
require_once __DIR__ . '/../app/models/Card.php';
require_once __DIR__ . '/../app/models/Deck.php';
require_once __DIR__ . '/../app/models/Game.php';

// Démarrer la session
session_start();

// Récupération des paramètres
$nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : 'Anonyme';
$paires = isset($_GET['paires']) ? (int)$_GET['paires'] : 6;
$mode = isset($_GET['mode']) ? htmlspecialchars($_GET['mode']) : 'basique';

// Créer ou récupérer la partie
if (!isset($_SESSION['game']) || isset($_GET['new'])) {
    // Nouvelle partie
    $game = new Game($nom, $paires);
    $_SESSION['game'] = serialize($game);
} else {
    // Partie en cours
    $game = unserialize($_SESSION['game']);
}

// Récupérer les données pour l'affichage
$cards = $game->getDeck()->getCards();
$totalCartes = count($cards);
$moves = $game->getMoves();
$isGameOver = $game->isGameOver();

// Calcul colonnes - MÊME LOGIQUE que config.php : colonnes = paires (2 lignes max)
$colonnes = $paires;

// Couleurs selon le mode (cohérent avec config.php)
$modeColors = [
    'basique' => '#000000ff',
    'intermédiaire' => '#001535ff',
    'avancé' => '#3e0000ff'
];

$cardColor = $modeColors[$mode] ?? '#000000';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>memory arcana</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/game.css">
</head>

<body class="page game">
    <main>
        <div class="content">
            <!-- NOM DU JOUEUR -->
            <p class="player-name"><span class="player-highlight"><?php echo $nom; ?></span></p>

            <!-- 3 COLONNES : Infos + Stats + Actions -->
            <div class="game-header">
                <!-- COLONNE 1 : Paires + Mode -->
                <div class="game-col">
                    <div class="game-item">
                        <span class="item-value"><?php echo $paires; ?></span>
                        <span class="item-label">Paires</span>
                    </div>
                    <div class="game-item">
                        <span class="item-label">Mode</span>
                        <span class="item-value"><?php echo ucfirst($mode); ?></span>
                    </div>
                </div>

                <!-- COLONNE 2 : Coups + Temps -->
                <div class="game-col">
                    <div class="game-item">
                        <span class="item-value" id="timer">00:00</span>
                        <span class="item-label">Temps</span>
                    </div>
                    <div class="game-item">
                        <span class="item-value" id="moves"><?php echo $moves; ?></span>
                        <span class="item-label">Coups</span>
                    </div>
                </div>

                <!-- COLONNE 3 : Boutons -->
                <div class="game-col">
                    <a href="game.php?nom=<?php echo urlencode($nom); ?>&paires=<?php echo $paires; ?>&mode=<?php echo urlencode($mode); ?>&new=1" class="btn-game">Nouveau</a>
                    <a href="index.php" class="btn-game">Quitter</a>
                </div>
            </div>
        </div>
    </main>

    <!-- MESSAGE DE VICTOIRE (caché par défaut) -->
    <div class="victory-banner <?php echo $isGameOver ? '' : 'hidden'; ?>">
        <p class="victory-text">
            Bravo <span class="player-highlight"><?php echo $nom; ?></span>, tu as fait
            <span id="final-moves"><?php echo $moves; ?></span> coups en
            <span id="final-time"><?php echo $game->getFormattedTime(); ?></span>
            <a href="result.php" class="victory-link">Voir les résultats</a>
        </p>
    </div>

    <!-- Plateau full-width (comme preview) -->
    <section class="game-section">
        <div class="game-board" style="grid-template-columns: repeat(<?php echo $colonnes; ?>, 1fr);">
            <?php foreach ($cards as $card): ?>
                <div class="card 
                    <?php echo $card->isMatched() ? 'card-matched' : ''; ?>
                    <?php echo $card->isFlipped() ? 'card-flipped' : ''; ?>"
                    data-card-id="<?php echo $card->getId(); ?>"
                    data-card-name="<?php echo htmlspecialchars($card->getName()); ?>"
                    style="background-color: <?php echo $card->isFlipped() || $card->isMatched() ? '#ffffff' : $cardColor; ?>;">

                    <?php if ($card->isFlipped() || $card->isMatched()): ?>
                        <!-- Image de l'arcane (pour l'instant juste le nom en texte) -->
                        <div class="card-content" style="display:flex; align-items:center; justify-content:center; height:100%; font-size:0.7rem; padding:0.5rem; text-align:center; color:#000;">
                            <?php echo htmlspecialchars($card->getName()); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- JavaScript (à créer dans la prochaine étape) -->
    <!-- <script src="assets/js/game.js"></script> -->
    <!-- <script src="assets/js/timer.js"></script> -->
</body>

</html>