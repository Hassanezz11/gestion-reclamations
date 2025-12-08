<?php
class Agent
{
    private PDO $pdo;
    private string $role = 'agent';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $name, string $email, string $passwordHash, ?string $telephone = null, ?string $adresse = null): bool
    {
        $sql = "
            INSERT INTO utilisateurs (nom_complet, email, mot_de_passe, role, telephone, adresse, date_creation)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $email, $passwordHash, $this->role, $telephone, $adresse]);
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

    public function update(int $id, string $name, string $email, ?string $telephone, ?string $adresse): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE utilisateurs
            SET nom_complet = ?, email = ?, telephone = ?, adresse = ?
            WHERE id = ? AND role = 'agent'
        ");

        return $stmt->execute([$name, $email, $telephone, $adresse, $id]);
    }

    public function delete(int $id): bool
    {
        // Nettoyer les relations avant suppression
        $this->pdo->prepare("DELETE FROM affectations WHERE agent_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM remarques_reclamation WHERE utilisateur_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM messages WHERE utilisateur_id = ?")->execute([$id]);

        $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = ? AND role = 'agent'");
        return $stmt->execute([$id]);
    }
}
