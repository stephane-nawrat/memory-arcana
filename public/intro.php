<?php
$step = $_GET['step'] ?? 'enter';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>memory arcana</title>
    <link rel="stylesheet" href="assets/css/global.css">
</head>

<body class="page intro">
    <main>
        <div class="content">
            <?php if ($step === 'enter'): ?>
                <p class="question">jouer aux cartes ?</p>
                <div class="actions">
                    <a href="index.php" class="btn">OUI</a>
                    <a href="?step=lucid" class="btn">NON</a>
                </div>

            <?php elseif ($step === 'lucid'): ?>
                <p class="quote">Vois-tu un labyrinthe ?</p>
                <div class="actions">
                    <a href="index.php" class="btn">OUI</a>
                    <a href="?step=bye" class="btn">NON</a>
                </div>

            <?php else: /* bye */ ?>
                <p class="farewell">Au revoir</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>