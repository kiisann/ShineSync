<?php
// app/models/Cart.php
class Cart extends Model
{
    public function getOrCreateCart(int $userId): int
    {
        $cart = $this->db->queryOne(
            "SELECT id FROM carts WHERE user_id = ?", [$userId]
        );
        if ($cart) return (int)$cart['id'];

        $this->db->execute("INSERT INTO carts (user_id) VALUES (?)", [$userId]);
        return $this->db->lastInsertId();
    }

    public function getCartItems(int $userId): array
    {
        return $this->db->query(
            "SELECT cd.id, cd.product_id, cd.quantity, cd.price,
                    p.name, p.image, p.stock, p.is_active,
                    (cd.quantity * cd.price) AS subtotal
             FROM carts c
             INNER JOIN cart_details cd ON c.id = cd.cart_id
             INNER JOIN products p      ON cd.product_id = p.id
             WHERE c.user_id = ?
             ORDER BY cd.id ASC",
            [$userId]
        );
    }

    public function getCartTotal(int $userId): float
    {
        $row = $this->db->queryOne(
            "SELECT SUM(cd.quantity * cd.price) AS total
             FROM carts c
             INNER JOIN cart_details cd ON c.id = cd.cart_id
             WHERE c.user_id = ?",
            [$userId]
        );
        return (float)($row['total'] ?? 0);
    }

    public function getCartCount(int $userId): int
    {
        $row = $this->db->queryOne(
            "SELECT SUM(cd.quantity) AS total
             FROM carts c
             INNER JOIN cart_details cd ON c.id = cd.cart_id
             WHERE c.user_id = ?",
            [$userId]
        );
        return (int)($row['total'] ?? 0);
    }

    public function addItem(int $userId, int $productId, int $qty, float $price): bool
    {
        $cartId = $this->getOrCreateCart($userId);

        // Cek apakah sudah ada
        $existing = $this->db->queryOne(
            "SELECT id, quantity FROM cart_details WHERE cart_id=? AND product_id=?",
            [$cartId, $productId]
        );

        if ($existing) {
            return $this->db->execute(
                "UPDATE cart_details SET quantity = quantity + ?, price = ? WHERE id = ?",
                [$qty, $price, (int)$existing['id']]
            );
        }

        return $this->db->execute(
            "INSERT INTO cart_details (cart_id, product_id, quantity, price) VALUES (?,?,?,?)",
            [$cartId, $productId, $qty, $price]
        );
    }

    public function updateItem(int $detailId, int $userId, int $qty): bool
    {
        return $this->db->execute(
            "UPDATE cart_details cd
             INNER JOIN carts c ON cd.cart_id = c.id
             SET cd.quantity = ?
             WHERE cd.id = ? AND c.user_id = ?",
            [$qty, $detailId, $userId]
        );
    }

    public function getItemForUser(int $detailId, int $userId): ?array
    {
        return $this->db->queryOne(
            "SELECT cd.id, cd.product_id, cd.quantity, p.stock, p.name, p.is_active
             FROM cart_details cd
             INNER JOIN carts c    ON cd.cart_id = c.id
             INNER JOIN products p ON cd.product_id = p.id
             WHERE cd.id = ? AND c.user_id = ?",
            [$detailId, $userId]
        );
    }

    public function removeItem(int $detailId, int $userId): bool
    {
        return $this->db->execute(
            "DELETE cd FROM cart_details cd
             INNER JOIN carts c ON cd.cart_id = c.id
             WHERE cd.id = ? AND c.user_id = ?",
            [$detailId, $userId]
        );
    }

    public function clearCart(int $userId): bool
    {
        return $this->db->execute(
            "DELETE cd FROM cart_details cd
             INNER JOIN carts c ON cd.cart_id = c.id
             WHERE c.user_id = ?",
            [$userId]
        );
    }
}
