<?php
class Message
{
    private $conn;

    public function __construct($odbcConnection)
    {
        $this->conn = $odbcConnection;
    }

    public function create(int $reclamationId, int $userId, string $message)
    {
        $sql = "INSERT INTO MESSAGES (reclamation_id, utilisateur_id, message, date_creation)
                VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return false;
        return odbc_execute($stmt, [$reclamationId, $userId, $message]);
    }

    public function findByReclamation(int $reclamationId)
    {
        $sql = "SELECT * FROM MESSAGES WHERE reclamation_id = ? ORDER BY date_creation ASC";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return [];
        if (!odbc_execute($stmt, [$reclamationId])) return [];
        $rows = [];
        while ($row = odbc_fetch_array($stmt)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
?>
