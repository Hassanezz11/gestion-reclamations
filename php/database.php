<?php
/**
 * Connexion Oracle via ODBC
 * Configurez d'abord un DSN système dans Windows (ex: ORACLE_DSN)
 */
class Database
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            $dsn = 'ORACLE_DSN'; // Nom du DSN ODBC à configurer
            $user = 'oracle_user';
            $pass = 'oracle_password';

            $conn = odbc_connect($dsn, $user, $pass);
            if (!$conn) {
                die('Erreur de connexion ODBC Oracle : ' . odbc_errormsg());
            }
            self::$conn = $conn;
        }
        return self::$conn;
    }
}
?>
