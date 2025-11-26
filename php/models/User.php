<?php
require_once __DIR__ . '/../database.php';

class User
{
    /** @var PDO */
    private $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO utilisateurs (nom_complet, email, mot_de_passe, role, telephone, adresse)
                VALUES (:nom, :email, :password, :role, :tel, :adresse)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nom'      => $data['nom_complet'],
            ':email'    => $data['email'],
            ':password' => $data['mot_de_passe'], // déjà hashé avant
            ':role'     => $data['role'] ?? 'user',
            ':tel'      => $data['telephone'] ?? null,
            ':adresse'  => $data['adresse'] ?? null,
        ]);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }

    public function getAllAgents(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs WHERE role = 'agent' ORDER BY nom_complet");
        return $stmt->fetchAll();
    }

    public function getAllClients(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs WHERE role = 'user' ORDER BY nom_complet");
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE utilisateurs
                SET nom_complet = :nom,
                    email       = :email,
                    role        = :role,
                    telephone   = :tel,
                    adresse     = :adresse
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nom'     => $data['nom_complet'],
            ':email'   => $data['email'],
            ':role'    => $data['role'],
            ':tel'     => $data['telephone'] ?? null,
            ':adresse' => $data['adresse'] ?? null,
            ':id'      => $id,
        ]);
    }

    public function updatePassword(int $id, string $hash): bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET mot_de_passe = :pwd WHERE id = :id");
        return $stmt->execute([':pwd' => $hash, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
