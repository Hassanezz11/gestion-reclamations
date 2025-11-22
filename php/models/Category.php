<?php
class Category
{
    private $conn;

    public function __construct($odbcConnection)
    {
        $this->conn = $odbcConnection;
    }

    public function all()
    {
        $sql = "SELECT * FROM CATEGORIES ORDER BY nom";
        $rs = odbc_exec($this->conn, $sql);
        $rows = [];
        if ($rs) {
            while ($row = odbc_fetch_array($rs)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
}
?>
