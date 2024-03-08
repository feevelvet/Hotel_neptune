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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $disponibilite = $_POST['disponibilite'];

    // Mise à jour de la chambre dans la base de données
    $stmt = $conn->prepare("UPDATE Chambres SET Nom = ?, Description = ?, Prix = ? , disponibilite = ? WHERE Id = ?");
    $stmt->bind_param("ssdii", $nom, $description, $prix, $disponibilite , $id);

    if ($stmt->execute()) {
        $message = "Chambre mise à jour avec succès.";
        header("Refresh:0");
    } else {
        echo $prix, $id , $nom , $description , $disponibilite ;
        $message = "Erreur lors de la mise à jour : " . $stmt->error;
    }

    $stmt->close();
}

// Si l'ID est valide, récupérer les détails de la chambre depuis la base de données
if ($idChambre > 0) {
    $requete = $conn->prepare("SELECT * FROM Chambres WHERE Id = ?");
    $requete->bind_param("i", $idChambre);
    $requete->execute();
    $resultat = $requete->get_result();
    $chambre = $resultat->fetch_assoc();
    $requete->close();
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
    <title>Modifier Chambre</title>
    <link rel="stylesheet" href="./css/stylees.css">
</head>

<body>
<header>
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
    <h1>Modifier la Chambre</h1>
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $idChambre; ?>">

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($chambre['Nom']); ?>" required>
        <br>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($chambre['Description']); ?></textarea>
        <br>

        <label for="prix">Prix :</label>
        <input type="number" id="prix" name="prix" value="<?php echo htmlspecialchars($chambre['Prix']); ?>" required>
        <br>
        <label for="disponibilite">Prix :</label>
        <input type="number" id="disponibilite" name="disponibilite" value="<?php echo htmlspecialchars($chambre['disponibilite']); ?>" required min = 0 max = 1>
        <br>

        <input type="submit" value="Mettre à jour">
    </form>
</body>

</html>
