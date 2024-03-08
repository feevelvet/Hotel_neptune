<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];

// Connexion à la base de données (à adapter selon tes paramètres)
$connexion = new mysqli('localhost', 'root', 'root', 'hotel');

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

$Id = isset($_GET['Id']) ? $_GET['Id'] : '';
$Nom = isset($_GET['Nom']) ? $_GET['Nom'] : '';

$requete = "SELECT * FROM Chambres WHERE Id = $Id";

// Exécuter la requête
$result = $connexion->query($requete);

if ($connexion->connect_error) {
    die('Erreur de connexion à la base de données : ' . $connexion->connect_error);
}

if (!$result) {
    die('Erreur dans la requête : ' . $connexion->error);
}

// Insertion de la réservation dans la base de données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $arrival_date = date('Y-m-d', strtotime($_POST['arrival_date'])); // Formatage de la date
    $num_nights = $_POST['num_nights'];
    $chambre_id = $_POST['chambre_id'];
    $user_id = $_SESSION['user_id'];
    $nom = $_SESSION['user_nom'];
    $email = $_SESSION['user_email'];
    $reserved_dates_query = "SELECT date_debut, date_fin FROM Reservations WHERE chambre_id = $chambre_id";
    $reserved_dates_result = $connexion->query($reserved_dates_query);

    $reserved_dates = [];
    
        while ($row = $reserved_dates_result->fetch_assoc()) {
            $start_date = new DateTime($row['date_debut']);
            $end_date = new DateTime($row['date_fin']);
            $interval = new DateInterval('P1D'); // intervalle d'un jour
            $date_range = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // +1 jour pour inclure la date de fin

            foreach ($date_range as $date) {
                $reserved_dates[] = $date->format('Y-m-d');
            }
        }
    
    // Vérification de la disponibilité de la chambre pour les dates sélectionnées
    $reservation = "SELECT * FROM Reservations WHERE chambre_id = $chambre_id AND date_debut <= '$arrival_date' AND date_fin >= DATE_ADD('$arrival_date', INTERVAL $num_nights DAY)";
$reservation_result = $connexion->query($reservation);

if ($reservation_result->num_rows == 0) {
    // Vérification si la réservation existe déjà pour les mêmes dates
    $existing_reservation_query = "SELECT * FROM Reservations WHERE chambre_id = $chambre_id AND date_debut = '$arrival_date' AND date_fin = DATE_ADD('$arrival_date', INTERVAL $num_nights DAY)";
    $existing_reservation_result = $connexion->query($existing_reservation_query);

    if ($existing_reservation_result->num_rows == 0) {
        // Insérer la réservation dans la base de données
        $insert_reservation_query = "INSERT INTO Reservations (chambre_id, date_debut, date_fin, client_nom, client_email) VALUES ($chambre_id, '$arrival_date', DATE_ADD('$arrival_date', INTERVAL $num_nights DAY), '$nom', '$email')";
        $insert_reservation_result = $connexion->query($insert_reservation_query);

        if (!$insert_reservation_result) {
            die('Erreur lors de l\'insertion de la réservation : ' . $connexion->error);
        } else {
            $reservation_id = $connexion->insert_id;
            $_SESSION['reservation_id'] = $reservation_id;
            header("Location: paye.php");
            exit();
        }
    } else {
        $message = 'Une réservation existe déjà pour les mêmes dates.';
    }
} else {
    $message = 'Les dates sélectionnées chevauchent une réservation existante. Veuillez choisir d\'autres dates.';
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/stylees.css">
   
    <title>Réservation de chambre - Hôtel Neptune</title>
    <!-- Ajoutez vos liens vers les feuilles de style et scripts nécessaires ici -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="./js/script.js"></script>
    
    <script>
        // Votre script JavaScript ici
        $(document).ready(function() {
            // Fonction pour désactiver les dates déjà réservées dans le sélecteur de dates
            function disableReservedDates(date) {
                // Assurez-vous que la variable reservedDates est définie et qu'elle contient un tableau de dates réservées
                if (typeof reservedDates !== 'undefined' && reservedDates instanceof Array) {
                    var stringDate = $.datepicker.formatDate('yy-mm-dd', date);
                    return [reservedDates.indexOf(stringDate) == -1];
                } else {
                    // Si reservedDates n'est pas défini ou n'est pas un tableau, retournez true pour activer toutes les dates
                    return [true];
                }
            }

            // Définir la date minimale comme aujourd'hui
            var today = new Date();
            $("#arrival_date").datepicker("option", "minDate", today);

            // Initialiser le sélecteur de dates avec la fonction disableReservedDates
            $("#arrival_date").datepicker({
                beforeShowDay: disableReservedDates
            });
        });
    </script>
    
    <style>
        .ui-datepicker-unselectable {
            opacity: .35 !important;
            filter: grayscale(100%);
            -webkit-filter: grayscale(100%);
        }
    </style>
    
</head>

<body>

    <header>
        <div class="acceuil">
            <a href="hotel.php"><button id="home"><?php echo $lang['home']; ?></button></a>
        </div>

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
                <a href="?lang=en"><img src="./image/en.jpeg" style="width: 15%; " alt="English"></a>
            <?php else: ?>
                <a href="?lang=fr"><img src="./image/fr.jpeg"style="width: 15%; "alt="Français"></a>
            <?php endif; ?>
        </div>

    <section class="reservation-form">
        <!-- Contenu du formulaire de réservation -->
        <?php
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($result->num_rows > 0) {
                echo '<article class="room">';
                // Afficher les détails de la chambre en utilisant les données de $row
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="' . $row['Nom'] . '">';
                echo '<h2>' .'Nom de la chambre : '. $row['Nom'] . '</h2>';
                echo '<p>' .'Description de la chambre : '. $row['Description'] . '</p>';
                echo '<p>' . $lang['nombres'] . ' : ' . $row['Pieces'] . '</p>';
                echo '<p>' . $lang['nombre'] . ' : ' . $row['Personne'] . '</p>';
                echo '<p>'.'Prix : ' . $row['Prix'] . '€ ' . $lang['per_night'] . '</p>';
                echo '</article>';
            } else {
                echo 'Aucune chambre ne correspond aux critères.';
            }
        }
        ?>

        <form action="" method="post">
            <?php
            if (!isset($_SESSION['user_id'])) {
                echo '<p>Vous devez être connecté pour réserver une chambre. <a href="enregistre.php">Cliquez ici pour vous connecter.</a></p>';
                die();
            }
            ?>
            <label for="arrival_date"><?php echo $lang['arrival_date']; ?></label>
            <input type="text" id="arrival_date" name="arrival_date" >

            <label for="num_nights"><?php echo $lang['num_nights']; ?></label>
            <input type="number" id="num_nights" name="num_nights" required min="1">

            <input type="hidden" name="chambre_id" value="<?php echo $Id; ?>">
            <input type="hidden" name="Nom" value="<?php echo $Nom; ?>">
            <button type="submit"><?php echo $lang['reserve_now']; ?></button>
            <script>
        var reservedDates = <?php echo json_encode($reserved_dates); ?>;
            </script>
        </form>
        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif;?>
<footer>
        <p>&copy; <?php echo date("Y"); ?> Hôte Neptune. <?php echo $lang['all_rights_reserved']; ?></p>
    </footer>
</section>
</body>

</html>
