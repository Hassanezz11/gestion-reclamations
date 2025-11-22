<?php
class Reclamation
{
    private $conn;

    public function __construct($odbcConnection)
    {
        $this->conn = $odbcConnection;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO RECLAMATIONS (utilisateur_id, categorie_id, sous_categorie_id, objet, description, piece_jointe, priorite, statut, date_creation)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'EN COURS', CURRENT_TIMESTAMP)";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return false;

        return odbc_execute($stmt, [
            $data['utilisateur_id'],
            $data['categorie_id'],
            $data['sous_categorie_id'],
            $data['objet'],
            $data['description'],
            $data['piece_jointe'],
            $data['priorite']
        ]);
    }

    public function findByUser(int $userId)
    {
        $sql = "SELECT * FROM RECLAMATIONS WHERE utilisateur_id = ? ORDER BY date_creation DESC";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return [];

        if (!odbc_execute($stmt, [$userId])) return [];

        $rows = [];
        while ($row = odbc_fetch_array($stmt)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
?>
