# 💍✨ ShineSync (Proyek UAP)

Proyek ini merupakan sistem e-commerce perhiasan berbasis web yang dibangun menggunakan PHP dan MySQL dengan arsitektur MVC (Model-View-Controller). Tujuannya sebagai platform jual beli perhiasan yang dilengkapi fitur manajemen produk, pemesanan, pembayaran, ulasan, dan laporan penjualan, dengan memanfaatkan stored procedure, function, trigger, view database, dan sistem loyalitas poin untuk pelanggan.

<img width="1898" height="946" alt="image" src="https://github.com/user-attachments/assets/c8ddef2b-aa61-4a19-949a-772cf059a782" />

## 📌 Detail Konsep
Stored procedure digunakan sebagai lapisan utama operasi CRUD pada produk, sesuai kebutuhan PDD (Procedure-Driven Design). Procedure disimpan di database sehingga menjamin konsistensi, efisiensi, dan keamanan eksekusi di sistem multi-user.
Procedure yang diimplementasikan pada Product.php
**sp_select_produk(p_id) — Mengambil semua produk atau berdasarkan ID**
```sql
public function getAllViaSP(): array
{
    return $this->db->callProcedure('sp_select_produk', [0]);
}

public function findByIdViaSP(int $id): ?array
{
    $rows = $this->db->callProcedure('sp_select_produk', [$id]);
    return $rows[0] ?? null;
}
```
**sp_insert_produk(...) — Menambahkan produk baru**
```sql
public function createViaSP(array $d): int
{
    $rows = $this->db->callProcedure('sp_insert_produk', [
        (int)$d['category_id'], $d['name'], $d['slug'],
        $d['description'] ?? '', (float)$d['price'], (int)$d['stock'],
        (float)($d['weight'] ?? 0), $d['material'] ?? '', $d['image'] ?? '',
        (int)($d['is_featured'] ?? 0)
    ]);
    return (int)($rows[0]['new_id'] ?? 0);
}
```

**sp_update_produk(...) — Memperbarui data produk**
**sp_delete_produk(p_id) — Menghapus produk berdasarkan ID**

## 🔍 Function Database
IsFacilityAvailable / logika ketersediaan stok — Function database digunakan untuk mengecek kondisi tertentu secara efisien di lapisan database.

## ⚡ View Database
**Digunakan untuk query kompleks yang sering dipanggil, misalnya laporan produk terlaris:**
```sql
public function getBestsellers(int $limit = 6): array
{
    return $this->db->query(
        "SELECT * FROM view_produk_terlaris LIMIT ?", [$limit]
    );
}
```
View view_produk_terlaris dibangun dari agregasi data order_items dan orders yang sudah diconfirm, sehingga halaman beranda cukup memanggil satu view tanpa query berat.

## 🛒 Fitur Checkout dengan Diskon Otomatis
Sistem checkout menghitung diskon 10% secara otomatis bagi pelanggan yang berbelanja di atas Rp 1.000.000, sekaligus menampilkan estimasi poin loyalitas yang diperoleh:
```sql
// app/views/customer/checkout.php
$discount = $total >= 1000000 ? $total * 0.1 : 0;
$grand    = $total - $discount;
$poin     = floor($grand / 10000);
```
Tampilan checkout mencakup:

```sql
Form informasi pengiriman (nama, telepon, alamat lengkap)
Pilihan metode pembayaran: Transfer Bank, QRIS, COD
Info rekening otomatis tampil saat Transfer Bank dipilih
Ringkasan pesanan beserta estimasi poin loyalitas yang didapat
```
## 🎖️ Sistem Loyalitas Poin
Setiap transaksi menghasilkan poin berdasarkan grand total pembelian (Rp 10.000 = 1 poin). Poin ditampilkan secara real-time di halaman checkout sebagai insentif bagi pelanggan.

## 🚦 Routing & Arsitektur MVC
Semua request masuk melalui index.php (Front Controller), kemudian di-route ke controller yang sesuai menggunakan PHP 8 match expression:
### Route Customer
| URL | Controller | Keterangan |
|---|---|---|
| `/` | `HomeController::index` | Halaman beranda |
| `/products` | `ProductController::index` | Daftar produk |
| `/products/{slug}` | `ProductController::detail` | Detail produk |
| `/products/search` | `ProductController::search` | Pencarian produk |
| `/cart` | `CartController::index` | Halaman keranjang |
| `/cart/add` | `CartController::add` | Tambah ke keranjang |
| `/checkout` | `CheckoutController::index` | Halaman checkout |
| `/checkout/process` | `CheckoutController::process` | Proses pesanan |
| `/orders` | `OrderController::history` | Riwayat pesanan |
| `/wishlist` | `WishlistController::index` | Wishlist produk |
| `/reviews/store` | `ReviewController::store` | Kirim ulasan |
| `/profile` | `ProfileController::index` | Profil pengguna |

### Route Admin
| URL | Controller | Keterangan |
|---|---|---|
| `/admin` | `DashboardController::index` | Dashboard admin |
| `/admin/products` | `ProductController::adminIndex` | Kelola produk |
| `/admin/categories` | `CategoryController::adminIndex` | Kelola kategori |
| `/admin/orders` | `OrderController::adminIndex` | Semua pesanan |
| `/admin/orders/aktif` | `OrderController::adminAktif` | Order aktif |
| `/admin/orders/arsip` | `OrderController::adminArsip` | Order arsip |
| `/admin/payments` | `PaymentController::adminIndex` | Verifikasi pembayaran |
| `/admin/customers` | `DashboardController::customers` | Data pelanggan |
| `/admin/reviews` | `ReviewController::adminIndex` | Moderasi ulasan |
| `/admin/reports` | `ReportController::index` | Laporan penjualan |

---

## 🗄️ Model & Relasi Database

| Model | Tabel | Keterangan |
|---|---|---|
| `User` | `users` | Data pelanggan & admin |
| `Product` | `products` | Produk perhiasan |
| `Category` | `categories` | Kategori produk |
| `Cart` | `cart_items` | Keranjang belanja |
| `Order` | `orders`, `order_items` | Pesanan & detail item |
| `Payment` | `payments` | Bukti pembayaran |
| `Review` | `reviews` | Ulasan produk |
| `Wishlist` | `wishlists` | Produk favorit |
| `Report` | (view/query) | Laporan penjualan |

Relasi produk dengan ulasan menggunakan **LEFT JOIN** untuk menjaga produk tetap tampil meski belum ada ulasan:

```php
public function getActiveWithRating(int $categoryId = 0, string $search = '', string $sort = 'newest'): array
{
    // ...
    return $this->db->query(
        "SELECT p.*, c.name AS category_name,
                COALESCE(AVG(r.rating), 0) AS avg_rating,
                COUNT(DISTINCT r.id)        AS review_count
         FROM products p
         LEFT JOIN categories c ON p.category_id = c.id
         LEFT JOIN reviews r    ON p.id = r.product_id AND r.is_approved = 1
         {$whereStr}
         GROUP BY p.id
         ORDER BY {$orderBy}",
        $params
    );
}
```

---

## 🔐 Autentikasi & Otorisasi

Sistem menggunakan `Session` class kustom dengan dua level akses:

- **Customer** — Akses ke halaman belanja, riwayat pesanan, profil, wishlist, dan ulasan
- **Admin** — Akses penuh ke panel admin (produk, kategori, pesanan, pembayaran, ulasan, laporan)

Route admin dilindungi dengan `Session::requireAdmin()` yang otomatis redirect ke halaman login jika belum autentikasi.

---

## 💳 Alur Pembayaran

1. Pelanggan checkout → memilih metode bayar (Transfer / QRIS / COD)
2. Pesanan masuk dengan status `pending`
3. Pelanggan upload bukti pembayaran di `/orders/payment/{order_id}`
4. Admin verifikasi di `/admin/payments` → status berubah ke `confirmed`
5. Admin proses pengiriman → status berubah ke `shipped` → `delivered`

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | PHP 8 (Pure MVC) |
| Database | MySQL 8 |
| Frontend | HTML5, CSS3, Bootstrap 5, Font Awesome |
| Routing | Custom Front Controller (`index.php`) |
| Session | Custom `Session` class |
| DB Layer | Custom `Database` class (PDO-style) |

---

## ⚙️ Cara Menjalankan

1. Clone atau extract project ke direktori web server (XAMPP/Laragon)
2. Import database:
   ```
   database/shinesync_db.sql
   ```
3. Konfigurasi koneksi di `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'shinesync');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('APP_URL',  'http://localhost/ShineSyncNew');
   ```
4. Akses aplikasi di browser:
   - **Customer:** `http://localhost/ShineSyncNew`
   - **Admin:** `http://localhost/ShineSyncNew/admin`

---

## 👤 Default Akun

| Role | Email | Password |
|---|---|---|
| Admin | admin@shinesync.com | admin123 |
| Customer | (daftar via `/auth/register`) | — |
