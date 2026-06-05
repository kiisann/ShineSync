<?php
class Review extends Model
{
    public function create(array $d): int
    {
        $this->db->execute(
            "INSERT INTO reviews (user_id, product_id, order_id, rating, comment) VALUES (?,?,?,?,?)",
            [(int)$d['user_id'], (int)$d['product_id'], (int)$d['order_id'], (int)$d['rating'], $d['comment']]
        );
        return $this->db->lastInsertId();
    }

    public function getByProduct(int $productId): array
    {
        return $this->db->query(
            "SELECT r.*, u.name AS reviewer_name, u.avatar
             FROM reviews r
             INNER JOIN users u ON r.user_id = u.id
             WHERE r.product_id = ? AND r.is_approved = 1
             ORDER BY r.created_at DESC",
            [$productId]
        );
    }

    public function getAll(): array
    {
        return $this->db->query(
            "SELECT r.*, u.name AS customer_name, p.name AS product_name
             FROM reviews r
             INNER JOIN users u    ON r.user_id    = u.id
             INNER JOIN products p ON r.product_id = p.id
             ORDER BY r.created_at DESC"
        );
    }

    public function toggle(int $id): bool
    {
        return $this->db->execute(
            "UPDATE reviews SET is_approved = NOT is_approved WHERE id = ?", [$id]
        );
    }

    public function hasReviewed(int $userId, int $productId, int $orderId): bool
    {
        $row = $this->db->queryOne(
            "SELECT id FROM reviews WHERE user_id=? AND product_id=? AND order_id=?",
            [$userId, $productId, $orderId]
        );
        return $row !== null;
    }

    public function getApprovedReviews(int $limit = 6): array
    {
        return $this->db->query(
            "SELECT r.*, u.name AS reviewer_name, u.avatar, p.name AS product_name
             FROM reviews r
             INNER JOIN users u    ON r.user_id    = u.id
             INNER JOIN products p ON r.product_id = p.id
             WHERE r.is_approved = 1
             ORDER BY r.created_at DESC LIMIT ?",
            [$limit]
        );
    }
}
