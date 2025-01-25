<?php

namespace Claude\UrlShortener\Database;

class UrlStorage
{
    private \SQLite3 $db;

    public function __construct(string $dbPath = __DIR__ . '/database.sqlite')
    {
        $this->db = new \SQLite3($dbPath);
        $this->initSchema();
    }

    private function initSchema(): void
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS urls (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT UNIQUE NOT NULL,
                url TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
    }

    public function create(string $url, string $code): bool
    {
        $stmt = $this->db->prepare('INSERT INTO urls (url, code) VALUES (:url, :code)');
        $stmt->bindValue(':url', $url, SQLITE3_TEXT);
        $stmt->bindValue(':code', $code, SQLITE3_TEXT);

        return $stmt->execute() !== false;
    }

    public function findByCode(string $code): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM urls WHERE code = :code LIMIT 1');
        $stmt->bindValue(':code', $code, SQLITE3_TEXT);

        $result = $stmt->execute();
        return $result ? $result->fetchArray(SQLITE3_ASSOC) : null;
    }

    private function generateCode(int $length = 6): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $code;
    }

    public function createShortUrl(string $url): ?string
    {
        $attempts = 5;
        while ($attempts--) {
            $code = $this->generateCode();
            if ($this->create($url, $code)) {
                return $code;
            }
        }
        return null;
    }
}
