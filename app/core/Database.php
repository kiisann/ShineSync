<?php
// app/core/Database.php — Singleton wrapper untuk mysqli
class Database
{
    private static ?Database $instance = null;
    private mysqli $conn;

    private function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        if ($this->conn->connect_error) {
            die(json_encode([
                'error' => true,
                'message' => 'Koneksi database gagal: ' . $this->conn->connect_error
            ]));
        }

        $this->conn->set_charset(DB_CHARSET);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }

    // ── Query Helpers ──────────────────────────────────────────

    /** Eksekusi query dan kembalikan result (array of rows) */
    public function query(string $sql, array $params = []): array
    {
        if (empty($params)) {
            $result = $this->conn->query($sql);
            if ($result === false) {
                throw new RuntimeException('Query error: ' . $this->conn->error . ' | SQL: ' . $sql);
            }
            return $result->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $stmt = $this->prepare($sql, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /** Eksekusi query, kembalikan satu baris saja */
    public function queryOne(string $sql, array $params = []): ?array
    {
        $rows = $this->query($sql, $params);
        return $rows[0] ?? null;
    }

    /** Eksekusi INSERT/UPDATE/DELETE */
    public function execute(string $sql, array $params = []): bool
    {
        if (empty($params)) {
            $result = $this->conn->query($sql);
            return $result !== false;
        }
        $stmt = $this->prepare($sql, $params);
        return $stmt->execute();
    }

    /** Dapatkan last insert ID */
    public function lastInsertId(): int
    {
        return (int) $this->conn->insert_id;
    }

    /** Affected rows dari execute() terakhir */
    public function affectedRows(): int
    {
        return $this->conn->affected_rows;
    }

    // ── Stored Procedure ──────────────────────────────────────

    /** Panggil stored procedure, kembalikan array of rows */
    public function callProcedure(string $procedure, array $params = []): array
    {
        $placeholders = implode(', ', array_fill(0, count($params), '?'));
        $sql = "CALL {$procedure}(" . ($placeholders ?: '') . ")";

        if (empty($params)) {
            $result = $this->conn->query($sql);
            $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
            // Free all result sets
            while ($this->conn->next_result()) {
                if ($r = $this->conn->store_result()) $r->free();
            }
            return $rows;
        }

        $stmt = $this->prepare($sql, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        while ($this->conn->next_result()) {
            if ($r = $this->conn->store_result()) $r->free();
        }
        return $rows;
    }

    // ── Transaction ───────────────────────────────────────────

    public function beginTransaction(): void
    {
        $this->conn->autocommit(false);
        $this->conn->begin_transaction();
    }

    public function commit(): void
    {
        $this->conn->commit();
        $this->conn->autocommit(true);
    }

    public function rollback(): void
    {
        $this->conn->rollback();
        $this->conn->autocommit(true);
    }

    // ── Escape ────────────────────────────────────────────────

    public function escape(string $value): string
    {
        return $this->conn->real_escape_string($value);
    }

    // ── Private Helpers ───────────────────────────────────────

    private function prepare(string $sql, array $params): mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new RuntimeException('Prepare error: ' . $this->conn->error . ' | SQL: ' . $sql);
        }

        $types = '';
        foreach ($params as $p) {
            if (is_int($p))   $types .= 'i';
            elseif (is_float($p)) $types .= 'd';
            else $types .= 's';
        }
        $stmt->bind_param($types, ...$params);
        return $stmt;
    }
}
