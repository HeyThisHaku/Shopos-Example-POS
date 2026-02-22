const fmt = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);

let allProducts = [];
let debounceTimer;

async function fetchProducts(search = '', category = '') {
  const params = new URLSearchParams();
  if (search) params.set('search', search);
  if (category) params.set('category', category);

  document.getElementById('loadingState').classList.remove('hidden');
  document.getElementById('productsGrid').innerHTML = '';

  try {
    const res = await fetch('/api/products.php?' + params);
    const json = await res.json();
    allProducts = json.products || [];
    renderGrid(allProducts);
  } catch {
    document.getElementById('productsGrid').innerHTML = '<p style="color:#e05050;padding:40px">Failed to load products.</p>';
  } finally {
    document.getElementById('loadingState').classList.add('hidden');
  }
}

function stockInfo(stock) {
  if (stock === 0) return { label: 'Out of Stock', cls: 'out-stock' };
  if (stock <= 10) return { label: `Low Stock: ${stock}`, cls: 'low-stock' };
  return { label: `In Stock: ${stock}`, cls: 'in-stock' };
}

function renderGrid(products) {
  const grid = document.getElementById('productsGrid');
  if (!products.length) {
    grid.innerHTML = '<p style="color:#6a6a8a;padding:60px;text-align:center;grid-column:1/-1">No products found.</p>';
    return;
  }
  grid.innerHTML = products.map(p => {
    const s = stockInfo(p.stock);
    return `
      <div class="product-card" onclick="openModal(${p.id})">
        <img class="card-img" src="${p.image_url || 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=400'}" alt="${p.name}" onerror="this.src='https://placehold.co/400x200/111118/6a6a8a?text=No+Image'">
        <div class="card-body">
          <span class="card-badge">${p.category || 'Uncategorized'}</span>
          <div class="card-name">${p.name}</div>
          <div class="card-desc">${p.description || 'No description available.'}</div>
          <div class="card-footer">
            <span class="price-tag">${fmt(p.price)}</span>
            <span class="stock-badge ${s.cls}">${s.label}</span>
          </div>
        </div>
      </div>
    `;
  }).join('');
}

function openModal(id) {
  const p = allProducts.find(x => x.id == id);
  if (!p) return;
  const s = stockInfo(p.stock);
  document.getElementById('modalImg').src = p.image_url || 'https://placehold.co/600x300/111118/6a6a8a?text=No+Image';
  document.getElementById('modalCategory').textContent = p.category || 'Uncategorized';
  document.getElementById('modalName').textContent = p.name;
  document.getElementById('modalDesc').textContent = p.description || 'No description available.';
  document.getElementById('modalPrice').textContent = fmt(p.price);
  document.getElementById('modalStock').textContent = s.label;
  document.getElementById('modalStock').className = 'stock-badge ' + s.cls;
  document.getElementById('productModal').classList.remove('hidden');
}

function closeModal() {
  document.getElementById('productModal').classList.add('hidden');
}

async function logout() {
  await fetch('/api/auth.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({action:'logout'}) });
  window.location.href = '/login.php';
}

// Events
document.getElementById('searchInput').addEventListener('input', (e) => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchProducts(e.target.value, document.getElementById('categoryFilter').value);
  }, 300);
});

document.getElementById('categoryFilter').addEventListener('change', (e) => {
  fetchProducts(document.getElementById('searchInput').value, e.target.value);
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeModal();
});

fetchProducts();
