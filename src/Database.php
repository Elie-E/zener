<?php

namespace App;

class Database {
    private static $instance = null;
    private $connection = null;
    private $maxRetries = 5;
    private $retryDelay = 2; // seconds

    private function __construct() {
        $attempt = 0;
        
        while ($attempt < $this->maxRetries) {
            try {
                $this->connection = new \PDO(
                    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASSWORD'],
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
                // If connection successful, break the loop
                break;
            } catch (\PDOException $e) {
                $attempt++;
                if ($attempt === $this->maxRetries) {
                    throw new \Exception("Failed to connect to database after {$this->maxRetries} attempts: " . $e->getMessage());
                }
                sleep($this->retryDelay);
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
