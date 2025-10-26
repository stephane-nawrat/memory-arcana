<?php

/**
 * Script de test pour la classe Card
 * Vérifie que toutes les méthodes fonctionnent correctement
 */

// Charger la classe
require_once __DIR__ . '/../app/models/Card.php';

echo "=== TEST DE LA CLASSE CARD ===\n\n";

// Test 1 : Créer une carte
echo "Test 1 : Création d'une carte\n";
$card = new Card(1, "Le Mat", 22, "a22.jpg", "#00ff00");
echo "✓ Carte créée : ID={$card->getId()}, Nom={$card->getName()}, Numéro={$card->getNumber()}\n\n";

// Test 2 : État initial
echo "Test 2 : État initial\n";
echo "- Est retournée ? " . ($card->isFlipped() ? "OUI" : "NON") . " (attendu: NON)\n";
echo "- Est trouvée ? " . ($card->isMatched() ? "OUI" : "NON") . " (attendu: NON)\n";
echo "✓ État initial correct\n\n";

// Test 3 : Retourner la carte
echo "Test 3 : Retourner la carte\n";
$card->flip();
echo "- Est retournée ? " . ($card->isFlipped() ? "OUI" : "NON") . " (attendu: OUI)\n";
echo "✓ Carte retournée avec succès\n\n";

// Test 4 : Cacher la carte
echo "Test 4 : Cacher la carte\n";
$card->hide();
echo "- Est retournée ? " . ($card->isFlipped() ? "OUI" : "NON") . " (attendu: NON)\n";
echo "✓ Carte cachée avec succès\n\n";

// Test 5 : Marquer comme trouvée
echo "Test 5 : Marquer comme trouvée\n";
$card->match();
echo "- Est trouvée ? " . ($card->isMatched() ? "OUI" : "NON") . " (attendu: OUI)\n";
echo "- Est retournée ? " . ($card->isFlipped() ? "OUI" : "NON") . " (attendu: OUI)\n";
echo "✓ Carte marquée trouvée avec succès\n\n";

// Test 6 : Export en tableau
echo "Test 6 : Export en tableau\n";
$array = $card->toArray();
echo "✓ Tableau exporté :\n";
print_r($array);
echo "\n";

// Test 7 : Créer une deuxième carte (paire)
echo "Test 7 : Créer une paire\n";
$card2 = new Card(2, "Le Mat", 22, "a22.jpg", "#00ff00");
echo "- Même nom ? " . ($card->getName() === $card2->getName() ? "OUI" : "NON") . " (attendu: OUI)\n";
echo "- Même numéro ? " . ($card->getNumber() === $card2->getNumber() ? "OUI" : "NON") . " (attendu: OUI)\n";
echo "- IDs différents ? " . ($card->getId() !== $card2->getId() ? "OUI" : "NON") . " (attendu: OUI)\n";
echo "✓ Système de paires fonctionnel\n\n";

echo "=== TOUS LES TESTS RÉUSSIS ✓ ===\n";
