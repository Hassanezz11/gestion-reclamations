<?php
class Agent
{
    private PDO $pdo;
    private string $role = 'agent';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllAgents(): array
    {
        $stmt = $this->pdo->query("
            SELECT u.*, 
                   (SELECT COUNT(*) FROM affectations a WHERE a.agent_id = u.id) AS nb_affectations
            FROM utilisateurs u
            WHERE role = 'agent'
            ORDER BY nom_complet ASC
        ");

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = ? AND role = 'agent'");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
