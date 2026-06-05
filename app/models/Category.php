<?php
class Category extends Model
{
    public function getAll(): array
    {
        return $this->db->query(
            "SELECT c.*, COUNT(p.id) AS product_count
             FROM categories c
             LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
             WHERE c.is_active = 1
             GROUP BY c.id
             ORDER BY c.name ASC"
        );
    }

    public function getAllAdmin(): array
    {
        return $this->db->query(
            "SELECT c.*, COUNT(p.id) AS product_count
             FROM categories c
             LEFT JOIN products p ON c.id = p.category_id
             GROUP BY c.id
             ORDER BY c.name ASC"
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne("SELECT * FROM categories WHERE id=?", [$id]);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->db->queryOne("SELECT * FROM categories WHERE slug=?", [$slug]);
    }

    public function create(array $d): bool
    {
        return $this->db->execute(
            "INSERT INTO categories (name, slug, description, is_active) VALUES (?,?,?,?)",
            [$d['name'], $d['slug'], $d['description'] ?? '', $d['is_active'] ?? 1]
        );
    }

    public function update(int $id, array $d): bool
    {
        return $this->db->execute(
            "UPDATE categories SET name=?, slug=?, description=?, is_active=? WHERE id=?",
            [$d['name'], $d['slug'], $d['description'] ?? '', $d['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM categories WHERE id=?", [$id]);
    }

    public function slugExists(string $slug, int $excludeId = 0): bool
    {
        $row = $this->db->queryOne(
            "SELECT id FROM categories WHERE slug=? AND id!=?", [$slug, $excludeId]
        );
        return $row !== null;
    }
}