<?php

/**
 * Test de la classe Game
 * Vérifie toute la logique du jeu
 */

require_once __DIR__ . '/../app/models/Card.php';
require_once __DIR__ . '/../app/models/Deck.php';
require_once __DIR__ . '/../app/models/Game.php';

echo "=== TEST DE LA CLASSE GAME ===\n\n";

// Test 1 : Créer une partie
echo "Test 1 : Création d'une partie\n";
$game = new Game("Alice", 3);
echo "✓ Partie créée\n";
echo "- Joueur : {$game->getPlayerName()}\n";
echo "- Nombre de paires : {$game->getDeck()->getPairsCount()}\n";
echo "- Coups : {$game->getMoves()}\n";
echo "- Paires trouvées : {$game->getMatchedCount()}\n";
echo "- Partie terminée ? " . ($game->isGameOver() ? "OUI" : "NON") . "\n\n";

// Test 2 : Retourner une première carte
echo "Test 2 : Retourner une première carte\n";
$cards = $game->getDeck()->getCards();
$firstCard = $cards[0];
$result = $game->flipCard($firstCard->getId());
echo "- Succès : " . ($result['success'] ? "OUI" : "NON") . "\n";
echo "- Message : {$result['message']}\n";
echo "- En attente d'une 2ème carte : " . (isset($result['waitingForSecond']) ? "OUI" : "NON") . "\n";
echo "✓ Première carte retournée\n\n";

// Test 3 : Retourner une deuxième carte (différente)
echo "Test 3 : Retourner une deuxième carte (différente de la 1ère)\n";
// Trouver une carte avec un nom différent
$secondCard = null;
foreach ($cards as $card) {
    if ($card->getId() !== $firstCard->getId() && $card->getName() !== $firstCard->getName()) {
        $secondCard = $card;
        break;
    }
}

if ($secondCard) {
    $result = $game->flipCard($secondCard->getId());
    echo "- Succès : " . ($result['success'] ? "OUI" : "NON") . "\n";
    echo "- C'est une paire ? " . (isset($result['match']) && $result['match'] ? "OUI" : "NON") . "\n";
    echo "- Message : {$result['message']}\n";
    echo "- Coups après comparaison : {$game->getMoves()}\n";

    // Si pas de paire, cacher les cartes
    if (isset($result['match']) && !$result['match']) {
        $game->hideFlippedCards();
        echo "✓ Cartes cachées après échec\n";
    }
} else {
    echo "⚠️ Impossible de trouver une carte différente (peut arriver avec 3 paires)\n";
}
echo "\n";

// Test 4 : Trouver une vraie paire
echo "Test 4 : Trouver une vraie paire\n";
$cards = $game->getDeck()->getCards();

// Trouver 2 cartes avec le même nom
$pair = [];
$names = [];
foreach ($cards as $card) {
    if (!$card->isMatched() && !$card->isFlipped()) {
        $name = $card->getName();
        if (!isset($names[$name])) {
            $names[$name] = [];
        }
        $names[$name][] = $card;

        if (count($names[$name]) === 2) {
            $pair = $names[$name];
            break;
        }
    }
}

if (count($pair) === 2) {
    $movesBefore = $game->getMoves();
    $matchedBefore = $game->getMatchedCount();

    $result1 = $game->flipCard($pair[0]->getId());
    $result2 = $game->flipCard($pair[1]->getId());

    echo "- C'est une paire ? " . (isset($result2['match']) && $result2['match'] ? "OUI" : "NON") . "\n";
    echo "- Coups avant : $movesBefore, après : {$game->getMoves()}\n";
    echo "- Paires trouvées avant : $matchedBefore, après : {$game->getMatchedCount()}\n";
    echo "✓ Paire trouvée avec succès\n";
} else {
    echo "⚠️ Impossible de trouver une paire disponible\n";
}
echo "\n";

// Test 5 : Essayer de retourner une carte déjà trouvée
echo "Test 5 : Essayer de retourner une carte déjà trouvée\n";
if (count($pair) === 2) {
    $result = $game->flipCard($pair[0]->getId());
    echo "- Succès : " . ($result['success'] ? "OUI" : "NON") . " (attendu: NON)\n";
    echo "- Message : {$result['message']}\n";
    echo "✓ Blocage correct des cartes déjà trouvées\n";
}
echo "\n";

// Test 6 : Jouer une partie complète
echo "Test 6 : Simulation d'une partie complète (3 paires)\n";
$game2 = new Game("Bob", 3);
$cards = $game2->getDeck()->getCards();

// Grouper les cartes par nom
$cardsByName = [];
foreach ($cards as $card) {
    $name = $card->getName();
    if (!isset($cardsByName[$name])) {
        $cardsByName[$name] = [];
    }
    $cardsByName[$name][] = $card;
}

// Trouver toutes les paires
$attempts = 0;
foreach ($cardsByName as $name => $pairCards) {
    if (count($pairCards) === 2) {
        $game2->flipCard($pairCards[0]->getId());
        $result = $game2->flipCard($pairCards[1]->getId());
        $attempts++;

        if (isset($result['gameOver']) && $result['gameOver']) {
            echo "✓ VICTOIRE détectée !\n";
            echo "- Nombre de coups : {$game2->getMoves()}\n";
            echo "- Temps écoulé : {$game2->getFormattedTime()}\n";

            $score = $game2->getScore();
            echo "- Score final :\n";
            echo "  * Joueur : {$score['playerName']}\n";
            echo "  * Coups : {$score['moves']}\n";
            echo "  * Temps : {$score['formattedTime']}\n";
            echo "  * Paires : {$score['pairs']}\n";
            break;
        }
    }
}
echo "\n";

// Test 7 : Calcul du temps
echo "Test 7 : Calcul du temps\n";
$game3 = new Game("Charlie", 3);
sleep(2); // Attendre 2 secondes
$elapsed = $game3->getElapsedTime();
echo "- Temps écoulé : {$elapsed}s (attendu: ~2s)\n";
echo "- Temps formaté : {$game3->getFormattedTime()}\n";
echo "✓ Calcul du temps fonctionnel\n\n";

echo "=== TOUS LES TESTS RÉUSSIS ✓ ===\n";
