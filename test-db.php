<?php
require_once 'app/models/Database.php';
require_once 'app/models/Score.php';

echo "=== TEST CONNEXION BDD ===\n\n";

// Test connexion
try {
    $db = Database::getInstance();
    echo "✅ Connexion réussie !\n\n";
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    exit;
}

// Test lecture scores
$score = new Score();
$results = $score->getTopScores(5);

echo "=== TOP 5 SCORES ===\n";
foreach ($results as $row) {
    echo "- {$row['player_name']} : {$row['moves']} coups en {$row['time_seconds']}s ({$row['mode']})\n";
}

echo "\n✅ Tout fonctionne !\n";
