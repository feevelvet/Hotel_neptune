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
