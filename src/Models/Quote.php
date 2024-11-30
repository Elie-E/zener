<?php

namespace App\Models;

use App\Database;

class Quote {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createQuote($quoteText, $artist, $songTitle, $userId) {
        $stmt = $this->db->prepare("
            INSERT INTO quotes (quote_text, artist, song_title, user_id, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$quoteText, $artist, $songTitle, $userId]);
    }

    public function getAllQuotes() {
        $stmt = $this->db->prepare("
            SELECT q.*, u.username, COUNT(l.id) as like_count,
                   CASE WHEN ? IS NOT NULL THEN EXISTS(
                       SELECT 1 FROM likes 
                       WHERE quote_id = q.id AND user_id = ?
                   ) ELSE 0 END as user_has_liked
            FROM quotes q
            LEFT JOIN users u ON q.user_id = u.id
            LEFT JOIN likes l ON q.id = l.quote_id
            GROUP BY q.id
            ORDER BY q.created_at DESC
        ");
        $userId = $_SESSION['user_id'] ?? null;
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getQuoteById($id) {
        $stmt = $this->db->prepare("
            SELECT q.*, u.username, COUNT(l.id) as like_count,
                   CASE WHEN ? IS NOT NULL THEN EXISTS(
                       SELECT 1 FROM likes 
                       WHERE quote_id = q.id AND user_id = ?
                   ) ELSE 0 END as user_has_liked
            FROM quotes q
            LEFT JOIN users u ON q.user_id = u.id
            LEFT JOIN likes l ON q.id = l.quote_id
            WHERE q.id = ?
            GROUP BY q.id
        ");
        $userId = $_SESSION['user_id'] ?? null;
        $stmt->execute([$userId, $userId, $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
