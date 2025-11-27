<?php
class Category
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        return $this->pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $nom, ?string $description): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nom, description) VALUES (?, ?)");
        return $stmt->execute([$nom, $description]);
    }

    public function update(int $id, string $nom, ?string $description): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET nom=?, description=? WHERE id=?");
        return $stmt->execute([$nom, $description, $id]);
    }

    public function delete(int $id): bool
    {
        $this->pdo->prepare("DELETE FROM sous_categories WHERE categorie_id=?")->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id=?");
        return $stmt->execute([$id]);
    }
}
