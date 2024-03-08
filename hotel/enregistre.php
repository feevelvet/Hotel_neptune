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
}

// Assurez-vous de configurer votre connexion à la base de données ici.
$conn = new mysqli('localhost', 'root', 'root', 'hotel');

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$stmt = null;  // Initialisez $stmt en dehors de la condition POST

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];
    // Rechercher l'utilisateur dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!($user && password_verify($password, $user['Password']))) {
    // Afficher le message si les identifiants sont incorrects
    $message = "Identifiants incorrects. Veuillez réessayer.";
} else {
    // Démarrer la session et stocker des informations sur l'utilisateur connecté
    $_SESSION['user_id'] = $user['Id'];
    $_SESSION['user_email'] = $user['Email'];
    $_SESSION['user_nom'] = $user['Nom'];
    $_SESSION['user_prenom'] = $user['Prenom'];
    $_SESSION['user_role'] = $user['role'];
    
    // Rediriger vers la page d'accueil ou toute autre page après la connexion réussie
    $redirect_url = isset($_SESSION['previous_page']) ? $_SESSION['previous_page'] : "enregistre.php";
    header("Location: $redirect_url");
    exit();
}
} else {
    $message = "Veuillez remplir les champs"; // Message si le formulaire n'est pas soumis
}

// Fermer la connexion
if ($stmt) {
    $stmt->close();
}
$conn->close();
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
    <form class="login-form" method="post" action="enregistre.php" >
      <h2><i class="fas fa-lock"></i> Se connecter</h2>
      <input type="text" name="email" placeholder="Email" required/>
      <input type="password" name="password" placeholder="Mot de passe" required/> 
      <button type="submit">Se connecter</button>
      <?php echo $message;?>
      <p class="message">Vous avez pas de compte ? <a href="enregistre2.php">créer un compte</a></p>
    </form>
  </div>
</div>

</body>
</html>

