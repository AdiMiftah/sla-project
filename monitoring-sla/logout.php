<?php
// Mulai session
session_start();

// Hapus semua data session
session_unset();

// Hapus cookie session
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
  );
}

// Hapus session
session_destroy();

// Redirect ke halaman login
header('Location: login.php');
exit;
?>



