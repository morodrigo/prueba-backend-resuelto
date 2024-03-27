<?php
class Db
{
    private static $connection;

    public static function connect()
    {
        if (!isset(self::$connection)) {
            self::$connection = new mysqli(Config::$host, Config::$user, Config::$password, Config::$database);
            if (self::$connection->connect_error) {
                die("Connection error: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }

    public static function disconnect()
    {
        if (isset(self::$connection)) {
            self::$connection->close();
        }
    }
    public static function executeQuery($query,$params, $returnAffectedRows = false)
    {
        $connection = self::connect();
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            die("Error preparing statement: " . $connection->error);
        }
        if ($params) {
            $bindResult = $stmt->bind_param(...$params);
            if (!$bindResult) {
                die("Error binding parameters: " . $stmt->error);
            }
        }
        $executeResult = $stmt->execute();
        if (!$executeResult) {
            die("Error executing query: " . $stmt->error);
        }
        if ($returnAffectedRows) {
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        }
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
}
