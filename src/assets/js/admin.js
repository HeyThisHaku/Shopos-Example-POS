const fmt = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);

let allProducts = [];
let deleteTarget = null;

async function loadProducts() {
  try {
    const res = await fetch('/api/products.php');
    const json = await res.json();
    allProducts = json.products || [];
    renderTable(allProducts);
    updateStats(allProducts);
  } catch {
    document.getElementById('tableBody').innerHTML = '<tr><td colspan="5" class="loading-row" style="color:#e05050">Failed to load products.</td></tr>';
  }
}

function updateStats(products) {
  document.getElementById('statTotal').textContent = products.length;
  const avgPrice = products.length ? products.reduce((a, p) => a + parseFloat(p.price), 0) / products.length : 0;
  document.getElementById('statAvg').textContent = fmt(avgPrice);
  const totalStock = products.reduce((a, p) => a + parseInt(p.stock), 0);
  document.getElementById('statStock').textContent = totalStock.toLocaleString('id-ID');
  const cats = new Set(products.map(p => p.category).filter(Boolean));
  document.getElementById('statCats').textContent = cats.size;
}

function renderTable(products) {
  const tbody = document.getElementById('tableBody');
  if (!products.length) {
    tbody.innerHTML = '<tr><td colspan="5" class="loading-row">No products found.</td></tr>';
    return;
  }
  tbody.innerHTML = products.map(p => `
    <tr>
      <td>
        <div class="td-product">
          <img class="td-img" src="${p.image_url || 'https://placehold.co/44x44/111118/6a6a8a?text=P'}" alt="${p.name}" onerror="this.src='https://placehold.co/44x44/111118/6a6a8a?text=P'">
          <div>
            <div class="td-name">${p.name}</div>
            <div class="td-id">#${p.id}</div>
          </div>
        </div>
      </td>
      <td><span class="cat-badge">${p.category || '—'}</span></td>
      <td><span class="price-mono">${fmt(p.price)}</span></td>
      <td>${p.stock}</td>
      <td>
        <div class="action-btns">
          <button class="btn-edit" onclick="editProduct(${p.id})">Edit</button>
          <button class="btn-del" onclick="confirmDelete(${p.id})">Delete</button>
        </div>
      </td>
    </tr>
  `).join('');
}

function openModal(product = null) {
  const form = document.getElementById('productForm');
  form.reset();
  document.getElementById('formAlert').className = 'alert hidden';
  document.getElementById('productId').value = '';

  if (product) {
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = product.id;
    document.getElementById('fName').value = product.name;
    document.getElementById('fDesc').value = product.description || '';
    document.getElementById('fPrice').value = product.price;
    document.getElementById('fStock').value = product.stock;
    document.getElementById('fCategory').value = product.category || '';
    document.getElementById('fImage').value = product.image_url || '';
  } else {
    document.getElementById('modalTitle').textContent = 'Add Product';
  }

  document.getElementById('productModal').classList.remove('hidden');
}

function editProduct(id) {
  const p = allProducts.find(x => x.id == id);
  if (p) openModal(p);
}

function closeModal() {
  document.getElementById('productModal').classList.add('hidden');
}

function confirmDelete(id) {
  deleteTarget = id;
  document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirm() {
  deleteTarget = null;
  document.getElementById('confirmModal').classList.add('hidden');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
  if (!deleteTarget) return;
  try {
    const res = await fetch('/api/products.php', {
      method: 'DELETE',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id: deleteTarget})
    });
    const json = await res.json();
    if (json.success) {
      closeConfirm();
      loadProducts();
    }
  } catch {
    closeConfirm();
  }
});

document.getElementById('productForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = document.getElementById('productId').value;
  const alertEl = document.getElementById('formAlert');
  const btn = e.target.querySelector('.btn-save');
  btn.disabled = true;
  btn.querySelector('span').style.opacity = '0';
  btn.querySelector('.spinner').classList.remove('hidden');

  const payload = {
    name: document.getElementById('fName').value.trim(),
    description: document.getElementById('fDesc').value.trim(),
    price: document.getElementById('fPrice').value,
    stock: document.getElementById('fStock').value || 0,
    category: document.getElementById('fCategory').value.trim(),
    image_url: document.getElementById('fImage').value.trim(),
  };

  if (id) payload.id = id;

  try {
    const res = await fetch('/api/products.php', {
      method: id ? 'PUT' : 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    if (json.success) {
      closeModal();
      loadProducts();
    } else {
      alertEl.textContent = json.error || 'Something went wrong';
      alertEl.className = 'alert error';
    }
  } catch {
    alertEl.textContent = 'Connection error.';
    alertEl.className = 'alert error';
  } finally {
    btn.disabled = false;
    btn.querySelector('span').style.opacity = '1';
    btn.querySelector('.spinner').classList.add('hidden');
  }
});

// Table search
document.getElementById('tableSearch').addEventListener('input', (e) => {
  const q = e.target.value.toLowerCase();
  const filtered = allProducts.filter(p =>
    p.name.toLowerCase().includes(q) ||
    (p.category || '').toLowerCase().includes(q)
  );
  renderTable(filtered);
});

async function logout() {
  await fetch('/api/auth.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({action:'logout'}) });
  window.location.href = '/login.php';
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeModal();
    closeConfirm();
  }
});

loadProducts();
