<?php
// app/models/Report.php
// Implementasi: VIEW, SQL JOIN, SET OPERATIONS (UNION & UNION ALL)
class Report extends Model
{
    // ── VIEW: Laporan Penjualan ───────────────────────────────
    public function getLaporanPenjualan(string $dateFrom = '', string $dateTo = ''): array
    {
        $where = ''; $params = [];
        if ($dateFrom && $dateTo) {
            $where = "WHERE tanggal_pesanan >= ? AND tanggal_pesanan <= ?";
            $params = [$dateFrom, $dateTo];
        }
        return $this->db->query("SELECT * FROM view_laporan_penjualan {$where} ORDER BY id_order DESC", $params);
    }

    public function getLaporanSummary(): array
    {
        return $this->db->queryOne(
            "SELECT
                COUNT(*)          AS total_transaksi,
                SUM(grand_total)  AS total_pendapatan,
                AVG(grand_total)  AS rata_nilai_order,
                SUM(jumlah_item)  AS total_item_terjual
             FROM view_laporan_penjualan"
        ) ?? [];
    }

    // ── VIEW: Produk Terlaris ─────────────────────────────────
    public function getProdukTerlaris(int $limit = 10): array
    {
        return $this->db->query(
            "SELECT * FROM view_produk_terlaris LIMIT ?", [$limit]
        );
    }

    // ── VIEW: Customer Aktif ──────────────────────────────────
    public function getCustomerAktif(int $limit = 10): array
    {
        return $this->db->query(
            "SELECT * FROM view_customer_aktif LIMIT ?", [$limit]
        );
    }

    // ── SET OPERATIONS: UNION ─────────────────────────────────
    // Customer yang pernah ORDER atau pernah REVIEW (hapus duplikat)
    public function getCustomerAktifUnion(): array
    {
        return $this->db->query(
            "SELECT u.id, u.name, u.email, 'Pembeli' AS aktivitas
             FROM users u
             INNER JOIN orders o ON u.id = o.user_id

             UNION

             SELECT u.id, u.name, u.email, 'Reviewer' AS aktivitas
             FROM users u
             INNER JOIN reviews r ON u.id = r.user_id

             ORDER BY name ASC"
        );
    }

    // ── SET OPERATIONS: UNION ALL ─────────────────────────────
    // Produk Cincin + Kalung (pertahankan semua, termasuk duplikat)
    public function getInventarisUnionAll(): array
    {
        return $this->db->query(
            "SELECT p.id, p.name, p.price, p.stock, c.name AS kategori, 'Cincin' AS sumber
             FROM products p
             INNER JOIN categories c ON p.category_id = c.id
             WHERE c.slug = 'cincin' AND p.is_active = 1

             UNION ALL

             SELECT p.id, p.name, p.price, p.stock, c.name AS kategori, 'Kalung' AS sumber
             FROM products p
             INNER JOIN categories c ON p.category_id = c.id
             WHERE c.slug = 'kalung' AND p.is_active = 1

             ORDER BY sumber, name ASC"
        );
    }

    // ── Grafik per kategori (built-in functions) ──────────────
    public function getSalesByCategory(): array
    {
        return $this->db->query(
            "SELECT c.name AS kategori,
                    COUNT(DISTINCT o.id)  AS jumlah_order,
                    SUM(od.subtotal)      AS total_pendapatan,
                    SUM(od.quantity)      AS total_qty
             FROM categories c
             LEFT JOIN products p       ON c.id = p.category_id
             LEFT JOIN order_details od ON p.id = od.product_id
             LEFT JOIN orders o         ON od.order_id = o.id AND o.status != 'cancelled'
             GROUP BY c.id, c.name
             ORDER BY total_pendapatan DESC"
        );
    }
}
