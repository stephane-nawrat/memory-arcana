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

<body class="page index">
    <main>
        <p class="question">Quel est ton nom ?</p>
        <div class="actions">
            <form action="config.php" method="GET">
                <input type="text"
                    name="nom"
                    required
                    placeholder="Écris ton nom ici..."
                    minlength="2"
                    maxlength="20"
                    class="form-name">
                <button type="submit" class="btn-start">Commencer</button>
            </form>

            <!-- 4 Cartes de dos alignées -->
            <div class="card-preview">
                <img src="assets/images/intro/carrington1.webp" alt="Carte 1">
                <img src="assets/images/intro/carrington2.webp" alt="Carte 2">
                <img src="assets/images/intro/carrington3.webp" alt="Carte 3">
                <img src="assets/images/intro/carrington4.webp" alt="Carte 4">
            </div>

        </div>
        </div>
        </div>
    </main>
</body>

</html>