<?php
class User
{
    private $conn;

    public function __construct($odbcConnection)
    {
        $this->conn = $odbcConnection;
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT id, nom_complet, email, mot_de_passe, role FROM UTILISATEURS WHERE email = ?";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return null;
        if (!odbc_execute($stmt, [$email])) return null;

        $row = odbc_fetch_array($stmt);
        return $row ?: null;
    }

    public function create(string $nom, string $email, string $hash, string $role = 'user')
    {
        $sql = "INSERT INTO UTILISATEURS (nom_complet, email, mot_de_passe, role, date_creation)
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = odbc_prepare($this->conn, $sql);
        if (!$stmt) return false;
        return odbc_execute($stmt, [$nom, $email, $hash, $role]);
    }
}
?>
