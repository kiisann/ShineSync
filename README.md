# ShineSync — Sistem Informasi Penjualan Perhiasan Berbasis Web

Platform e-commerce perhiasan premium dengan PHP Native MVC + MySQL + Bootstrap 5.

## 🚀 Instalasi di Laragon

### 1. Clone / Copy Project
```bash
# Copy folder ke:
C:\laragon\www\ShineSyncNew\
```

### 2. Import Database
1. Buka **HeidiSQL** atau **phpMyAdmin** (http://localhost/phpmyadmin)
2. Import file: `database/shinesync_db.sql`
3. Database `shinesync_db` akan terbuat otomatis

### 3. Konfigurasi
Edit `config/database.php` jika diperlukan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // Password database Anda
define('DB_NAME', 'shinesync_db');
define('APP_URL',  'http://localhost/ShineSyncNew');
```

### 4. Akses Aplikasi
- **Toko:** http://localhost/ShineSyncNew
- **Admin:** http://localhost/ShineSyncNew/admin/login

### 5. Akun Login

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@shinesync.com | password |
| Customer | siti@example.com | password |
| Customer | budi@example.com | password |

---

## 📁 Struktur Folder

```
ShineSyncNew/
├── index.php              ← Front Controller & Router
├── .htaccess              ← URL Rewriting
├── database/
│   └── shinesync_db.sql   ← Import ke phpMyAdmin
├── config/
│   └── database.php       ← Konfigurasi DB
├── app/
│   ├── core/              ← Database, Controller, Model, Session
│   ├── controllers/       ← 13 Controller
│   ├── models/            ← 9 Model
│   └── views/             ← All Views
│       ├── layouts/       ← Header & Footer
│       ├── auth/          ← Login, Register
│       ├── customer/      ← Halaman Customer
│       └── admin/         ← Halaman Admin
├── public/
│   ├── css/               ← style.css, admin.css
│   └── js/                ← main.js
└── uploads/
    ├── products/          ← Foto produk (otomatis)
    └── payments/          ← Bukti pembayaran (otomatis)
```

---

## 🎓 Implementasi Materi PDD

### 1. DATABASE VIEW ✅
**File:** `database/shinesync_db.sql`
- `view_laporan_penjualan` — digunakan di Admin Reports
- `view_produk_terlaris` — digunakan di Dashboard Admin & Homepage
- `view_customer_aktif` — digunakan di Dashboard Admin

### 2. SQL JOIN ✅
**INNER JOIN** — Detail pesanan, laporan penjualan:
```sql
FROM orders o
INNER JOIN users u         ON o.user_id   = u.id
INNER JOIN order_details od ON o.id       = od.order_id
INNER JOIN products p      ON od.product_id = p.id
```
**LEFT JOIN** — Produk dengan review (produk tanpa review tetap tampil):
```sql
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
LEFT JOIN reviews r    ON p.id = r.product_id
```

### 3. SET OPERATIONS ✅
**File:** `app/models/Report.php`, **Tampil di:** `/admin/reports`

**UNION** (hapus duplikat):
```sql
SELECT id, name, email, 'Pembeli' FROM users INNER JOIN orders ...
UNION
SELECT id, name, email, 'Reviewer' FROM users INNER JOIN reviews ...
```
**UNION ALL** (pertahankan semua):
```sql
SELECT ... FROM products WHERE category = 'cincin'
UNION ALL
SELECT ... FROM products WHERE category = 'kalung'
```

### 4. TRANSACTION ✅
**File:** `app/controllers/CheckoutController.php`
```php
$this->db->beginTransaction();  // START TRANSACTION
  // 1. INSERT orders
  // 2. INSERT order_details (trigger auto kurangi stok)
  // 3. INSERT payments
  // 4. DELETE cart
$this->db->commit();            // COMMIT
// atau
$this->db->rollback();          // ROLLBACK jika error
```

### 5. FUNCTION ✅
**Built-in:** `SUM()`, `COUNT()`, `AVG()`, `DATE_FORMAT()` di dashboard/laporan

**Custom MySQL Function:**
```sql
HitungDiskonMember(total)    -- Diskon 10% jika total >= Rp 1.000.000
HitungPoinLoyalitas(total)   -- 1 poin per Rp 10.000
```

### 6. STORED PROCEDURE ✅
**File:** `app/controllers/ProductController.php`
```php
CALL sp_insert_produk(...)  // Tambah produk
CALL sp_select_produk(0)    // Ambil semua produk
CALL sp_select_produk(id)   // Ambil produk by ID
CALL sp_update_produk(...)  // Update produk
CALL sp_delete_produk(id)   // Hapus produk (soft delete)
```

### 7. TRIGGER ✅
**File:** `database/shinesync_db.sql`
```sql
CREATE TRIGGER tr_kurangi_stok
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    UPDATE products SET stock = stock - NEW.quantity WHERE id = NEW.product_id;
END
```
Trigger otomatis berjalan setiap kali INSERT ke `order_details`.

---

## 🎨 Teknologi

| Layer | Teknologi |
|-------|-----------|
| Frontend | HTML5, CSS3, Bootstrap 5, JavaScript |
| Backend | PHP Native (MVC) |
| Database | MySQL/MariaDB |
| Web Server | Apache (Laragon) |
| DB Access | mysqli |
| Auth | PHP Session + password_hash |
| Font | Poppins (Google Fonts) |

---

## 🌐 URL Map

| URL | Keterangan |
|-----|------------|
| `/` | Homepage |
| `/products` | Katalog produk |
| `/products/{slug}` | Detail produk |
| `/cart` | Keranjang |
| `/checkout` | Checkout (TRANSACTION) |
| `/orders` | Riwayat pesanan |
| `/wishlist` | Wishlist |
| `/profile` | Profil customer |
| `/auth/login` | Login customer |
| `/auth/register` | Registrasi |
| `/admin/login` | Login admin |
| `/admin/dashboard` | Dashboard admin |
| `/admin/products` | Kelola produk (SP) |
| `/admin/categories` | Kelola kategori |
| `/admin/orders` | Kelola pesanan |
| `/admin/payments` | Verifikasi pembayaran |
| `/admin/customers` | Data customer |
| `/admin/reviews` | Kelola review |
| `/admin/reports` | **Laporan PDD** (VIEW + JOIN + UNION) |
