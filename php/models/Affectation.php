<?php
require_once __DIR__ . '/../database.php';

class Affectation
{
    private $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function assign(int $reclam_id, int $agent_id): bool
    {
        $sql = "INSERT INTO affectations (reclamation_id, agent_id)
                VALUES (:rec, :agent)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':rec'   => $reclam_id,
            ':agent' => $agent_id,
        ]);
    }

    public function getForReclamation(int $reclam_id): array
    {
        $sql = "SELECT a.*, u.nom_complet AS agent_nom
                FROM affectations a
                JOIN utilisateurs u ON u.id = a.agent_id
                WHERE a.reclamation_id = :rec
                ORDER BY a.date_affectation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':rec' => $reclam_id]);
        return $stmt->fetchAll();
    }

    public function getForAgent(int $agent_id): array
    {
        $sql = "SELECT a.*, r.objet, r.statut
                FROM affectations a
                JOIN reclamations r ON r.id = a.reclamation_id
                WHERE a.agent_id = :agent
                ORDER BY a.date_affectation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':agent' => $agent_id]);
        return $stmt->fetchAll();
    }
}
