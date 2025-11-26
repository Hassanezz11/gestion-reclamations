<?php
require_once __DIR__ . '/../database.php';

class Message
{
    private $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function send(int $reclam_id, int $user_id, string $msg): bool
    {
        $sql = "INSERT INTO messages (reclamation_id, utilisateur_id, message)
                VALUES (:rec, :user, :msg)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':rec'  => $reclam_id,
            ':user' => $user_id,
            ':msg'  => $msg,
        ]);
    }

    public function getByReclamation(int $reclam_id): array
    {
        $sql = "SELECT m.*, u.nom_complet AS auteur
                FROM messages m
                JOIN utilisateurs u ON u.id = m.utilisateur_id
                WHERE m.reclamation_id = :rec
                ORDER BY m.date_creation ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':rec' => $reclam_id]);
        return $stmt->fetchAll();
    }
}
