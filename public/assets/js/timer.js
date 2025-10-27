/**
 * timer.js - Gestion du chronomètre
 * Lance le timer au chargement, met à jour chaque seconde
 */

// Variables globales
let startTime = Date.now();
let timerInterval = null;

// Initialisation au chargement
document.addEventListener("DOMContentLoaded", function () {
  startTimer();
});

/**
 * Démarre le chronomètre
 */
function startTimer() {
  startTime = Date.now();

  // Mettre à jour toutes les secondes
  timerInterval = setInterval(updateTimer, 1000);

  console.log("Chronomètre démarré");
}

/**
 * Met à jour l'affichage du chronomètre
 */
function updateTimer() {
  const elapsed = Math.floor((Date.now() - startTime) / 1000);
  const minutes = Math.floor(elapsed / 60);
  const seconds = elapsed % 60;

  // Formater en MM:SS
  const formatted =
    String(minutes).padStart(2, "0") + ":" + String(seconds).padStart(2, "0");

  // Mettre à jour l'affichage
  const timerElement = document.getElementById("timer");
  if (timerElement) {
    timerElement.textContent = formatted;
  }
}

/**
 * Arrête le chronomètre (appelé à la victoire)
 */
function stopTimer() {
  if (timerInterval) {
    clearInterval(timerInterval);
    timerInterval = null;
    console.log("Chronomètre arrêté");
  }
}
