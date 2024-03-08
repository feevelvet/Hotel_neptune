<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
// Par défaut, si la langue n'est pas définie dans la session, on utilise le français
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fr';
}

// Vérifier si la langue est changée
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Inclure le fichier de langue approprié
$lang_file = './php/lang-' . $_SESSION['lang'] . '.php';
if (file_exists($lang_file)) {
    include $lang_file;
}
// Connexion à la base de données (à adapter selon tes paramètres)
$connexion = new mysqli('localhost', 'root', 'root', 'hotel');

// Vérifier la connexion
if ($connexion->connect_error) {
    die('Erreur de connexion à la base de données : ' . $connexion->connect_error);
}

// Récupérer les filtres
$pieces = isset($_GET['pieces']) ? intval($_GET['pieces']) : 0;
$prix = isset($_GET['prix']) ? intval($_GET['prix']) : 0;
$personnes = isset($_GET['personnes']) ? intval($_GET['personnes']) : 0;

// Construire la requête SQL en fonction des filtres

if (!isset($_GET['pieces']) || !isset($_GET['prix']) || !isset($_GET['personnes'])) {
    $requete = "SELECT * FROM Chambres WHERE Id >= 7 and Id <= 10";
} else {
    $requete = "SELECT * FROM Chambres WHERE Id >= 7 and Id <= 10 AND Pieces >= $pieces AND Prix <= $prix AND Personne >= $personnes";
}
// Exécuter la requête
$resultat = $connexion->query($requete);

if (!$resultat) {
    die('Erreur dans la requête : ' . $connexion->error);
}


// Fermer la connexion
$connexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/stylees.css">
    <title>Hôtel Neptune - Accueil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body style="background-color: #333;">
    <section class="main-title">
        <h1><?php echo $lang['welcome']; ?></h1>
    </section>
    <header>
        <img src="./image/logo.png" alt="Logo de l'hôtel" class="logo">
        <div class="acceuil">
                    <a href="hotel.php"><button id="home"><?php echo $lang['home']; ?></button></a>
  </div>
            <div class="header-buttons">
                 
    <?php if (isset($_SESSION['user_id'])) : ?>
        <!-- Bouton de déconnexion -->
        <a href="deconnexion.php"><button id="home-button"><?php echo $lang['logout']; ?></button></a>
    <?php else : ?>
        <!-- Bouton de connexion -->
        <a href="connexion.php"><button id="home-button"><?php echo $lang['login']; ?></button></a>
    <?php endif; ?>
       <?php if ($_SESSION['lang'] == 'fr'): ?>
           
            <a href="?lang=en"><img src="./image/en.jpeg" style="width: 15%; "alt="English"></a>
        <?php else: ?>
            
            <a href="?lang=fr"><img src="./image/fr.jpeg"style="width: 15%; "alt="Français"></a>
            
        <?php endif; ?>

    </div>

</div>

    <form method="get" action="executive.php">
    <label for="pieces">Nombre de Pièces :</label>
    <select name="pieces">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        
    </select>

    <label for="prix">Prix maximum :</label>
    <input type="number" name="prix" min="0">

    <label for="personnes">Nombre de Personnes :</label>
    <select name="personnes">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
    </select>

    <button type="submit">Filtrer</button>
</form>
     
            <?php
            
        if ($resultat->num_rows > 0) {
            while ($row = $resultat->fetch_assoc()) {
                echo '<article class="room">';
                // Afficher les détails de la chambre en utilisant les données de $row
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="' . $row['Nom'] . '">';
                echo '<h2>' . $row['Nom'] . '</h2>';
                echo '<p>' . $row['Description'] . '</p>';
                echo '<p>' . $row['Prix'] . '€ ' . $lang['per_night'] . '</p>';
                
               echo '<a href="reserver1.php?Id=' . urlencode($row['Id']) . '&Nom=' . urlencode($row['Nom']) .'"><button>' . $lang['see_now'] . '</button></a>';
                echo '</article>';
            }
        } else {
            echo 'Aucune chambre ne correspond aux critères.';
        }
        ?>

    
    
</section>
    <footer>
        <p>&copy; 2023 Hôtel Neptune. Tous droits réservés.</p>
    </footer>

    <div id="login-form" class="modal">
        <!-- Ajoutez ici le formulaire de connexion/inscription -->

    </div>

    </body>

</html>