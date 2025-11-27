<?php
session_start(); 
require_once '../config/bd.php';

$stmt = $conn->prepare("SELECT * FROM contacts_urgence WHERE user_id = ? ORDER BY nom");
$stmt->execute([$_SESSION['user_id']]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contacts d'Urgence</title>
  <style>
    /* Reset & Base */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      width: 100%;
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4f8;
    }

    /* Full-width dashboard style layout */
    .center-page {
      width: 100%;
      min-height: 100vh;
      padding: 3rem 4rem;
      background-color: #f0f4f8;
    }

    .login-container {
      max-width: 1200px;
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

    /* Add Contact Button */
    .login-container > .btn {
      display: inline-block;
      background-color: #3b82f6;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      margin-bottom: 2rem;
      transition: background-color 0.3s ease;
    }

    .login-container > .btn:hover {
      background-color: #2563eb;
    }

    /* Contacts Grid */
    .contacts-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 2rem;
    }

    .contact-card {
      background-color: #f9fafb;
      border-left: 5px solid #3b82f6;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
      transition: transform 0.2s ease;
    }

    .contact-card:hover {
      transform: translateY(-4px);
    }

    .contact-card h3 {
      margin: 0 0 0.5rem 0;
      font-size: 1.3rem;
      color: #1e40af;
    }

    .contact-card p {
      margin: 0.3rem 0;
      color: #374151;
    }

    .contact-card a {
      color: #2563eb;
      text-decoration: none;
    }

    .contact-card a:hover {
      text-decoration: underline;
    }

    /* Buttons inside card */
    .actions {
      margin-top: 1rem;
      display: flex;
      gap: 1rem;
    }

    .btn-edit {
      background-color: #10b981;
      color: white;
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
    }

    .btn-edit:hover {
      background-color: #059669;
    }

    .btn-delete {
      background-color: #ef4444;
      color: white;
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
    }

    .btn-delete:hover {
      background-color: #dc2626;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .center-page {
        padding: 1.5rem;
      }

      .login-container {
        padding: 1.5rem;
      }

      .contacts-list {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <div class="center-page">
    <div class="login-container">
      <h2>Contacts d'Urgence</h2>

      <a href="ajouter_contact.php" class="btn">Ajouter un contact</a>

      <?php if (count($contacts) > 0): ?>
        <div class="contacts-list">
          <?php foreach ($contacts as $contact): ?>
            <div class="contact-card">
              <h3><?= htmlspecialchars($contact['nom']) ?></h3>
              <p><strong>Téléphone:</strong> <a href="tel:<?= htmlspecialchars($contact['tel']) ?>"><?= htmlspecialchars($contact['tel']) ?></a></p>
              <div class="actions">
                <a href="modifier_contact.php?id=<?= $contact['id'] ?>" class="btn-edit">Modifier</a>
                <a href="supprimer_contact.php?id=<?= $contact['id'] ?>" class="btn-delete">Supprimer</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>Aucun contact trouvé.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>

</body>
</html>

