<?php

/**
 * Contrôleur AJAX pour les actions de jeu
 * Reçoit les requêtes JavaScript, appelle Game, renvoie JSON
 */

// Charger les classes
require_once __DIR__ . '/../app/models/Card.php';
require_once __DIR__ . '/../app/models/Deck.php';
require_once __DIR__ . '/../app/models/Game.php';

// Démarrer la session
session_start();

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données JSON envoyées par JavaScript
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Vérifier que le jeu existe en session
if (!isset($_SESSION['game'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Aucune partie en cours']);
    exit;
}

// Récupérer le jeu depuis la session
$game = unserialize($_SESSION['game']);

// Récupérer l'action demandée
$action = $data['action'] ?? '';

// === ACTION : RETOURNER UNE CARTE ===
if ($action === 'flip') {
    $cardId = (int)($data['cardId'] ?? -1);

    // Appeler la méthode flipCard de Game
    $result = $game->flipCard($cardId);

    // Sauvegarder l'état du jeu dans la session
    $_SESSION['game'] = serialize($game);

    // Si pas de paire, on doit cacher les cartes après 1 seconde
    // JavaScript gérera le délai
    if (isset($result['match']) && !$result['match']) {
        // Préparer la liste des cartes à renvoyer
        $cardsData = array_map(function ($card) {
            return [
                'id' => $card->getId(),
                'name' => $card->getName(),
                'isFlipped' => $card->isFlipped(),
                'isMatched' => $card->isMatched()
            ];
        }, $game->getDeck()->getCards());

        echo json_encode([
            'success' => true,
            'match' => false,
            'cards' => $cardsData,
            'moves' => $game->getMoves(),
            'time' => $game->getElapsedTime(),
            'cardsToHide' => $result['cardsToHide']
        ]);
        exit;
    }

    // Si paire trouvée ou en attente de 2ème carte
    $cardsData = array_map(function ($card) {
        return [
            'id' => $card->getId(),
            'name' => $card->getName(),
            'isFlipped' => $card->isFlipped(),
            'isMatched' => $card->isMatched()
        ];
    }, $game->getDeck()->getCards());

    $response = [
        'success' => $result['success'],
        'message' => $result['message'] ?? '',
        'cards' => $cardsData,
        'moves' => $game->getMoves(),
        'time' => $game->getElapsedTime()
    ];

    // Si paire trouvée
    if (isset($result['match'])) {
        $response['match'] = $result['match'];
    }

    // Si victoire
    if (isset($result['gameOver']) && $result['gameOver']) {
        $response['gameOver'] = true;
        $response['finalScore'] = $result['finalScore'];
    }

    echo json_encode($response);
    exit;
}

// === ACTION : CACHER LES CARTES (après échec) ===
if ($action === 'hide') {
    $game->hideFlippedCards();
    $_SESSION['game'] = serialize($game);

    $cardsData = array_map(function ($card) {
        return [
            'id' => $card->getId(),
            'name' => $card->getName(),
            'isFlipped' => $card->isFlipped(),
            'isMatched' => $card->isMatched()
        ];
    }, $game->getDeck()->getCards());

    echo json_encode([
        'success' => true,
        'cards' => $cardsData
    ]);
    exit;
}

// Action inconnue
http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Action inconnue']);
