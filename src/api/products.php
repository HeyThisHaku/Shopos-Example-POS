<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// GET - public
if ($method === 'GET') {
    $pdo = getDB();
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (name LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    jsonResponse(['products' => $stmt->fetchAll()]);
}

// Protected routes
requireAdmin();
$pdo = getDB();

if ($method === 'POST') {
    $fields = ['name','description','price','stock','category','image_url'];
    $data = [];
    foreach ($fields as $f) $data[$f] = $input[$f] ?? '';

    if (!$data['name'] || !$data['price']) {
        jsonResponse(['error' => 'Name and price are required'], 400);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name,description,price,stock,category,image_url) VALUES (?,?,?,?,?,?)");
    $stmt->execute(array_values($data));
    jsonResponse(['success' => true, 'id' => $pdo->lastInsertId()]);
}

if ($method === 'PUT') {
    $id = $input['id'] ?? 0;
    if (!$id) jsonResponse(['error' => 'ID required'], 400);

    $stmt = $pdo->prepare("UPDATE products SET name=?,description=?,price=?,stock=?,category=?,image_url=? WHERE id=?");
    $stmt->execute([
        $input['name'], $input['description'], $input['price'],
        $input['stock'], $input['category'], $input['image_url'], $id
    ]);
    jsonResponse(['success' => true]);
}

if ($method === 'DELETE') {
    $id = $input['id'] ?? $_GET['id'] ?? 0;
    if (!$id) jsonResponse(['error' => 'ID required'], 400);
    $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Method not allowed'], 405);
