<?php
// CRUD menggunakan Stored Procedure (PDD requirement)
class Product extends Model
{
    // Stored Procedures 

    /** sp_select_produk: 0 = semua, >0 = by ID */
    public function getAllViaSP(): array
    {
        return $this->db->callProcedure('sp_select_produk', [0]);
    }

    public function findByIdViaSP(int $id): ?array
    {
        $rows = $this->db->callProcedure('sp_select_produk', [$id]);
        return $rows[0] ?? null;
    }

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

    public function updateViaSP(int $id, array $d): bool
    {
        $this->db->callProcedure('sp_update_produk', [
            $id, (int)$d['category_id'], $d['name'], $d['slug'],
            $d['description'] ?? '', (float)$d['price'], (int)$d['stock'],
            (float)($d['weight'] ?? 0), $d['material'] ?? '', $d['image'] ?? '',
            (int)($d['is_featured'] ?? 0)
        ]);
        return true;
    }

    public function deleteViaSP(int $id): bool
    {
        $this->db->callProcedure('sp_delete_produk', [$id]);
        return true;
    }

    // Customer Queries (LEFT JOIN untuk produk + review)

    // Semua produk aktif dengan rating rata-rata (LEFT JOIN)
    public function getActiveWithRating(int $categoryId = 0, string $search = '', string $sort = 'newest'): array
    {
        $sortMap = [
            'newest'    => 'p.created_at DESC',
            'oldest'    => 'p.created_at ASC',
            'price_asc' => 'p.price ASC',
            'price_desc'=> 'p.price DESC',
            'rating'    => 'avg_rating DESC',
        ];
        $orderBy = $sortMap[$sort] ?? 'p.created_at DESC';

        $where  = ['p.is_active = 1'];
        $params = [];

        if ($categoryId > 0) {
            $where[] = 'p.category_id = ?';
            $params[] = $categoryId;
        }
        if ($search !== '') {
            $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $whereStr = 'WHERE ' . implode(' AND ', $where);

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

    /** Detail produk by slug dengan review (LEFT JOIN) */
    public function findBySlugWithReviews(string $slug): ?array
    {
        $product = $this->db->queryOne(
            "SELECT p.*, c.name AS category_name,
                    COALESCE(AVG(r.rating), 0) AS avg_rating,
                    COUNT(DISTINCT r.id)        AS review_count
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN reviews r    ON p.id = r.product_id AND r.is_approved = 1
             WHERE p.slug = ? AND p.is_active = 1",
            [$slug]
        );

        if ($product) {
            $product['reviews'] = $this->db->query(
                "SELECT r.*, u.name AS reviewer_name, u.avatar
                 FROM reviews r
                 INNER JOIN users u ON r.user_id = u.id
                 WHERE r.product_id = ? AND r.is_approved = 1
                 ORDER BY r.created_at DESC",
                [(int)$product['id']]
            );
        }
        return $product;
    }

    public function getFeatured(int $limit = 8): array
    {
        return $this->db->query(
            "SELECT p.*, c.name AS category_name,
                    COALESCE(AVG(r.rating),0) AS avg_rating
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN reviews r    ON p.id = r.product_id AND r.is_approved=1
             WHERE p.is_featured = 1 AND p.is_active = 1
             GROUP BY p.id ORDER BY p.created_at DESC LIMIT ?",
            [$limit]
        );
    }

    public function getBestsellers(int $limit = 6): array
    {
        return $this->db->query(
            "SELECT * FROM view_produk_terlaris LIMIT ?", [$limit]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT p.*, c.name AS category_name FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? AND p.is_active = 1",
            [$id]
        );
    }

    public function slugExists(string $slug, int $excludeId = 0): bool
    {
        $row = $this->db->queryOne(
            "SELECT id FROM products WHERE slug=? AND id!=?", [$slug, $excludeId]
        );
        return $row !== null;
    }

    public function getTotalActive(): int
    {
        $row = $this->db->queryOne("SELECT COUNT(*) AS total FROM products WHERE is_active=1");
        return (int)($row['total'] ?? 0);
    }

    public function decreaseStock(int $productId, int $qty): bool
    {
        return $this->db->execute(
            "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?",
            [$qty, $productId, $qty]
        );
    }
}