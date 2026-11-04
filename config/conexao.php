<?php
class Conexao {
    public static function getConn() {
        $host = getenv("SAKANA_DB_HOST") ?: "localhost";
        $port = getenv("SAKANA_DB_PORT") ?: "3306";
        $dbname = getenv("SAKANA_DB_NAME") ?: "bdSakana";
        $user = getenv("SAKANA_DB_USER") ?: "root";
        $pass = getenv("SAKANA_DB_PASSWORD") ?: "";

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $conexao = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            return $conexao;
        } catch (PDOException $e) {
            throw new RuntimeException("db_unavailable", 0, $e);
        }
    }
    
}
