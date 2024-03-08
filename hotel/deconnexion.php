<?php
session_start();
// Détruire la session
session_destroy();
// Rediriger vers la page d'accueil ou autre page après la déconnexion
$redirect_url = isset($_SESSION['previous_page']) ? $_SESSION['previous_page'] : "enregistre.php";
header("Location: $redirect_url");
exit();
exit();
?>