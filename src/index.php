<?php
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? '/admin.php' : '/products.php'));
} else {
    header('Location: /login.php');
}
exit;
