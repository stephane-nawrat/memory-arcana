<?php
$nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : 'Anonyme';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>memory arcana</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/config.css">
    <link rel="stylesheet" href="assets/css/preview.css">
</head>

<body class="page config">
    <main>
        <div class="content">
            <!-- LIGNE 1 : Titre -->
            <p class="question">Config <span class="player-highlight"><?php echo $nom; ?></span></p>

            <form action="game.php" method="GET" class="config-form">
                <input type="hidden" name="nom" value="<?php echo $nom; ?>">
                <input type="hidden" name="new" value="1">

                <!-- LIGNE 2 : Paires + Mode côte à côte -->
                <div class="form-inline">

                    <!-- Mode -->
                    <div class="form-field">
                        <label for="mode-select">Mode</label>
                        <select id="mode-select" name="mode">
                            <option value="basique">Basique</option>
                            <option value="intermédiaire">Intermédiaire</option>
                            <option value="avancé">Avancé</option>

                        </select>
                    </div>


                    <!-- Paires -->
                    <div class="form-field">
                        <span id="paires-display">6</span>
                        <label for="paires-slider">Paires</label>
                        <div class="slider-wrapper">
                            <input
                                type="range"
                                id="paires-slider"
                                name="paires"
                                min="3"
                                max="12"
                                value="6">

                        </div>
                    </div>
                </div>

                <!-- LIGNE 3 : Bouton -->
                <button type="submit" class="btn">LANCER LA PARTIE</button>
            </form>
        </div>
    </main>

    <!-- APERÇU DU PLATEAU (full-width, en dehors de .content) -->
    <section class="preview-section">
        <div id="preview-board" class="preview-board" data-columns="3">
            <!-- Les cartes seront générées par JavaScript -->
        </div>
    </section>

    <script>
        // Éléments
        const slider = document.getElementById('paires-slider');
        const display = document.getElementById('paires-display');
        const modeSelect = document.getElementById('mode-select');
        const previewBoard = document.getElementById('preview-board');

        // Couleurs selon le mode
        const modeColors = {
            'basique': '#000000ff',
            'intermédiaire': '#001535ff',
            'avancé': '#3e0000ff'
        };

        // Fonction pour générer l'aperçu
        function updatePreview(paires) {
            paires = parseInt(paires); // CONVERTIR en nombre
            const totalCartes = paires * 2;

            // NOUVEAU : 2 lignes maximum → colonnes = paires
            const colonnes = paires;

            // Appliquer le nombre de colonnes
            previewBoard.style.gridTemplateColumns = `repeat(${colonnes}, 1fr)`;

            // Récupérer la couleur du mode sélectionné
            const currentMode = modeSelect.value;
            const cardColor = modeColors[currentMode] || '#000000';

            // Générer les cartes
            previewBoard.innerHTML = '';
            for (let i = 0; i < totalCartes; i++) {
                const card = document.createElement('div');
                card.className = 'preview-card';
                card.style.backgroundColor = cardColor;
                previewBoard.appendChild(card);
            }
        }

        // Initialiser l'aperçu
        updatePreview(slider.value);

        // Mettre à jour au changement du slider
        slider.addEventListener('input', function() {
            display.textContent = this.value;
            updatePreview(this.value);
        });

        // Mettre à jour au changement du mode
        modeSelect.addEventListener('change', function() {
            updatePreview(slider.value);
        });
    </script>

</body>

</html>