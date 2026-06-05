<?php
class Wishlist extends Model
{
    public function getByUser(int $userId): array
    {
        return $this->db->query(
            "SELECT w.*, p.name, p.price, p.image, p.stock, p.slug,
                    c.name AS category_name,
                    COALESCE(AVG(r.rating),0) AS avg_rating
             FROM wishlist w
             INNER JOIN products p  ON w.product_id = p.id
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN reviews r    ON p.id = r.product_id AND r.is_approved=1
             WHERE w.user_id = ? AND p.is_active = 1
             GROUP BY w.id
             ORDER BY w.created_at DESC",
            [$userId]
        );
    }

    public function add(int $userId, int $productId): bool
    {
        if ($this->exists($userId, $productId)) return true;
        return $this->db->execute(
            "INSERT INTO wishlist (user_id, product_id) VALUES (?,?)",
            [$userId, $productId]
        );
    }

    public function remove(int $userId, int $productId): bool
    {
        return $this->db->execute(
            "DELETE FROM wishlist WHERE user_id=? AND product_id=?",
            [$userId, $productId]
        );
    }

    public function exists(int $userId, int $productId): bool
    {
        $row = $this->db->queryOne(
            "SELECT id FROM wishlist WHERE user_id=? AND product_id=?",
            [$userId, $productId]
        );
        return $row !== null;
    }

    public function getCount(int $userId): int
    {
        $row = $this->db->queryOne(
            "SELECT COUNT(*) AS total FROM wishlist WHERE user_id=?", [$userId]
        );
        return (int)($row['total'] ?? 0);
    }
}
