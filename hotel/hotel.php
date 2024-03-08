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
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Admin') {
    // Rediriger l'utilisateur vers la page d'administration s'il est administrateur
    header('Location: admin.php'); 
    exit;
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
if (isset($_GET['all']) && $_GET['all'] == 'true') {
    $requete = "SELECT * FROM Chambres";
} else {
    $requete = "SELECT * FROM Chambres WHERE Pieces >= $pieces AND Prix <= $prix AND Personne >= $personnes";
}
// Exécuter la requête
$resultat = $connexion->query($requete);

if (!$resultat) {
    die('Erreur dans la requête : ' . $connexion->error);
}
$afficherTout = isset($_GET['all']) && $_GET['all'] == 'true';


// Fermer la connexion
$connexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Hôtel Neptune - Accueil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body style="background-color: #000;">

    <section class="main-title">
        <h1><?php echo $lang['welcome']; ?></h1>
    </section>

    <header>

    

        <img src="./image/logo.png" alt="Logo de l'hôtel" class="logo">
            <div class="header-buttons">

            
          
    <?php if (isset($_SESSION['user_id'])) : ?>
        <a href="profil.php"><button id="home-button"><?php echo $lang['profil'] ?></button></a>   
        <a href="deconnexion.php"><button id="home-button"><?php echo $lang['logout']; ?></button></a>
    <?php else : ?>
        <!-- Bouton de connexion -->
        <a href="enregistre.php"><button id="home-button"><?php echo $lang['login']; ?></button></a>
    <?php endif; ?>
       <?php if ($_SESSION['lang'] == 'fr'): ?>
           
            <a href="?lang=en"><img src="./image/en.jpeg" style="width: 15%; "alt="English"></a>
        <?php else: ?>
            
            <a href="?lang=fr"><img src="./image/fr.jpeg"style="width: 15%; "alt="Français"></a>
            
        <?php endif; ?>
    </div>

</div>



        <div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="false">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="./image/chambre1.jpeg" class="d-block w-100 " style="width: 100%; height: 700px" alt="Diaporama 1">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre2.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 2">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre3.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 3">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre4.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 4">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre5.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 5">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre6.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 6">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre7.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 7">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre8.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 8">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre9.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 9">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre10.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama10 ">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre11.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 11">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre12.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 12">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre13.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 13">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre14.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 14">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre15.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 15">
        </div>
        <div class="carousel-item">
            <img src="./image/chambre16.jpeg" class="d-block w-100" style="width: 100%; height: 700px" alt="Diaporama 16">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
    </header>

    
    



    <form method="get" action="hotel.php">
    <label for="pieces">Nombre de Pièces :</label>
    <select name="pieces">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        
    </select>

    <label for="prix">Prix maximum :</label>
    <input type="number" name="prix" min="0" max ="2000">

    <label for="personnes">Nombre de Personnes :</label>
    <select name="personnes">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
    </select>

    <button type="submit">Filtrer</button>
</form>

    <?php
            if (isset($_GET['all']) && $_GET['all'] == 'true') {
    if ($resultat->num_rows > 0) {
            echo '<section class="room-list">';
            while ($row = $resultat->fetch_assoc()) {
                echo '<article class="room">';
                echo '<img class="room-image" src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="' . $row['Nom'] . '">';
                echo '<h2>' . $row['Nom'] . '</h2>';
                echo '<p>' . $lang['nombres'] . ' ' . $row['Pieces'] . '</p>';
                echo '<p>' . $lang['nombre'] . ' ' . $row['Personne'] . '</p>';
                echo '<p>' . $row['Prix'] . '€ ' . $lang['per_night'] . '</p>';
                echo '<a href="reserver1.php?Id=' . urlencode($row['Id']) . '&Nom=' . urlencode($row['Nom']) .'"><button>' . $lang['see_now'] . '</button></a>';                
                echo '</article>';
            }
             echo '</section>';
        }
  }  
else {
        if (isset($_GET['pieces']) || isset($_GET['prix']) || isset($_GET['personnes'])|| isset($_GET['all']) && $_GET['all'] == 'true') {
        if ($resultat->num_rows > 0) {
            echo '<section class="room-list">';
            while ($row = $resultat->fetch_assoc()) {
                echo '<article class="room">';
                echo '<img class="room-image" src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="' . $row['Nom'] . '">';
                echo '<h2>' . $row['Nom'] . '</h2>';
                echo '<p>' . $lang['nombres'] . ' ' . $row['Pieces'] . '</p>';
                echo '<p>' . $lang['nombre'] . ' ' . $row['Personne'] . '</p>';
                echo '<p>' . $row['Prix'] . '€ ' . $lang['per_night'] . '</p>';
                echo '<a href="reserver1.php?Id=' . urlencode($row['Id']) . '&Nom=' . urlencode($row['Nom']) .'"><button>' . $lang['see_now'] . '</button></a>';
                echo '</article>';
            }
             echo '</section>';
        } else {
            echo 'Aucune chambre ne correspond aux critères.';
        }
     }   else {
    echo '<section class="room-list">';
    echo '<article class="room">';
    echo '<img class="room-image" src="./image/chambre1.jpeg" alt="' . $lang['room_1'] . '">';
    echo '<h2>' . $lang['room_luxe'] . '</h2>';
    echo '<p>' . $lang['room_description_luxe'] . '</p>';
    echo '<p>' . $lang['room_price1'] . '</p>';
    echo '<a href="luxe.php"><button>' . $lang['see_now'] . '</button></a>';
    echo '</article>';
    echo '<article class="room">';
    echo '<img class="room-image" src="./image/chambre2.jpeg" alt="' . $lang['room_2'] . '">';
    echo '<h2>' . $lang['room_royale'] . '</h2>';
    echo '<p>' . $lang['room_description_royale'] . '</p>';
    echo '<p>' . $lang['room_price2'] . '</p>';
    echo '<a href="royale.php"><button>' . $lang['see_now'] . '</button></a>';
    echo '</article>';
    echo '<article class="room">';
    echo '<img class="room-image" src="./image/chambre3.jpeg" alt="' . $lang['room_3'] . '">';
    echo '<h2>' . $lang['room_executive'] . '</h2>';
    echo '<p>' . $lang['room_description_executive'] . '</p>';
    echo '<p>' . $lang['room_price3'] . '</p>';
    echo '<a href="executive.php"><button>' . $lang['see_now'] . '</button></a>';
    echo '</article>';
    echo '</section>';
}

}
?>
    <footer>
<?php
if ($afficherTout) {
    echo '<a href="?all=false" class="show-all-btn">Fermer </a>';
} else {
    echo '<a href="?all=true" class="show-all-btn">Tout afficher </a>';


}
?>

<hr />

        <p>&copy; 2023 Hôte Neptune Tous droits réservés.</p>
        <p>&copy; Nous contacter : Téléphone: 0769336543</p>
    </footer style=" color: #fff;">

    <div id="login-form" class="modal">
        <button onclick="closeLoginForm()">Fermer</button>
    </div>

    </body>

</html>