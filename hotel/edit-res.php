<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
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
// Vérification des permissions...
// Assurez-vous que seul un utilisateur avec le rôle 'admin' puisse accéder à cette page

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', 'root', 'hotel');
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$message = "";

// Récupérer l'ID de la chambre depuis l'URL
$idChambre = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si l'ID de la chambre est valide, récupérer les détails de la chambre depuis la base de données
if ($idChambre > 0) {
    $requeteChambre = $conn->prepare("SELECT * FROM Chambres WHERE Id = ?");
    $requeteChambre->bind_param("i", $idChambre);
    $requeteChambre->execute();
    $resultatChambre = $requeteChambre->get_result();
    $chambre = $resultatChambre->fetch_assoc();
    $requeteChambre->close();

    // Récupérer les réservations liées à cette chambre depuis la base de données
    $requeteReservations = $conn->prepare("SELECT * FROM Reservations WHERE chambre_id = ?");
    $requeteReservations->bind_param("i", $idChambre);
    $requeteReservations->execute();
    $resultatReservations = $requeteReservations->get_result();
    $reservations = $resultatReservations->fetch_all(MYSQLI_ASSOC);
    $requeteReservations->close();
} else {
    echo "ID de chambre invalide.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservations de la Chambre</title>
    <link rel="stylesheet" href="./css/stylees.css">
</head>

<body>
<div class="acceuil">
            <a href="admin.php"><button id="home"><?php echo $lang['home']; ?></button></a>
        </div>

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
            </div>
            </header>
    <h1>Réservations de la Chambre <?php echo htmlspecialchars($chambre['Nom']); ?></h1>
    <h2>Détails de la Chambre :</h2>
    <p>Nom : <?php echo htmlspecialchars($chambre['Nom']); ?></p>
    <p>Description : <?php echo htmlspecialchars($chambre['Description']); ?></p>
    <p>Prix : <?php echo htmlspecialchars($chambre['Prix']); ?></p>

    <h2>Réservations :</h2>
    <?php if (count($reservations) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Nom du client</th>
                    <th>Email du client</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['date_debut']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['date_fin']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['client_nom']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['client_email']); ?></td>
                        <td>
                            <a href="modifier_reservation.php?id=<?php echo $reservation['id']; ?>">Modifier</a>
                            <a href="supprimer_reservation.php?id=<?php echo $reservation['id']; ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune réservation pour cette chambre.</p>
    <?php endif; ?>
    <div class="acceuil">
            <a href="hotel.php"><button id="home"><?php echo $lang['home']; ?></button></a>
        </div>

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
</body>
</html>
