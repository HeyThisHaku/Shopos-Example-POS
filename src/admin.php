<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();
$userName = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — ShopOS</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<div class="dashboard">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-logo-sm">S</div>
      <span>ShopOS</span>
    </div>
    <nav class="sidebar-nav">
      <a href="#" class="nav-item active" data-section="products">
        <span class="nav-icon">📦</span>Products
      </a>
      <a href="/products.php" class="nav-item">
        <span class="nav-icon">🛒</span>Store View
      </a>
    </nav>
    <div class="sidebar-user">
      <div class="avatar"><?= strtoupper($userName[0]) ?></div>
      <div>
        <p><?= htmlspecialchars($userName) ?></p>
        <small>Administrator</small>
      </div>
      <button onclick="logout()" class="btn-icon" title="Logout">⏏</button>
    </div>
  </aside>

  <!-- Main -->
  <main class="dash-main">
    <header class="dash-header">
      <div>
        <h1>Dashboard</h1>
        <p>Welcome back, <?= htmlspecialchars($userName) ?>!</p>
      </div>
      <button class="btn-add" onclick="openModal()">+ Add Product</button>
    </header>

    <!-- Stats -->
    <div class="stats-row" id="statsRow">
      <div class="stat-card">
        <span class="stat-icon">📦</span>
        <div>
          <p>Total Products</p>
          <h2 id="statTotal">—</h2>
        </div>
      </div>
      <div class="stat-card">
        <span class="stat-icon">💰</span>
        <div>
          <p>Avg. Price</p>
          <h2 id="statAvg">—</h2>
        </div>
      </div>
      <div class="stat-card">
        <span class="stat-icon">📊</span>
        <div>
          <p>Total Stock</p>
          <h2 id="statStock">—</h2>
        </div>
      </div>
      <div class="stat-card">
        <span class="stat-icon">🏷️</span>
        <div>
          <p>Categories</p>
          <h2 id="statCats">—</h2>
        </div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="table-card">
      <div class="table-toolbar">
        <h3>Product List</h3>
        <input type="text" id="tableSearch" placeholder="Search..." class="search-sm">
      </div>
      <div class="table-wrap">
        <table id="productsTable">
          <thead>
            <tr>
              <th>Product</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr><td colspan="5" class="loading-row">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<!-- Product Modal -->
<div id="productModal" class="modal hidden">
  <div class="modal-overlay" onclick="closeModal()"></div>
  <div class="modal-box">
    <div class="modal-header">
      <h3 id="modalTitle">Add Product</h3>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
    <form id="productForm">
      <input type="hidden" id="productId">
      <div class="form-row">
        <div class="field">
          <label>Product Name *</label>
          <input type="text" id="fName" placeholder="e.g. Laptop Pro X1" required>
        </div>
        <div class="field">
          <label>Category</label>
          <input type="text" id="fCategory" placeholder="e.g. Electronics">
        </div>
      </div>
      <div class="field">
        <label>Description</label>
        <textarea id="fDesc" rows="3" placeholder="Product description..."></textarea>
      </div>
      <div class="form-row">
        <div class="field">
          <label>Price (Rp) *</label>
          <input type="number" id="fPrice" placeholder="0" min="0" required>
        </div>
        <div class="field">
          <label>Stock</label>
          <input type="number" id="fStock" placeholder="0" min="0">
        </div>
      </div>
      <div class="field">
        <label>Image URL</label>
        <input type="url" id="fImage" placeholder="https://...">
      </div>
      <div id="formAlert" class="alert hidden"></div>
      <div class="form-actions">
        <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn-save">
          <span>Save Product</span>
          <div class="spinner hidden"></div>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" class="modal hidden">
  <div class="modal-overlay"></div>
  <div class="modal-box confirm-box">
    <h3>Delete Product?</h3>
    <p>This action cannot be undone.</p>
    <div class="form-actions">
      <button class="btn-cancel" onclick="closeConfirm()">Cancel</button>
      <button class="btn-delete" id="confirmDeleteBtn">Delete</button>
    </div>
  </div>
</div>

<script src="/assets/js/admin.js"></script>
</body>
</html>
