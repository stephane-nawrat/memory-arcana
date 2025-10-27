/**
 * game.js - Gestion de l'interactivité du jeu Memory
 * Écoute les clics, envoie les requêtes AJAX, met à jour l'affichage
 */

// Variables globales
let isProcessing = false; // Empêche les clics pendant le traitement

// Initialisation au chargement de la page
document.addEventListener("DOMContentLoaded", function () {
  initGame();
});

/**
 * Initialise le jeu
 */
function initGame() {
  // Ajouter les écouteurs de clic sur toutes les cartes
  const cards = document.querySelectorAll(".card");
  cards.forEach((card) => {
    card.addEventListener("click", handleCardClick);
  });

  console.log("Jeu initialisé, " + cards.length + " cartes détectées");
}

/**
 * Gère le clic sur une carte
 */
function handleCardClick(event) {
  // Empêcher les clics multiples pendant le traitement
  if (isProcessing) {
    return;
  }

  const card = event.currentTarget;
  const cardId = parseInt(card.dataset.cardId);

  // Vérifier que la carte n'est pas déjà retournée ou trouvée
  if (
    card.classList.contains("card-flipped") ||
    card.classList.contains("card-matched")
  ) {
    return;
  }

  console.log("Clic sur carte ID:", cardId);

  // Bloquer les autres clics
  isProcessing = true;

  // Envoyer la requête AJAX
  flipCard(cardId);
}

/**
 * Envoie une requête AJAX pour retourner une carte
 */
function flipCard(cardId) {
  fetch("game-action.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "flip",
      cardId: cardId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Réponse serveur:", data);

      if (data.success) {
        // Mettre à jour l'affichage des cartes
        updateCards(data.cards);

        // Mettre à jour le compteur de coups
        updateMoves(data.moves);

        // Si pas de paire, attendre 1 seconde puis cacher
        if (data.match === false) {
          setTimeout(() => {
            hideCards(data.cardsToHide);
          }, 1000);
        } else {
          // Si paire trouvée ou en attente de 2ème carte, débloquer
          isProcessing = false;
        }

        // Si victoire
        if (data.gameOver) {
          handleVictory(data.finalScore);
        }
      } else {
        console.error("Erreur:", data.message);
        isProcessing = false;
      }
    })
    .catch((error) => {
      console.error("Erreur AJAX:", error);
      isProcessing = false;
    });
}

/**
 * Cache les cartes après un échec
 */
function hideCards(cardIds) {
  fetch("game-action.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "hide",
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        updateCards(data.cards);
      }
      // Débloquer les clics
      isProcessing = false;
    })
    .catch((error) => {
      console.error("Erreur AJAX:", error);
      isProcessing = false;
    });
}

/**
 * Met à jour l'affichage de toutes les cartes
 */
function updateCards(cardsData) {
  cardsData.forEach((cardData) => {
    const cardElement = document.querySelector(
      `[data-card-id="${cardData.id}"]`
    );
    if (!cardElement) return;

    // Mettre à jour les classes CSS
    if (cardData.isMatched) {
      cardElement.classList.add("card-matched");
      cardElement.classList.add("card-flipped");
    } else if (cardData.isFlipped) {
      cardElement.classList.add("card-flipped");
    } else {
      cardElement.classList.remove("card-flipped");
      cardElement.classList.remove("card-matched");
    }

    // Mettre à jour le contenu
    if (cardData.isFlipped || cardData.isMatched) {
      // Afficher le nom de l'arcane
      cardElement.style.backgroundColor = "#ffffff";
      cardElement.innerHTML = `
                <div class="card-content" style="display:flex; align-items:center; justify-content:center; height:100%; font-size:0.7rem; padding:0.5rem; text-align:center; color:#000;">
                    ${cardData.name}
                </div>
            `;
    } else {
      // Cacher (fond coloré)
      const mode =
        new URLSearchParams(window.location.search).get("mode") || "basique";
      const colors = {
        basique: "#000000ff",
        intermédiaire: "#001535ff",
        avancé: "#3e0000ff",
      };
      cardElement.style.backgroundColor = colors[mode];
      cardElement.innerHTML = "";
    }
  });
}

/**
 * Met à jour le compteur de coups
 */
function updateMoves(moves) {
  const movesElement = document.getElementById("moves");
  if (movesElement) {
    movesElement.textContent = moves;
  }
}

/**
 * Gère la victoire
 */
function handleVictory(score) {
  console.log("VICTOIRE !", score);

  // Arrêter le timer (si timer.js est chargé)
  if (typeof stopTimer === "function") {
    stopTimer();
  }

  // Afficher le message de victoire
  const victoryBanner = document.querySelector(".victory-banner");
  if (victoryBanner) {
    // Mettre à jour les valeurs
    document.getElementById("final-moves").textContent = score.moves;
    document.getElementById("final-time").textContent = score.formattedTime;

    // Afficher la bannière
    victoryBanner.classList.remove("hidden");
  }

  // Empêcher tout nouveau clic
  isProcessing = true;
}
