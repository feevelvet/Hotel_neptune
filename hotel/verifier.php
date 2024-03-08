<?php
// Fonction pour vérifier la disponibilité des dates
function verifier_disponibilite($arrival_date, $num_nights, $chambre_id) {
    // Connexion à la base de données (à adapter selon tes paramètres)
    $connexion = new mysqli('localhost', 'root', 'root', 'hotel');
    
    // Vérifier la connexion
    if ($connexion->connect_error) {
        die('Erreur de connexion à la base de données : ' . $connexion->connect_error);
    }
    
    // Convertir la date d'arrivée en format compatible avec MySQL
    $formatted_arrival_date = date('Y-m-d', strtotime($arrival_date));
    
    // Calculer la date de départ en ajoutant le nombre de nuits à la date d'arrivée
    $formatted_departure_date = date('Y-m-d', strtotime($formatted_arrival_date . ' + ' . $num_nights . ' days'));
    
    // Requête SQL pour vérifier les réservations existantes qui chevauchent les dates sélectionnées
    $requete = "SELECT COUNT(*) AS count FROM reservations WHERE chambre_id = $chambre_id 
                AND arrival_date < '$formatted_departure_date' 
                AND departure_date > '$formatted_arrival_date'";
    
    // Exécuter la requête
    $resultat = $connexion->query($requete);
    
    if (!$resultat) {
        die('Erreur dans la requête : ' . $connexion->error);
    }
    
    // Récupérer le nombre de réservations existantes qui chevauchent les dates sélectionnées
    $row = $resultat->fetch_assoc();
    $count = $row['count'];
    
    // Fermer la connexion
    $connexion->close();
    
    // Retourner vrai si aucune réservation existante ne chevauche les dates sélectionnées, sinon faux
    return $count == 0;
}
?>
