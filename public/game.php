<?php
// Récupération des paramètres
$nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : 'Anonyme';
$paires = isset($_GET['paires']) ? (int)$_GET['paires'] : 6;
$mode = isset($_GET['mode']) ? htmlspecialchars($_GET['mode']) : 'basique';

// Calcul du nombre de cartes
$totalCartes = $paires * 2;

// Calcul colonnes selon nombre EXACT de paires
if ($paires == 3) {
    $colonnes = 3; // 3 paires → 3 colonnes × 2 lignes
} elseif ($paires == 4) {
    $colonnes = 4;
} elseif ($paires == 5) {
    $colonnes = 5;
} elseif ($paires == 6) {
    $colonnes = 5;
} elseif ($paires >= 7 && $paires <= 9) {
    $colonnes = 6;
} else { // 10, 11, 12
    $colonnes = 8;
}

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
                        <span class="item-value" id="moves">0</span>
                        <span class="item-label">Coups</span>
                    </div>

                </div>

                <!-- COLONNE 3 : Boutons -->
                <div class="game-col">
                    <a href="config.php?nom=<?php echo $nom; ?>" class="btn-game">Nouveau</a>
                    <a href="index.php" class="btn-game">Quitter</a>
                </div>
            </div>
        </div>

    </main>

    <!-- MESSAGE DE VICTOIRE (caché par défaut) -->
    <div class="victory-banner hidden">
        <p class="victory-text">
            Bravo <span class="player-highlight"><?php echo $nom; ?></span>, tu as fait
            <span id="final-moves">28</span> coups en
            <span id="final-time">02mn45s</span> ->
            <a href="result.php" class="victory-link">Voir les résultats</a>
        </p>
    </div>

    <!-- Plateau full-width (comme preview) -->
    <section class="game-section">
        <div class="game-board" style="grid-template-columns: repeat(<?php echo ceil($totalCartes / 2); ?>, 1fr);">
            <?php for ($i = 0; $i < $totalCartes; $i++): ?>
                <div class="card" data-card-id="<?php echo $i; ?>" style="background-color: <?php echo $cardColor; ?>;">
                    <!-- Carte vide, juste la couleur -->
                </div>
            <?php endfor; ?>
        </div>
    </section>

</body>

</html>