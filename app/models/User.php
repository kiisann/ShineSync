<?php
class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        return $this->db->queryOne(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$email]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT id, name, email, phone, address, avatar, role, is_active, created_at FROM users WHERE id = ?",
            [$id]
        );
    }

    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['email'], $data['password'], $data['phone'] ?? '', $data['address'] ?? '', $data['role'] ?? 'customer']
        );
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE users SET name=?, phone=?, address=?, updated_at=NOW() WHERE id=?",
            [$data['name'], $data['phone'] ?? '', $data['address'] ?? '', $id]
        );
    }

    public function updatePassword(int $id, string $hash): bool
    {
        return $this->db->execute(
            "UPDATE users SET password=?, updated_at=NOW() WHERE id=?",
            [$hash, $id]
        );
    }

    public function updateAvatar(int $id, string $avatar): bool
    {
        return $this->db->execute(
            "UPDATE users SET avatar=?, updated_at=NOW() WHERE id=?",
            [$avatar, $id]
        );
    }

    public function emailExists(string $email, int $excludeId = 0): bool
    {
        $row = $this->db->queryOne(
            "SELECT id FROM users WHERE email=? AND id != ?",
            [$email, $excludeId]
        );
        return $row !== null;
    }

    // Digunakan admin
    public function getAllCustomers(): array
    {
        return $this->db->query(
            "SELECT u.*, COUNT(DISTINCT o.id) AS total_orders, COALESCE(SUM(o.grand_total),0) AS total_spent
             FROM users u
             LEFT JOIN orders o ON u.id = o.user_id AND o.status != 'cancelled'
             WHERE u.role = 'customer'
             GROUP BY u.id
             ORDER BY u.created_at DESC"
        );
    }

    public function getTotalCustomers(): int
    {
        $row = $this->db->queryOne("SELECT COUNT(*) AS total FROM users WHERE role='customer'");
        return (int)($row['total'] ?? 0);
    }
}
