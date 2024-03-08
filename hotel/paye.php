<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['reservation_id'])) {
    header("Location: hotel.php"); // Redirige vers la page principale si l'ID de réservation n'est pas disponible
    exit();
}

$reservation_id = $_SESSION['reservation_id'];
$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
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

// Récupérer les données de réservation à partir de l'ID de réservation
$requete = "SELECT * FROM Reservations WHERE Id = $reservation_id";
$result = $connexion->query($requete);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Récupérer les autres informations nécessaires pour afficher la confirmation de réservation
    $arrivalDate = $row['date_debut'];
    $numNights = intval((strtotime($row['date_fin']) - strtotime($row['date_debut'])) / (60 * 60 * 24));
    $chambreId = $row['chambre_id'];

    // Récupérer les données de la chambre
    $requete_chambre = "SELECT * FROM Chambres WHERE Id = $chambreId";
    $result_chambre = $connexion->query($requete_chambre);
    if ($result_chambre->num_rows > 0) {
        $row_chambre = $result_chambre->fetch_assoc();
        $Nom = $row_chambre['Nom'];
        $prixParNuit = $row_chambre['Prix'];
        $prixTotal = $prixParNuit * $numNights;
    } else {
        die('Aucune chambre ne correspond à cet ID.');
    }
} else {
    die('Aucune réservation ne correspond à cet ID.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/stylas.css">
    <title>Confirmation de Réservation - Hôtel Neptune</title>

</head>

<body style="background-color: #333;">
   <div class="acceuil">
                    <a href="hotel.php"><button id="home"><?php echo $lang['home']; ?></button></a>
  </div>
    <header>
        <img src="./image/logo.png" alt="Logo de l'hôtel" class="logo">
        <div class="header-buttons">
            <?php if (isset($_SESSION['user_id'])) : ?>
            <a>Bienvenue, <?php echo $_SESSION['user_prenom']; ?>!</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])) : ?>
            <!-- Bouton de déconnexion -->
            <a href="deconnexion.php"><button id="homebutton"><?php echo $lang['logout']; ?></button></a>
            <?php else : ?>
            <!-- Bouton de connexion -->
            <a href="enregistre.php"><button id="homebutton"><?php echo $lang['login']; ?></button></a>
            <?php endif; ?>
            <?php if ($_SESSION['lang'] == 'fr'): ?>
            <a href="?lang=en"><img src="./image/en.jpeg" style="width: 10%; " alt="English"></a>
            <?php else: ?>
            <a href="?lang=fr"><img src="./image/fr.jpeg" style="width: 10%; " alt="Français"></a>
            <?php endif; ?>
        </div>
    
    <section class="reservation-form">
        <article class="room">

        <h1><?php echo $lang['reservation_confirmation']; ?></h1>
         <?php
        
    if ($result->num_rows > 0) {
echo '<img src="data:image/jpeg;base64,' . base64_encode($row_chambre['image']) . '" alt="' . $Nom . '">';
}
         else {
            echo 'Aucune chambre ne correspond aux critères.';
        }


?>
        <p><?php echo $lang['room_reserved']; ?> : <?php echo $Nom; ?></p>
        <p><?php echo $lang['arrival_date']; ?>  <?php echo $arrivalDate; ?></p>
        <p><?php echo $lang['num_nights']; ?>  <?php echo $numNights; ?></p>
        <p><?php echo $lang['total_price']; ?> : <?php echo $prixTotal; ?>€</p>
        <p><?php echo $lang['name']; ?> <?php echo $_SESSION['user_nom']; ?>  <?php echo $_SESSION['user_prenom']; ?>
        <a href="resume.php?arrival_date=<?php echo urlencode($arrivalDate); ?>&num_nights=<?php echo urlencode($numNights); ?>&prix_total=<?php echo urlencode($prixTotal); ?>">

        </a>
        </article>
        </section>
     
</header>
    <!-- Ajoutez d'autres éléments HTML au besoin -->

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Hôtel Neptune. <?php echo $lang['all_rights_reserved']; ?></p>
    </footer>
</body>

</html>
