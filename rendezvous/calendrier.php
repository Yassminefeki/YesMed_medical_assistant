
<?php
session_start();
require_once '../config/bd.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$base_url = '/YesMed/';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Calendrier des Rendez-vous - YesMed</title>
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js"></script>

</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="calendar-container">
    <h2>Calendrier des Rendez-vous</h2>
    <div id="calendar"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '<?= $base_url ?>rendezvous/get_events.php',
        eventClick: function(info) {
            alert('Rendez-vous avec: ' + info.event.title + 
                  '\nLe: ' + info.event.start.toLocaleDateString() +
                  '\nÀ: ' + info.event.start.toLocaleTimeString());
        },
        locale: 'fr'
    });
    calendar.render();
});
</script>
</body>
</html>
