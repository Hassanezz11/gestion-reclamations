<?php
class Reclamation
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $userId, int $categorieId, int $sousCategorieId, string $objet, string $description, ?string $pieceJointe, string $priorite = 'moyenne'): bool
    {
        $sql = "
            INSERT INTO reclamations (utilisateur_id, categorie_id, sous_categorie_id, objet, description, piece_jointe, priorite, statut, date_creation)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'non_assignee', NOW())
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $categorieId, $sousCategorieId, $objet, $description, $pieceJointe, $priorite]);
    }

    public function update(int $id, string $objet, string $description, string $priorite, int $categorieId, int $sousCategorieId, ?string $pieceJointe = null): bool
    {
        $sql = "
            UPDATE reclamations
            SET objet = ?, description = ?, priorite = ?, categorie_id = ?, sous_categorie_id = ?, piece_jointe = ?, date_mise_a_jour = NOW()
            WHERE id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$objet, $description, $priorite, $categorieId, $sousCategorieId, $pieceJointe, $id]);
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

    public function getDetailsForAgent(int $id, int $agentId): ?array
    {
        $sql = "
            SELECT 
                r.*,
                u.nom_complet AS client,
                u.email AS client_email,
                u.telephone AS client_phone,
                c.nom AS categorie,
                sc.nom AS sous_categorie,
                ua.nom_complet AS agent
            FROM reclamations r
            JOIN utilisateurs u ON u.id = r.utilisateur_id
            LEFT JOIN categories c ON c.id = r.categorie_id
            LEFT JOIN sous_categories sc ON sc.id = r.sous_categorie_id
            JOIN affectations a ON a.reclamation_id = r.id
            LEFT JOIN utilisateurs ua ON ua.id = a.agent_id
            WHERE r.id = :id AND a.agent_id = :agentId
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':agentId' => $agentId
        ]);

        return $stmt->fetch() ?: null;
    }

    public function getForAgent(int $agentId, ?string $status = null, ?string $search = null, ?int $limit = null): array
    {
        $sql = "
            SELECT 
                r.*,
                u.nom_complet AS client,
                u.email AS client_email,
                c.nom AS categorie,
                sc.nom AS sous_categorie
            FROM reclamations r
            JOIN affectations a ON a.reclamation_id = r.id
            JOIN utilisateurs u ON u.id = r.utilisateur_id
            LEFT JOIN categories c ON c.id = r.categorie_id
            LEFT JOIN sous_categories sc ON sc.id = r.sous_categorie_id
            WHERE a.agent_id = :agentId
        ";

        if ($status) {
            $sql .= " AND r.statut = :status";
        }

        if ($search !== null && $search !== '') {
            $sql .= " AND (u.nom_complet LIKE :q OR r.objet LIKE :q OR r.description LIKE :q)";
        }

        $sql .= " ORDER BY r.date_creation DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':agentId', $agentId, PDO::PARAM_INT);

        if ($status) {
            $stmt->bindValue(':status', $status);
        }

        if ($search !== null && $search !== '') {
            $stmt->bindValue(':q', "%$search%");
        }

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
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

    public function countForAgentByStatus(int $agentId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.statut, COUNT(*) AS total
            FROM reclamations r
            JOIN affectations a ON a.reclamation_id = r.id
            WHERE a.agent_id = ?
            GROUP BY r.statut
        ");
        $stmt->execute([$agentId]);
        $data = $stmt->fetchAll();

        $counts = [
            'non_assignee' => 0,
            'en_cours' => 0,
            'resolue' => 0,
        ];

        foreach ($data as $row) {
            $counts[$row['statut']] = (int)$row['total'];
        }

        $counts['total'] = array_sum($counts);
        return $counts;
    }

    public function getRecent(int $limit = 5): array
    {
        $sql = "
            SELECT 
                r.*,
                u.nom_complet AS client,
                COALESCE(ua.nom_complet, 'Non assignee') AS agent
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
        // supprimer les dependances
        $this->pdo->prepare("DELETE FROM messages WHERE reclamation_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM remarques_reclamation WHERE reclamation_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM affectations WHERE reclamation_id = ?")->execute([$id]);

        $stmt = $this->pdo->prepare("DELETE FROM reclamations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

