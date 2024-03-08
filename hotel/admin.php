<?php
session_start();
$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
// Vérification de l'authentification et du rôle
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
// Connexion à la base de données
$connexion = new mysqli('localhost', 'root', 'root', 'hotel');
if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}

// Récupération des informations des chambres
$requete = "SELECT * FROM Chambres"; // Assurez-vous que le nom de la table et les colonnes correspondent à votre base de données
$resultat = $connexion->query($requete);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_room'])) {
        $id = $_POST['delete_room'];
    // Exécutez la requête SQL pour supprimer la chambre avec l'ID spécifié
    $delete_query = "DELETE FROM Chambres WHERE Id = ?";
    $stmt = $connexion->prepare($delete_query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Chambre supprimée avec succès.";
        // Rafraîchissez la page pour refléter les changements
        header("Refresh:0");
    } else {
        echo "Erreur lors de la suppression de la chambre : " . $stmt->error;
    }
    $stmt->close();
    }
    else {
    $id = $_POST['Id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $disponibilite = $_POST['disponibilite'];
    $Personne = $_POST['Personne'];
    $Piece = $_POST['Piece'];

    // Mise à jour de la chambre dans la base de données
    $insert_query = "INSERT INTO Chambres (Nom,Id, Personne, Pieces ,Description, Prix, disponibilite) VALUES (?,?, ?, ?, ?, ?,?)";
    $stmt = $connexion->prepare($insert_query);
    $stmt->bind_param("siiisdi", $nom,$id, $Personne,$Piece, $description, $prix, $disponibilite);

    if ($stmt->execute()) {
        echo "Chambre mise à jour avec succès.";
        header("Refresh:0");
    } else {
        
        echo "Erreur lors de la mise à jour : " . $stmt->error;
    }

    $stmt->close();
}
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Gestion des Chambres</title>
    <link rel="stylesheet" href="./css/stylees.css"> <!-- Assurez-vous d'avoir ce fichier CSS pour le style -->
</head>

<body>
<header>
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

        </div>
    <h1>Administration des Chambres</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultat->num_rows > 0): ?>
                <?php while($row = $resultat->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Id']); ?></td>
                        <td><?php echo htmlspecialchars($row['Nom']); ?></td>
                        <td><?php echo htmlspecialchars($row['Description']); ?></td>
                        <td><?php echo htmlspecialchars($row['Prix']); ?></td>
                        <td><?php if (htmlspecialchars($row['disponibilite']) == 1){
                            echo "disponible";
                        }else{
                            echo "indisponible" ;
                        }
                         ?></td>
                        <td>
                            <a href="edit-room.php?id=<?php echo $row['Id']; ?>">Modier</a>
                            <a href="edit-res.php?id=<?php echo $row['Id']; ?>">Voir reservations</a>  
                            <form method="post"> <input type="hidden" name="delete_room" value="<?php echo $row['Id']; ?>"> <button type="submit">Supprimer</button>
                    </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Aucune chambre trouvée</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <form method="post">
    <label for="Id"> Numéro id :</label>
    <input type="number" id="Id" name="Id" >
    <br>
    <label for="nom">Nom de la chambre :</label>
    <input type="text" id="nom" name="nom" required>
    <br>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required></textarea>
    <br>

    <label for="prix">Prix :</label>
    <input type="number" id="prix" name="prix" required>
    <br>
    <label for="Piece">Nombre de pieces :</label>
    <input type="number" id="Piece" name="Piece" required>
    <br>

    <label for="Personne">Nombre de Personne :</label>
    <input type="number" id="Personne" name="Personne" required min= 1>
    <br>
    <label for="disponibilite">Statut :</label>
    <select id="disponibilite" name="disponibilite" required>
        <option value="0">Indisponible</option>
        <option value="1">Disponible</option>
        <option value="2">En maintenance</option>
    </select>
    
    <button type="submit">Ajouter la chambre</button>
</form>
    <?php
    $connexion->close();
    ?>
</body>
</html>
