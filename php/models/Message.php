<?php
class Message
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function send(int $recId, int $userId, string $msg): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (reclamation_id, utilisateur_id, message)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$recId, $userId, $msg]);
    }

    public function getByReclamation(int $recId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.nom_complet
            FROM messages m
            JOIN utilisateurs u ON u.id = m.utilisateur_id
            WHERE m.reclamation_id=?
            ORDER BY m.date_creation ASC
        ");
        $stmt->execute([$recId]);
        return $stmt->fetchAll();
    }
}
