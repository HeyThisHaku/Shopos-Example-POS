<?php
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) {
    header('Location: /products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — ShopOS</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-brand">
    <div class="brand-logo">S</div>
    <h1>ShopOS</h1>
    <p>Join the platform today</p>
  </div>
  <div class="auth-card">
    <h2>Create Account</h2>
    <div id="alert" class="alert hidden"></div>
    <form id="registerForm">
      <div class="field">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="John Doe" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" placeholder="you@example.com" required>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" placeholder="Min. 6 characters" required minlength="6">
      </div>
      <div class="field">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Repeat password" required>
      </div>
      <button type="submit" class="btn-primary">
        <span>Create Account</span>
        <div class="spinner hidden"></div>
      </button>
    </form>
    <p class="auth-link">Already have an account? <a href="/login.php">Sign In</a></p>
  </div>
</div>
<script>
document.getElementById('registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target));
  if (data.password !== data.confirm_password) {
    return showAlert('Passwords do not match!', 'error');
  }
  const btn = e.target.querySelector('button');
  btn.disabled = true;
  btn.querySelector('span').style.opacity = '0';
  btn.querySelector('.spinner').classList.remove('hidden');

  try {
    const res = await fetch('/api/auth.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({action: 'register', name: data.name, email: data.email, password: data.password})
    });
    const json = await res.json();
    if (json.success) {
      showAlert('Account created! Redirecting...', 'success');
      setTimeout(() => window.location.href = '/login.php', 1500);
    } else {
      showAlert(json.error, 'error');
    }
  } catch {
    showAlert('Connection error.', 'error');
  } finally {
    btn.disabled = false;
    btn.querySelector('span').style.opacity = '1';
    btn.querySelector('.spinner').classList.add('hidden');
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
