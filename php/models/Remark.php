<?php
class Remark
{
    private $conn;

    public function __construct($odbcConnection)
    {
        $this->conn = $odbcConnection;
    }

    public function add(int $reclamationId, int $agentId, string $statut, string $remarque)
    {
        $sql = "INSERT INTO REMARQUES_RECLAMATION (reclamation_id, utilisateur_id, statut, remarque, date_creation)
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return false;
        return odbc_execute($stmt, [$reclamationId, $agentId, $statut, $remarque]);
    }
}
?>
