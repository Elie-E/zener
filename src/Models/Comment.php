<?php

namespace App\Models;

use App\Database;

class Comment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCommentsByQuoteId($quoteId) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username 
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.quote_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$quoteId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createComment($quoteId, $commentText, $userId = null, $authorName = null, $parentId = null) {
        $stmt = $this->db->prepare("
            INSERT INTO comments (quote_id, user_id, author_name, comment_text, parent_id, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$quoteId, $userId, $authorName, $commentText, $parentId]);
    }
}
