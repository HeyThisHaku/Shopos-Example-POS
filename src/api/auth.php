<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'login') {
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (!$email || !$password) {
        jsonResponse(['error' => 'Email and password are required'], 400);
    }

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || md5($password) !== $user['password']) {
        jsonResponse(['error' => 'Invalid email or password'], 401);
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    jsonResponse([
        'success' => true,
        'redirect' => $user['role'] === 'admin' ? '/admin.php' : '/products.php'
    ]);
}

if ($action === 'register') {
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (!$name || !$email || !$password) {
        jsonResponse(['error' => 'All fields are required'], 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['error' => 'Invalid email format'], 400);
    }

    if (strlen($password) < 6) {
        jsonResponse(['error' => 'Password must be at least 6 characters'], 400);
    }

    $pdo = getDB();
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        jsonResponse(['error' => 'Email already registered'], 409);
    }

    $hashed = md5($password);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
    $stmt->execute([$name, $email, $hashed]);

    jsonResponse(['success' => true]);
}

if ($action === 'logout') {
    session_destroy();
    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Invalid action'], 400);
