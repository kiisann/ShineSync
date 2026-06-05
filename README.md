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





