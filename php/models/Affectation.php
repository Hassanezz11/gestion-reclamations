<?php
class Affectation
{
    private $conn;

    public function __construct($odbcConnection)
    {
        $this->conn = $odbcConnection;
    }

    public function assign(int $reclamationId, int $agentId)
    {
        $sql = "INSERT INTO AFFECTATIONS (reclamation_id, agent_id, date_affectation)
                VALUES (?, ?, CURRENT_TIMESTAMP)";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return false;
        return odbc_execute($stmt, [$reclamationId, $agentId]);
    }
}
?>
