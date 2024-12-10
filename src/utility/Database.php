<?php

namespace Database;

use PDOException;

class Database
{
    public object $conn;

    public function __construct()
    {
        try {
            $this->conn = $this->instantiatePDO();

        } catch (\PDOException $ex) {
            echo "Connection failed: " . $ex->getMessage();
        }
    }

    public static function query(string $query): false|array
    {
        $pdo = self::instantiatePDO();

        $statement = $pdo->query($query);

        return $statement->fetchall();
    }

    private static function instantiatePDO()
    {
        $dbConnection = env('DB_CONNECTION');
        $dbHost = env('DB_HOST');
        $dbName = env('DB_NAME');

        try {
            return (new \PDO(
                "{$dbConnection}:host={$dbHost};dbname={$dbName}",
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            ));

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}