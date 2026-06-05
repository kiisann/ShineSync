<?php
class Order extends Model
{
    public function create(array $d): int
    {
        $this->db->execute(
            "INSERT INTO orders (user_id, order_number, total_amount, discount, grand_total, loyalty_points,
                                 shipping_name, shipping_phone, shipping_address, notes, status)
             VALUES (?,?,?,?,?,?,?,?,?,?,'pending')",
            [
                (int)$d['user_id'], $d['order_number'], (float)$d['total_amount'],
                (float)$d['discount'], (float)$d['grand_total'], (int)$d['loyalty_points'],
                $d['shipping_name'], $d['shipping_phone'], $d['shipping_address'],
                $d['notes'] ?? ''
            ]
        );
        return $this->db->lastInsertId();
    }

    public function addDetail(int $orderId, array $item): bool
    {
        return $this->db->execute(
            "INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal)
             VALUES (?,?,?,?,?,?)",
            [
                $orderId, (int)$item['product_id'], $item['name'],
                (int)$item['quantity'], (float)$item['price'],
                (float)($item['quantity'] * $item['price'])
            ]
        );
    }

    public function findByOrderNumber(string $orderNumber): ?array
    {
        return $this->db->queryOne(
            "SELECT o.*, u.name AS customer_name, u.email AS customer_email
             FROM orders o
             INNER JOIN users u ON o.user_id = u.id
             WHERE o.order_number = ?",
            [$orderNumber]
        );
    }

    public function findByIdWithDetails(int $id, int $userId = 0): ?array
    {
        $sql = "SELECT o.*, u.name AS customer_name
                FROM orders o
                INNER JOIN users u ON o.user_id = u.id
                WHERE o.id = ?";
        $params = [$id];
        if ($userId > 0) { $sql .= " AND o.user_id = ?"; $params[] = $userId; }

        $order = $this->db->queryOne($sql, $params);
        if (!$order) return null;

        $order['details'] = $this->db->query(
            "SELECT od.*, p.image FROM order_details od
             LEFT JOIN products p ON od.product_id = p.id
             WHERE od.order_id = ?",
            [$id]
        );
        $order['payment'] = $this->db->queryOne(
            "SELECT * FROM payments WHERE order_id = ?", [$id]
        );
        return $order;
    }

    public function getByUser(int $userId): array
    {
        return $this->db->query(
            "SELECT o.*, p.status AS payment_status,
                    COUNT(od.id) AS item_count
             FROM orders o
             LEFT JOIN payments p    ON o.id = p.order_id
             LEFT JOIN order_details od ON o.id = od.order_id
             WHERE o.user_id = ?
             GROUP BY o.id
             ORDER BY o.created_at DESC",
            [$userId]
        );
    }

    public function getAll(): array
    {
        return $this->db->query(
            "SELECT o.*, u.name AS customer_name, u.email,
                    p.status AS payment_status,
                    COUNT(od.id) AS item_count
             FROM orders o
             INNER JOIN users u ON o.user_id = u.id
             LEFT JOIN payments p       ON o.id = p.order_id
             LEFT JOIN order_details od ON o.id = od.order_id
             GROUP BY o.id
             ORDER BY o.created_at DESC"
        );
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->db->execute(
            "UPDATE orders SET status=?, updated_at=NOW() WHERE id=?",
            [$status, $id]
        );
    }

    public function getTotalOrders(): int
    {
        $row = $this->db->queryOne("SELECT COUNT(*) AS total FROM orders");
        return (int)($row['total'] ?? 0);
    }

    public function getTotalRevenue(): float
    {
        $row = $this->db->queryOne(
            "SELECT SUM(o.grand_total) AS total FROM orders o
             INNER JOIN payments p ON o.id = p.order_id
             WHERE p.status = 'verified'"
        );
        return (float)($row['total'] ?? 0);
    }

    // Grafik penjualan 12 bulan terakhir (total_amount diupdate oleh trigger tr_update_total_order)
    public function getMonthlySales(): array
    {
        return $this->db->query(
            "SELECT DATE_FORMAT(o.created_at,'%Y-%m') AS bulan,
                    DATE_FORMAT(o.created_at,'%b %Y')  AS label,
                    COUNT(o.id)                         AS jumlah_order,
                    SUM(o.total_amount)                 AS total_pendapatan
             FROM orders o
             INNER JOIN payments p ON o.id = p.order_id
             WHERE p.status = 'verified'
               AND o.created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY DATE_FORMAT(o.created_at,'%Y-%m'), DATE_FORMAT(o.created_at,'%b %Y')
             ORDER BY bulan ASC"
        );
    }

    public function getPendingPayments(): array
    {
        return $this->db->query(
            "SELECT o.*, u.name AS customer_name, p.amount, p.proof_image, p.method
             FROM orders o
             INNER JOIN users u    ON o.user_id  = u.id
             INNER JOIN payments p ON o.id       = p.order_id
             WHERE p.status = 'pending' AND p.proof_image IS NOT NULL
             ORDER BY p.created_at DESC"
        );
    }
}
