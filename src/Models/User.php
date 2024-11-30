<?php

namespace App\Models;

class User {
    private $db;

    public function __construct() {
        $maxRetries = 5;
        $retryDelay = 2;
        $attempt = 0;
        
        while ($attempt < $maxRetries) {
            try {
                $this->db = new \PDO(
                    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASSWORD'],
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
                break;
            } catch (\PDOException $e) {
                $attempt++;
                if ($attempt === $maxRetries) {
                    throw new \Exception("Failed to connect to database after {$maxRetries} attempts: " . $e->getMessage());
                }
                sleep($retryDelay);
            }
        }
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($username, $password) {
        // Check if username already exists
        if ($this->findByUsername($username)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashedPassword]);
    }

    public function verifyPassword($username, $password) {
        $user = $this->findByUsername($username);
        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password']);
    }
}
