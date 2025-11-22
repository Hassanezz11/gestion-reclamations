<?php
class SubCategory
{
    private $conn;

    public function __construct($odbcConnection)
    {
        $this->conn = $odbcConnection;
    }

    public function findByCategory(int $categorieId)
    {
        $sql = "SELECT * FROM SOUS_CATEGORIES WHERE categorie_id = ? ORDER BY nom";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return [];
        if (!odbc_execute($stmt, [$categorieId])) return [];
        $rows = [];
        while ($row = odbc_fetch_array($stmt)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
?>
