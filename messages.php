<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare('DELETE FROM messages WHERE id = ?');
    $stmt->execute([(int) $_POST['delete_id']]);
    header('Location: messages.php');
    exit;
}

$messages = $pdo->query('SELECT * FROM messages ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Inbox — Gayathri S</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="site-nav">
  <div class="nav-inner">
    <a href="../index.html" class="brand"><span class="dot"></span>Gayathri S</a>
    <nav class="nav-links">
      <a href="../index.html">Back to site</a>
      <a href="logout.php">Log out</a>
    </nav>
  </div>
</header>

<div class="page-header">
  <div class="container">
    <h1>Admin inbox</h1>
    <p>All messages currently stored in the database, most recent first.</p>
  </div>
</div>

<section>
  <div class="container">
    <table class="log-table">
      <thead>
        <tr><th>Name</th><th>Email</th><th>Message</th><th>Received</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $msg): ?>
        <tr>
          <td><?= htmlspecialchars($msg['name']) ?></td>
          <td><?= htmlspecialchars($msg['email']) ?></td>
          <td><?= htmlspecialchars($msg['message']) ?></td>
          <td><?= htmlspecialchars($msg['created_at']) ?></td>
          <td>
            <form method="POST" action="messages.php" onsubmit="return confirm('Delete this message?');">
              <input type="hidden" name="delete_id" value="<?= (int) $msg['id'] ?>">
              <button type="submit" class="clear-log" style="margin: 0;">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($messages)): ?>
      <div class="log-empty">No messages in the database yet.</div>
    <?php endif; ?>
  </div>
</section>

<footer class="site-footer">
  <div class="container">&copy; <span id="year"></span> Gayathri S · Madurai, India</div>
</footer>

</body>
</html>
