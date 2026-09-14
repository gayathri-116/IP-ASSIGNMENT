<?php
require_once __DIR__ . '/../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Demo credentials for the assignment — change these before deploying publicly.
    if ($username === 'admin' && $password === 'portfolio123') {
        $_SESSION['is_admin'] = true;
        header('Location: messages.php');
        exit;
    }
    $error = 'Incorrect username or password.';
}

if (is_logged_in()) {
    header('Location: messages.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Gayathri S</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="site-nav">
  <div class="nav-inner">
    <a href="../index.html" class="brand"><span class="dot"></span>Gayathri S</a>
    <nav class="nav-links"><a href="../index.html" class="active">Back to site</a></nav>
  </div>
</header>

<div class="page-header">
  <div class="container">
    <h1>Admin login</h1>
    <p>Sign in to view and manage messages submitted through the contact form.</p>
  </div>
</div>

<section>
  <div class="container" style="max-width: 420px;">
    <div class="form-card">
      <?php if ($error): ?>
        <div class="field invalid" style="border:none;">
          <div class="error" style="display:block;"><?= htmlspecialchars($error) ?></div>
        </div>
      <?php endif; ?>
      <form method="POST" action="login.php">
        <div class="field">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="admin">
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Log in</button>
      </form>
      <p style="margin-top: 18px; font-size: 0.82rem;">
        Demo credentials — username <b>admin</b>, password <b>portfolio123</b>.
      </p>
    </div>
  </div>
</section>

<footer class="site-footer">
  <div class="container">&copy; <span id="year"></span> Gayathri S · Madurai, India</div>
</footer>

<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
