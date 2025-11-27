<?php
class Remark
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(int $recId, int $agentId, string $status, string $remark): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO remarques_reclamation (reclamation_id, utilisateur_id, statut, remarque)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$recId, $agentId, $status, $remark]);
    }

    public function getByReclamation(int $recId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT rr.*, u.nom_complet
            FROM remarques_reclamation rr
            JOIN utilisateurs u ON u.id = rr.utilisateur_id
            WHERE rr.reclamation_id=?
            ORDER BY rr.date_creation DESC
        ");
        $stmt->execute([$recId]);
        return $stmt->fetchAll();
    }
}
