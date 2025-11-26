<?php
require_once __DIR__ . '/../database.php';

class Category
{
    private $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY nom");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $cat = $stmt->fetch();
        return $cat ?: null;
    }

    public function create(string $nom, ?string $description = null): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO categories (nom, description) VALUES (:nom, :description)"
        );
        return $stmt->execute([':nom' => $nom, ':description' => $description]);
    }

    public function update(int $id, string $nom, ?string $description = null): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE categories SET nom = :nom, description = :description WHERE id = :id"
        );
        return $stmt->execute([':nom' => $nom, ':description' => $description, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
