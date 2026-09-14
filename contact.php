<?php
require_once __DIR__ . '/includes/db.php';

$errors = [];
$success = false;
$name = $email = $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['clear_all'])) {
        // Data-management action: wipe the message log from the database.
        $pdo->exec('DELETE FROM messages');
        header('Location: contact.php');
        exit;
    }

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (strlen($name) < 2) {
        $errors['name'] = 'Please enter your name (at least 2 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (strlen($message) < 10) {
        $errors['message'] = 'Your message should be at least 10 characters.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO messages (name, email, message, created_at) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $message, date('d M Y, h:i A')]);
        $success = true;
        $name = $email = $message = '';
    }
}

$messages = $pdo->query('SELECT * FROM messages ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact — Gayathri S</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="site-nav">
  <div class="nav-inner">
    <a href="index.html" class="brand"><span class="dot"></span>Gayathri S</a>
    <nav class="nav-links">
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <a href="academics.html">Academics</a>
      <a href="projects.html">Projects</a>
      <a href="skills.html">Skills</a>
      <a href="achievements.html">Achievements</a>
      <a href="internships.html">Internships</a>
      <a href="contact.php" class="active">Contact</a>
    </nav>
    <button class="nav-toggle" aria-label="Toggle menu"><span></span><span></span><span></span></button>
  </div>
</header>

<div class="page-header">
  <div class="container">
    <h1>Get in touch</h1>
    <p>Have an opportunity, a project, or just a question? Send me a message below — it's saved straight to a database on the server.</p>
  </div>
</div>

<section>
  <div class="container contact-grid">

    <div class="contact-card">
      <h3>Contact details</h3>

      <div class="contact-item">
        <div class="ico">📞</div>
        <div>
          <div class="label">Phone</div>
          <div class="value">+91 94457 74010</div>
        </div>
      </div>

      <div class="contact-item">
        <div class="ico">✉️</div>
        <div>
          <div class="label">Email</div>
          <div class="value">gayathri26.11.06@gmail.com</div>
        </div>
      </div>

      <div class="contact-item">
        <div class="ico">📍</div>
        <div>
          <div class="label">Location</div>
          <div class="value">Madurai, Tamil Nadu, India</div>
        </div>
      </div>

      <div class="contact-item">
        <div class="ico">🔗</div>
        <div>
          <div class="label">LinkedIn</div>
          <div class="value"><a href="https://www.linkedin.com/in/gayathri-s-82017a328" target="_blank" rel="noopener">gayathri-s-82017a328</a></div>
        </div>
      </div>
    </div>

    <div class="form-card">
      <h3 style="margin-bottom: 20px;">Send a message</h3>
      <form method="POST" action="contact.php" novalidate>
        <div class="field <?= isset($errors['name']) ? 'invalid' : '' ?>">
          <label for="field-name">Your name</label>
          <input type="text" id="field-name" name="name" placeholder="e.g. Arjun Kumar" value="<?= htmlspecialchars($name) ?>">
          <div class="error"><?= htmlspecialchars($errors['name'] ?? 'Please enter your name (at least 2 characters).') ?></div>
        </div>
        <div class="field <?= isset($errors['email']) ? 'invalid' : '' ?>">
          <label for="field-email">Your email</label>
          <input type="email" id="field-email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($email) ?>">
          <div class="error"><?= htmlspecialchars($errors['email'] ?? 'Please enter a valid email address.') ?></div>
        </div>
        <div class="field <?= isset($errors['message']) ? 'invalid' : '' ?>">
          <label for="field-message">Message</label>
          <textarea id="field-message" name="message" rows="5" placeholder="What would you like to say?"><?= htmlspecialchars($message) ?></textarea>
          <div class="error"><?= htmlspecialchars($errors['message'] ?? 'Your message should be at least 10 characters.') ?></div>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Send message</button>
        <?php if ($success): ?>
          <div class="form-msg ok show">Thanks! Your message has been saved to the database.</div>
        <?php endif; ?>
      </form>
    </div>

  </div>

  <div class="container log-wrap">
    <div class="section-head">
      <div class="mark"></div>
      <div>
        <h2>Messages received</h2>
        <p>Every submission is validated on the server and written to a SQLite database with PHP — this page's dynamic, server-side feature.</p>
      </div>
    </div>
    <table class="log-table">
      <thead>
        <tr><th>Name</th><th>Email</th><th>Message</th><th>Received</th></tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $msg): ?>
        <tr>
          <td><?= htmlspecialchars($msg['name']) ?></td>
          <td><?= htmlspecialchars($msg['email']) ?></td>
          <td><?= htmlspecialchars($msg['message']) ?></td>
          <td><?= htmlspecialchars($msg['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($messages)): ?>
      <div class="log-empty">No messages yet — be the first to send one above.</div>
    <?php endif; ?>
    <?php if (!empty($messages)): ?>
      <form method="POST" action="contact.php" onsubmit="return confirm('Delete all saved messages? This cannot be undone.');">
        <input type="hidden" name="clear_all" value="1">
        <button type="submit" class="clear-log">Clear all messages</button>
      </form>
    <?php endif; ?>
  </div>
</section>

<footer class="site-footer">
  <div class="container">&copy; <span id="year"></span> Gayathri S · Madurai, India · Built with HTML, CSS, JavaScript &amp; PHP · <a href="admin/login.php">Admin</a></div>
</footer>

<script src="js/main.js"></script>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
