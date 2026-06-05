<?php
class Payment extends Model
{
    public function create(array $d): int
    {
        $this->db->execute(
            "INSERT INTO payments (order_id, amount, method, bank_name, account_number) VALUES (?,?,?,?,?)",
            [(int)$d['order_id'], (float)$d['amount'], $d['method'] ?? 'transfer', $d['bank_name'] ?? '', $d['account_number'] ?? '']
        );
        return $this->db->lastInsertId();
    }

    public function findByOrderId(int $orderId): ?array
    {
        return $this->db->queryOne("SELECT * FROM payments WHERE order_id=?", [$orderId]);
    }

    public function uploadProof(int $orderId, string $filename): bool
    {
        return $this->db->execute(
            "UPDATE payments SET proof_image=? WHERE order_id=?",
            [$filename, $orderId]
        );
    }

    public function verify(int $paymentId, int $adminId): bool
    {
        return $this->db->execute(
            "UPDATE payments SET status='verified', verified_at=NOW(), verified_by=? WHERE id=?",
            [$adminId, $paymentId]
        );
    }

    public function reject(int $paymentId, int $adminId, string $notes = ''): bool
    {
        return $this->db->execute(
            "UPDATE payments SET status='rejected', verified_at=NOW(), verified_by=?, notes=? WHERE id=?",
            [$adminId, $notes, $paymentId]
        );
    }

    public function getAllPending(): array
    {
        return $this->db->query(
            "SELECT p.*, o.order_number, o.grand_total, u.name AS customer_name, u.email
             FROM payments p
             INNER JOIN orders o ON p.order_id = o.id
             INNER JOIN users u  ON o.user_id = u.id
             WHERE p.status = 'pending'
             ORDER BY p.created_at DESC"
        );
    }

    public function getAll(): array
    {
        return $this->db->query(
            "SELECT p.*, o.order_number, o.grand_total, u.name AS customer_name
             FROM payments p
             INNER JOIN orders o ON p.order_id = o.id
             INNER JOIN users u  ON o.user_id = u.id
             ORDER BY p.created_at DESC"
        );
    }

    public function getTotalVerified(): float
    {
        $row = $this->db->queryOne("SELECT SUM(amount) AS total FROM payments WHERE status='verified'");
        return (float)($row['total'] ?? 0);
    }

    public function getPendingCount(): int
    {
        $row = $this->db->queryOne(
            "SELECT COUNT(*) AS total FROM payments WHERE status='pending' AND proof_image IS NOT NULL"
        );
        return (int)($row['total'] ?? 0);
    }
}
