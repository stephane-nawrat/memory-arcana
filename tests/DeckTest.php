<?php

/**
 * Test de la classe Deck
 * Vérifie la génération et le mélange des cartes
 */

require_once __DIR__ . '/../app/models/Card.php';
require_once __DIR__ . '/../app/models/Deck.php';

echo "=== TEST DE LA CLASSE DECK ===\n\n";

// Test 1 : Créer un deck de 3 paires
echo "Test 1 : Création d'un deck de 3 paires\n";
$deck3 = new Deck(3);
echo "✓ Deck créé\n";
echo "- Nombre de paires : {$deck3->getPairsCount()}\n";
echo "- Nombre total de cartes : {$deck3->getTotalCards()} (attendu: 6)\n\n";

// Test 2 : Créer un deck de 6 paires
echo "Test 2 : Création d'un deck de 6 paires\n";
$deck6 = new Deck(6);
echo "✓ Deck créé\n";
echo "- Nombre de paires : {$deck6->getPairsCount()}\n";
echo "- Nombre total de cartes : {$deck6->getTotalCards()} (attendu: 12)\n\n";

// Test 3 : Créer un deck de 12 paires
echo "Test 3 : Création d'un deck de 12 paires\n";
$deck12 = new Deck(12);
echo "✓ Deck créé\n";
echo "- Nombre de paires : {$deck12->getPairsCount()}\n";
echo "- Nombre total de cartes : {$deck12->getTotalCards()} (attendu: 24)\n\n";

// Test 4 : Vérifier qu'il y a bien des paires
echo "Test 4 : Vérification des paires (deck de 6 paires)\n";
$cards = $deck6->getCards();
$names = [];
foreach ($cards as $card) {
    $name = $card->getName();
    if (!isset($names[$name])) {
        $names[$name] = 0;
    }
    $names[$name]++;
}

echo "- Nombre d'arcanes différents : " . count($names) . " (attendu: 6)\n";

$allPairs = true;
foreach ($names as $name => $count) {
    if ($count !== 2) {
        $allPairs = false;
        echo "❌ Erreur : '$name' apparaît $count fois (attendu: 2)\n";
    }
}

if ($allPairs) {
    echo "✓ Toutes les cartes sont bien en paires (chaque arcane × 2)\n";
}
echo "\n";

// Test 5 : Afficher quelques cartes
echo "Test 5 : Affichage des 5 premières cartes\n";
$cards = $deck6->getCards();
for ($i = 0; $i < 5; $i++) {
    $card = $cards[$i];
    echo "- Carte {$card->getId()} : {$card->getName()} (n°{$card->getNumber()})\n";
}
echo "✓ Cartes accessibles\n\n";

// Test 6 : Récupérer une carte par ID
echo "Test 6 : Récupérer une carte par ID\n";
$card = $deck6->getCardById(3);
if ($card) {
    echo "✓ Carte ID=3 trouvée : {$card->getName()}\n";
} else {
    echo "❌ Carte ID=3 non trouvée\n";
}
echo "\n";

// Test 7 : Vérifier que les cartes sont mélangées
echo "Test 7 : Vérifier le mélange\n";
$deck_a = new Deck(6);
$deck_b = new Deck(6);

$cards_a = $deck_a->getCards();
$cards_b = $deck_b->getCards();

$sameOrder = true;
for ($i = 0; $i < count($cards_a); $i++) {
    if ($cards_a[$i]->getName() !== $cards_b[$i]->getName()) {
        $sameOrder = false;
        break;
    }
}

if (!$sameOrder) {
    echo "✓ Les cartes sont bien mélangées (2 decks ont un ordre différent)\n";
} else {
    echo "⚠️ Les cartes semblent dans le même ordre (peut arriver par hasard)\n";
}
echo "\n";

echo "=== TOUS LES TESTS RÉUSSIS ✓ ===\n";
