<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

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
$message="";
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];

    // Hacher le mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);


    // Créer une connexion à la base de données
    $conn = new mysqli('localhost', 'root', 'root', 'hotel');

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (Email, Password, Nom, Prenom) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $passwordHash, $nom, $prenom);

    // Exécuter la requête
    if ($stmt->execute()) {
        $message= "Inscription réussie !";
    } else {
        $message=  "Erreur lors de l'inscription : " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/stylo.css">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body style="display:flex; align-items:center; justify-content:center;">
  <img src="./image/logo.png" alt="Logo de l'hôtel" class="logo">
            <div class="header-buttons">
       <?php if ($_SESSION['lang'] == 'fr'): ?>
           
            <a href="?lang=en"><img src="./image/en.jpeg" style="width: 15%; "alt="English"></a>
        <?php else: ?>
            
            <a href="?lang=fr"><img src="./image/fr.jpeg"style="width: 15%; "alt="Français"></a>
            
        <?php endif; ?>
</div>
  <div class="acceuil">
                    <a href="hotel.php"><button id="home-button"><?php echo $lang['home']; ?></button></a>
  </div>
        
<div class="login-page">
  <div class="form">
    <form action="enregistre2.php" method="post">
        <label for="email">E-mail :</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
        <br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>
        
        <br>

        <input type="submit" value="S'inscrire">
        <br>
        <?php echo $message;?>
        <p class="message">Vous avez déjà un compte <a href="enregistre.php">Se connecter</a></p>
    </form>
      </div>
</div>
</body>
</html>

