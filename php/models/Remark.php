<?php
require_once __DIR__ . '/../database.php';

class Remark
{
    private $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function add(int $reclam_id, int $agent_id, string $statut, string $remarque): bool
    {
        $sql = "INSERT INTO remarques_reclamation (
                    reclamation_id, utilisateur_id, statut, remarque
                ) VALUES (:rec, :agent, :statut, :remarque)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':rec'      => $reclam_id,
            ':agent'    => $agent_id,
            ':statut'   => $statut,
            ':remarque' => $remarque,
        ]);
    }

    public function getByReclamation(int $reclam_id): array
    {
        $sql = "SELECT rr.*, u.nom_complet AS agent_nom
                FROM remarques_reclamation rr
                JOIN utilisateurs u ON u.id = rr.utilisateur_id
                WHERE rr.reclamation_id = :rec
                ORDER BY rr.date_creation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':rec' => $reclam_id]);
        return $stmt->fetchAll();
    }
}
