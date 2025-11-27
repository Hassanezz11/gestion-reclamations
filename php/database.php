<?php
/**
 * Classe Database
 * ----------------
 * Singleton responsable de la connexion PDO à MySQL.
 *
 * Utilisation :
 *   $pdo = Database::getInstance();
 */

class Database
{
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'reclam_db';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    /** @var ?PDO */
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * Retourne l'instance PDO unique
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {

            $dsn = 'mysql:host=' . self::DB_HOST .
                   ';dbname=' . self::DB_NAME .
                   ';charset=' . self::DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Exceptions visibles
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, //FIX FOR HY093
            ];

            try {
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);

                // Optional debug:
                // echo "Connexion OK !";

            } catch (PDOException $e) {
                die('Erreur de connexion MySQL : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
