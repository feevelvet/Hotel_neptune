<!-- index.php -->

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
// Vérification des permissions...
// Assurez-vous que seul un utilisateur connecté puisse accéder à cette page
if (!isset($_SESSION['user_id'])) {
    header("Location: enregistre.php");
    exit();
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', 'root', 'hotel');
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$message = "";

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur depuis la base de données
$requeteUtilisateur = $conn->prepare("SELECT * FROM users WHERE Id = ?");
$requeteUtilisateur->bind_param("i", $user_id);
$requeteUtilisateur->execute();
$resultatUtilisateur = $requeteUtilisateur->get_result();
$utilisateur = $resultatUtilisateur->fetch_assoc();
$requeteUtilisateur->close();

// Récupérer les réservations de l'utilisateur depuis la base de données
$requeteReservations = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
if (!$requeteReservations) {
    die("Erreur de préparation de la requête : " . $conn->error);
}
$requeteReservations->bind_param("i", $user_id);
if (!$requeteReservations->execute()) {
    die("Erreur lors de l'exécution de la requête : " . $requeteReservations->error);
}
$resultatReservations = $requeteReservations->get_result();
$reservations = $resultatReservations->fetch_all(MYSQLI_ASSOC);
$requeteReservations->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="./css/profile.css">
    <script src="./js/profile.js" defer></script>
</head>
<body>

    <h1>Profil de <?php echo htmlspecialchars($utilisateur['Nom']) . ' ' . htmlspecialchars($utilisateur['Prenom']) ; ?></h1>
    <div>
        <h2>Informations Utilisateur :</h2>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($utilisateur['Prenom']); ?></p>
        <p><strong>Prenom:</strong> <?php echo htmlspecialchars($utilisateur['Nom']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($utilisateur['Email']); ?></p>
        <!-- Ajoutez d'autres informations utilisateur ici -->
    </div>

    <div>
        <h2>Réservations :</h2>
        <?php if (count($reservations) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <!-- Ajoutez d'autres colonnes au besoin -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['date_debut']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['date_fin']); ?></td>
                            <!-- Ajoutez d'autres colonnes au besoin -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune réservation trouvée.</p>
        <?php endif; ?>
    </div>

     <a href="#" id="edit-profile" onclick="toggleEditField('profile-info'); return false;">Modifier le profil</a>
    <a href="deconnexion.php">Déconnexion</a> <!-- Lien pour se déconnecter -->
</body>
</html>
