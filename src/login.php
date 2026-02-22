<?php
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? '/admin.php' : '/products.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — ShopOS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-brand">
    <div class="brand-logo">S</div>
    <h1>ShopOS</h1>
    <p>Manage your store efficiently</p>
  </div>
  <div class="auth-card">
    <h2>Sign In</h2>
    <div id="alert" class="alert hidden"></div>
    <form id="loginForm">
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" placeholder="admin@shop.com" required>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-primary">
        <span>Sign In</span>
        <div class="spinner hidden"></div>
      </button>
    </form>
    <p class="auth-link">Don't have an account? <a href="/register.php">Register</a></p>
    <div class="demo-creds">
      <p>Demo credentials:</p>
      <span>Admin: admin@shop.com / password</span>
      <span>User: user@shop.com / password</span>
    </div>
  </div>
</div>
<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = e.target.querySelector('button');
  const spinner = btn.querySelector('.spinner');
  btn.querySelector('span').style.opacity = '0';
  spinner.classList.remove('hidden');
  btn.disabled = true;

  const data = Object.fromEntries(new FormData(e.target));
  try {
    const res = await fetch('/api/auth.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({action: 'login', ...data})
    });
    const json = await res.json();
    if (json.success) {
      window.location.href = json.redirect;
    } else {
      showAlert(json.error, 'error');
    }
  } catch {
    showAlert('Connection error. Try again.', 'error');
  } finally {
    btn.querySelector('span').style.opacity = '1';
    spinner.classList.add('hidden');
    btn.disabled = false;
  }
});

function showAlert(msg, type) {
  const el = document.getElementById('alert');
  el.textContent = msg;
  el.className = 'alert ' + type;
}
</script>
</body>
</html>
