<?php
class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $nom, string $email, string $passwordHash, string $role = 'user'): bool
    {
        $sql = "INSERT INTO utilisateurs (nom_complet, email, mot_de_passe, role, date_creation)
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $email, $passwordHash, $role]);
    }

    public function getAllClients(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs WHERE role = 'user' ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }

    public function getAllAgents(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs WHERE role = 'agent' ORDER BY nom_complet ASC");
        return $stmt->fetchAll();
    }

    public function searchClients(string $q): array
    {
        $sql = "
            SELECT * 
            FROM utilisateurs 
            WHERE role = 'user'
            AND (nom_complet LIKE :q1 OR email LIKE :q2)
            ORDER BY date_creation DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':q1' => "%$q%",
            ':q2' => "%$q%"
        ]);

        return $stmt->fetchAll();
    }

    public function countReclamations(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reclamations WHERE utilisateur_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
    public function updateProfile(int $id, string $nom, string $email, ?string $tel, ?string $adresse): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE utilisateurs 
            SET nom_complet = ?, email = ?, telephone = ?, adresse = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nom, $email, $tel, $adresse, $id]);
    }

    public function updatePassword(int $id, string $hash): bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

}
