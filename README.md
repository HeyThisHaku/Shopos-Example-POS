# ShopOS — PHP + MySQL + Nginx Stack

Aplikasi web toko sederhana dengan fitur Login, Register, Product Listing, dan Admin Dashboard CRUD.

## 🚀 Cara Menjalankan

### Prerequisites
- Docker & Docker Compose terinstall

### Start Project

```bash
# Clone / masuk ke direktori project
cd shopos

# Build & jalankan semua service
docker compose up --build -d

# Cek status container
docker compose ps
```

Akses aplikasi di browser:
- **App**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

---

## 🔑 Akun Demo

| Role  | Email            | Password  |
|-------|------------------|-----------|
| Admin | admin@shop.com   | password  |
| User  | user@shop.com    | password  |

---

## 📁 Struktur Project

```
shopos/
├── docker-compose.yml
├── Dockerfile              # PHP-FPM image
├── php.ini                 # PHP config
├── nginx/
│   └── default.conf        # Nginx config
├── mysql/
│   └── init.sql            # DB schema + seed data
└── src/                    # PHP source files
    ├── index.php           # Redirect entry point
    ├── login.php           # Halaman login
    ├── register.php        # Halaman registrasi
    ├── products.php        # Katalog produk (user)
    ├── admin.php           # Dashboard admin (CRUD)
    ├── includes/
    │   ├── auth.php        # Session & auth helpers
    │   └── db.php          # PDO connection
    ├── api/
    │   ├── auth.php        # Login/Register/Logout API
    │   └── products.php    # Products CRUD API
    └── assets/
        ├── css/
        │   ├── auth.css
        │   ├── main.css
        │   └── admin.css
        └── js/
            ├── products.js
            └── admin.js
```

---

## 🛠️ Fitur

### Halaman Publik (setelah login)
- **Login** — validasi email & password, session management
- **Register** — buat akun baru dengan validasi
- **Products** — grid produk, search real-time, filter kategori, modal detail

### Admin Dashboard
- **Stats Cards** — total produk, rata-rata harga, total stok, jumlah kategori
- **Product Table** — list semua produk dengan search
- **Add Product** — form tambah produk baru
- **Edit Product** — form edit produk existing
- **Delete Product** — konfirmasi hapus produk

---

## 🐳 Services

| Service    | Port  | Deskripsi          |
|------------|-------|--------------------|
| nginx      | 8080  | Web server         |
| php        | 9000  | PHP-FPM (internal) |
| mysql      | 3306  | Database           |
| phpmyadmin | 8081  | DB management UI   |

---

## 🔧 Commands Berguna

```bash
# Stop semua container
docker compose down

# Stop + hapus volume (reset database)
docker compose down -v

# Lihat logs
docker compose logs -f

# Masuk ke MySQL shell
docker exec -it shopos_mysql mysql -u shopuser -pshoppass shopdb

# Restart single service
docker compose restart php
```
