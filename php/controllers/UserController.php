<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Reclamation.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Remark.php';

class UserController
{
    private PDO $pdo;
    private Reclamation $reclamationModel;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
        $this->reclamationModel = new Reclamation($this->pdo);
    }

    public function getDashboardStats(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'non_assignee' THEN 1 ELSE 0 END) as en_attente,
                SUM(CASE WHEN statut = 'en_cours' THEN 1 ELSE 0 END) as en_cours,
                SUM(CASE WHEN statut = 'resolue' THEN 1 ELSE 0 END) as resolues
            FROM reclamations
            WHERE utilisateur_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getRecentReclamations(int $userId, int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, c.nom AS categorie, sc.nom AS sous_categorie
            FROM reclamations r
            LEFT JOIN categories c ON c.id = r.categorie_id
            LEFT JOIN sous_categories sc ON sc.id = r.sous_categorie_id
            WHERE r.utilisateur_id = ?
            ORDER BY r.date_creation DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllReclamations(int $userId, ?string $statusFilter = null): array
    {
        $sql = "
            SELECT r.*, c.nom AS categorie, sc.nom AS sous_categorie
            FROM reclamations r
            LEFT JOIN categories c ON c.id = r.categorie_id
            LEFT JOIN sous_categories sc ON sc.id = r.sous_categorie_id
            WHERE r.utilisateur_id = ?
        ";

        $params = [$userId];

        if ($statusFilter && $statusFilter !== 'all') {
            $sql .= " AND r.statut = ?";
            $params[] = $statusFilter;
        }

        $sql .= " ORDER BY r.date_creation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getReclamationDetails(int $reclamationId, int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*,
                   c.nom AS categorie,
                   sc.nom AS sous_categorie,
                   u.nom_complet AS agent_nom
            FROM reclamations r
            LEFT JOIN categories c ON c.id = r.categorie_id
            LEFT JOIN sous_categories sc ON sc.id = r.sous_categorie_id
            LEFT JOIN affectations a ON a.reclamation_id = r.id
            LEFT JOIN utilisateurs u ON u.id = a.agent_id
            WHERE r.id = ? AND r.utilisateur_id = ?
        ");
        $stmt->execute([$reclamationId, $userId]);
        return $stmt->fetch() ?: null;
    }

    public function getReclamationMessages(int $reclamationId): array
    {
        $messageModel = new Message($this->pdo);
        return $messageModel->getByReclamation($reclamationId);
    }

    public function getReclamationRemarks(int $reclamationId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT rr.*, u.nom_complet AS agent_nom
            FROM remarques_reclamation rr
            JOIN utilisateurs u ON u.id = rr.utilisateur_id
            WHERE rr.reclamation_id = ?
            ORDER BY rr.date_creation ASC
        ");
        $stmt->execute([$reclamationId]);
        return $stmt->fetchAll();
    }

    public function sendMessage(int $reclamationId, int $userId, string $message): bool
    {
        $messageModel = new Message($this->pdo);
        return $messageModel->send($reclamationId, $userId, $message);
    }
}
?>
