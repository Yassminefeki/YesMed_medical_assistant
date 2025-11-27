<?php
session_start();
require_once '../config/bd.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$base_url = '/YesMed/';

// Fetch appointments for the list view
$stmt = $conn->prepare("SELECT * FROM rendezvous WHERE user_id = ? ORDER BY date, heure");
$stmt->execute([$_SESSION['user_id']]);
$rendezvous = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des Rendez-vous - YesMed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
        }

        .center-page {
            width: 100%;
            min-height: 100vh;
            padding: 3rem 4rem;
        }

        .login-container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            border-radius: 16px;
            padding: 2.5rem 3rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .login-container h2 {
            font-size: 2rem;
            color: #1e3a8a;
            margin-bottom: 2rem;
        }

        .calendar-container {
            margin-bottom: 3rem;
        }

        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        .rendezvous-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .rendezvous-card {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .rendezvous-card:hover {
            transform: translateY(-4px);
        }

        .rendezvous-card h3 {
            font-size: 1.5rem;
            color: #2563eb;
            margin-bottom: 1rem;
        }

        .rendezvous-card p {
            font-size: 1rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }

        .actions {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-add {
            background-color: #10b981;
            color: white;
            margin-bottom: 1.5rem;
        }

        .btn-add:hover {
            background-color: #059669;
        }

        .btn-edit {
            background-color: #3b82f6;
            color: white;
        }

        .btn-edit:hover {
            background-color: #2563eb;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        @media (max-width: 768px) {
            .center-page {
                padding: 1.5rem;
            }

            .login-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="center-page">
        <div class="login-container">
            <h2>Gestion des Rendez-vous</h2>

            <!-- Calendar Section -->
            <div class="calendar-container">
                <h3>Calendrier</h3>
                <div id="calendar"></div>
            </div>

            <!-- Appointment List Section -->
            <a href="ajouter_rendezvous.php" class="btn btn-add">Ajouter un rendez-vous</a>
            <?php if (count($rendezvous) > 0): ?>
                <div class="rendezvous-list">
                    <?php foreach ($rendezvous as $rdv): ?>
                        <div class="rendezvous-card">
                            <h3><?= htmlspecialchars($rdv['medecin_name']) ?></h3>
                            <p><strong>Spécialité:</strong> <?= htmlspecialchars($rdv['specialite']) ?></p>
                            <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($rdv['date'])) ?></p>
                            <p><strong>Heure:</strong> <?= date('H:i', strtotime($rdv['heure'])) ?></p>
                            <div class="actions">
                                <a href="modifier_rendezvous.php?id=<?= $rdv['id'] ?>" class="btn btn-edit">Modifier</a>
                                <a href="supprimer_rendezvous.php?id=<?= $rdv['id'] ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');">Supprimer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aucun rendez-vous trouvé.</p>
            <?php endif; ?>
        </div>
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

    <?php include '../includes/footer.php'; ?>
</body>
</html>