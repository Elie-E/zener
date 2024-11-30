<?php

namespace App\Models;

use App\Database;

class Like {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function toggleLike($userId, $quoteId) {
        // Check if like exists
        $stmt = $this->db->prepare("SELECT id FROM likes WHERE user_id = ? AND quote_id = ?");
        $stmt->execute([$userId, $quoteId]);
        
        if ($stmt->fetch()) {
            // Unlike if already liked
            $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = ? AND quote_id = ?");
            $stmt->execute([$userId, $quoteId]);
            return false; // Indicates unliked
        } else {
            // Like if not already liked
            $stmt = $this->db->prepare("INSERT INTO likes (user_id, quote_id) VALUES (?, ?)");
            $stmt->execute([$userId, $quoteId]);
            return true; // Indicates liked
        }
    }

    public function getLikeCount($quoteId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE quote_id = ?");
        $stmt->execute([$quoteId]);
        return $stmt->fetchColumn();
    }

    public function hasUserLiked($userId, $quoteId) {
        $stmt = $this->db->prepare("SELECT 1 FROM likes WHERE user_id = ? AND quote_id = ?");
        $stmt->execute([$userId, $quoteId]);
        return (bool) $stmt->fetch();
    }

    public function getLikedQuotesByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT q.*, COUNT(l2.id) as like_count 
            FROM quotes q 
            INNER JOIN likes l1 ON q.id = l1.quote_id 
            LEFT JOIN likes l2 ON q.id = l2.quote_id 
            WHERE l1.user_id = ? 
            GROUP BY q.id 
            ORDER BY q.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
