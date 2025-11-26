<?php
require_once __DIR__ . '/../database.php';

class SubCategory
{
    private $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function getAllByCategory(int $categorie_id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM sous_categories WHERE categorie_id = :id ORDER BY nom"
        );
        $stmt->execute([':id' => $categorie_id]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sous_categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(int $categorie_id, string $nom): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO sous_categories (categorie_id, nom) VALUES (:cat, :nom)"
        );
        return $stmt->execute([':cat' => $categorie_id, ':nom' => $nom]);
    }

    public function update(int $id, int $categorie_id, string $nom): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE sous_categories SET categorie_id = :cat, nom = :nom WHERE id = :id"
        );
        return $stmt->execute([':cat' => $categorie_id, ':nom' => $nom, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM sous_categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
