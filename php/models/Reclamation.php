<?php
require_once __DIR__ . '/../database.php';

class Reclamation
{
    private $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO reclamations (
                    utilisateur_id, categorie_id, sous_categorie_id,
                    objet, description, piece_jointe, priorite, statut
                )
                VALUES (
                    :user_id, :cat_id, :scat_id,
                    :objet, :description, :piece, :priorite, :statut
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':user_id'     => $data['utilisateur_id'],
            ':cat_id'      => $data['categorie_id'],
            ':scat_id'     => $data['sous_categorie_id'],
            ':objet'       => $data['objet'],
            ':description' => $data['description'],
            ':piece'       => $data['piece_jointe'] ?? null,
            ':priorite'    => $data['priorite'] ?? 'moyenne',
            ':statut'      => $data['statut'] ?? 'non_assignee',
        ]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.*, u.nom_complet AS nom_utilisateur
             FROM reclamations r
             JOIN utilisateurs u ON u.id = r.utilisateur_id
             WHERE r.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByUser(int $user_id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM reclamations
             WHERE utilisateur_id = :id
             ORDER BY date_creation DESC"
        );
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $sql = "SELECT r.*, u.nom_complet AS nom_utilisateur
                FROM reclamations r
                JOIN utilisateurs u ON u.id = r.utilisateur_id
                ORDER BY r.date_creation DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getAssignedToAgent(int $agent_id): array
    {
        $sql = "SELECT r.*
                FROM reclamations r
                JOIN affectations a ON a.reclamation_id = r.id
                WHERE a.agent_id = :agent
                ORDER BY r.date_creation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':agent' => $agent_id]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $statut): bool
    {
        $sql = "UPDATE reclamations
                SET statut = :statut, date_mise_a_jour = NOW()
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE reclamations
                SET objet = :objet,
                    description = :description,
                    priorite = :priorite,
                    statut = :statut,
                    date_mise_a_jour = NOW()
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':objet'       => $data['objet'],
            ':description' => $data['description'],
            ':priorite'    => $data['priorite'],
            ':statut'      => $data['statut'],
            ':id'          => $id,
        ]);
    }
}
