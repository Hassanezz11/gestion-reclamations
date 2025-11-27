<?php
class Reclamation
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        return $this->pdo->query("
            SELECT r.*, u.nom_complet AS client
            FROM reclamations r
            LEFT JOIN utilisateurs u ON u.id = r.utilisateur_id
            ORDER BY r.date_creation DESC
        ")->fetchAll();
    }

    public function search(string $q): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.nom_complet AS client
            FROM reclamations r
            JOIN utilisateurs u ON u.id = r.utilisateur_id
            WHERE u.nom_complet LIKE :q OR r.objet LIKE :q
            ORDER BY r.date_creation DESC
        ");
        $stmt->execute([':q' => "%$q%"]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.nom_complet AS client
            FROM reclamations r
            JOIN utilisateurs u ON u.id = r.utilisateur_id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE reclamations SET statut=?, date_mise_a_jour=NOW() WHERE id=?");
        return $stmt->execute([$status, $id]);
    }
    public function countByStatus(string $status): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reclamations WHERE statut = ?");
        $stmt->execute([$status]);
        return (int)$stmt->fetchColumn();
    }

    public function getRecent(int $limit = 5): array
    {
        $sql = "
            SELECT 
                r.*,
                u.nom_complet AS client,
                COALESCE(ua.nom_complet, '—') AS agent
            FROM reclamations r
            JOIN utilisateurs u ON u.id = r.utilisateur_id
            LEFT JOIN affectations a ON a.reclamation_id = r.id
            LEFT JOIN utilisateurs ua ON ua.id = a.agent_id
            ORDER BY r.date_creation DESC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
}

    
    public function getAllWithDetails(): array
    {
        $sql = "
            SELECT 
                r.*,
                u.nom_complet AS client,
                c.nom AS categorie,
                sc.nom AS sous_categorie,
                ua.nom_complet AS agent
            FROM reclamations r
            JOIN utilisateurs u ON u.id = r.utilisateur_id
            LEFT JOIN categories c ON c.id = r.categorie_id
            LEFT JOIN sous_categories sc ON sc.id = r.sous_categorie_id
            LEFT JOIN affectations a ON a.reclamation_id = r.id
            LEFT JOIN utilisateurs ua ON ua.id = a.agent_id
            ORDER BY r.date_creation DESC
        ";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function delete(int $id): bool
    {
        // supprimer les dépendances
        $this->pdo->prepare("DELETE FROM messages WHERE reclamation_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM remarques_reclamation WHERE reclamation_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM affectations WHERE reclamation_id = ?")->execute([$id]);

        $stmt = $this->pdo->prepare("DELETE FROM reclamations WHERE id = ?");
        return $stmt->execute([$id]);
    }


}




