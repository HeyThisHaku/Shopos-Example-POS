<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$userName = $_SESSION['name'];
$isAdmin = isAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products — ShopOS</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<nav class="navbar">
  <div class="nav-brand">
    <div class="brand-logo-sm">S</div>
    <span>ShopOS</span>
  </div>
  <div class="nav-links">
    <?php if ($isAdmin): ?>
    <a href="/admin.php" class="nav-link">Dashboard</a>
    <?php endif; ?>
    <span class="nav-user">👤 <?= htmlspecialchars($userName) ?></span>
    <button onclick="logout()" class="btn-logout">Logout</button>
  </div>
</nav>

<main class="main-content">
  <div class="page-header">
    <div>
      <h1>Products</h1>
      <p>Browse our collection</p>
    </div>
    <div class="filter-bar">
      <input type="text" id="searchInput" placeholder="Search products..." class="search-input">
      <select id="categoryFilter" class="select-filter">
        <option value="">All Categories</option>
        <option value="Electronics">Electronics</option>
        <option value="Accessories">Accessories</option>
        <option value="Audio">Audio</option>
      </select>
    </div>
  </div>

  <div id="loadingState" class="loading-state">
    <div class="spinner-lg"></div>
    <p>Loading products...</p>
  </div>

  <div id="productsGrid" class="products-grid"></div>
</main>

<div id="productModal" class="modal hidden">
  <div class="modal-overlay" onclick="closeModal()"></div>
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <img id="modalImg" src="" alt="">
    <div class="modal-body">
      <div class="modal-badge" id="modalCategory"></div>
      <h2 id="modalName"></h2>
      <p id="modalDesc"></p>
      <div class="modal-footer">
        <span class="price-tag" id="modalPrice"></span>
        <span class="stock-badge" id="modalStock"></span>
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/products.js"></script>
</body>
</html>
