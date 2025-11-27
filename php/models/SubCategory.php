<?php
class SubCategory
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByCategory(int $catId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sous_categories WHERE categorie_id=? ORDER BY nom");
        $stmt->execute([$catId]);
        return $stmt->fetchAll();
    }

    public function create(int $catId, string $nom): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO sous_categories (categorie_id, nom) VALUES (?, ?)");
        return $stmt->execute([$catId, $nom]);
    }

    public function update(int $id, string $nom): bool
    {
        $stmt = $this->pdo->prepare("UPDATE sous_categories SET nom=? WHERE id=?");
        return $stmt->execute([$nom, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM sous_categories WHERE id=?");
        return $stmt->execute([$id]);
    }
}
