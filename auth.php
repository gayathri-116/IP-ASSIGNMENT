<?php
/**
 * Simple session-based authentication for the admin message inbox.
 * Demo credentials live in admin/login.php — change them before
 * putting this site on a public server.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}
