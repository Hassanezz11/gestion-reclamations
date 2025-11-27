<?php
class Affectation
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function assign(int $recId, int $agentId): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO affectations (reclamation_id, agent_id, date_affectation)
            VALUES (?, ?, NOW())
        ");
        return $stmt->execute([$recId, $agentId]);
    }

    public function getAgentForReclamation(int $recId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*
            FROM affectations a
            JOIN utilisateurs u ON u.id = a.agent_id
            WHERE a.reclamation_id = ?
        ");
        $stmt->execute([$recId]);
        return $stmt->fetch() ?: null;
    }
    public function countByAgent(int $agentId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM affectations WHERE agent_id = ?");
        $stmt->execute([$agentId]);
        return (int)$stmt->fetchColumn();
    }

}
