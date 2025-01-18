<?php
namespace www\classes;

use PDO;
use PDOException;

class ConnectToDB {
    private $host = 'mysql_container';
    private $db   = 'rootdb';
    private $user = 'mysql';
    private $pass = 'mysql';
    private $conn = null;
    private $stmt = null;

    private static $instance = null;

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8mb4", $this->user, $this->pass);
            $this->conn->exec("SET NAMES 'utf8mb4';");
        } catch(PDOException $e) {
            echo "Error with connection, what wrong with it: " . $e->getMessage();
        }
    }

    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function fetch($sql, $params = []) {
        $this->stmt = $this->conn->prepare($sql);
        $this->stmt->execute($params);
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    function fetchAll($sql, $params = []) {
        $this->stmt = $this->conn->prepare($sql);
        $this->stmt->execute($params);
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function query($sql, $data = []) {
        $this->stmt = $this->conn->prepare($sql);
        $this->stmt->execute($data);
        return $this->stmt;
    }

    function close() {
        if($this->conn !== null) {
            $this->conn = null;
        }
    }
}
