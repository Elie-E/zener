<?php

namespace App\Controllers;

use App\Models\Comment;

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $quoteId = $_POST['quote_id'] ?? null;
        $commentText = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';
        $authorName = isset($_POST['author_name']) ? trim($_POST['author_name']) : null;
        $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        if (!$quoteId || empty($commentText)) {
            $_SESSION['error'] = 'Comment text is required';
            header('Location: /quote/view?id=' . $quoteId);
            exit;
        }

        // If user is logged in, use their ID instead of author name
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $authorName = null;
        }

        try {
            $this->commentModel->createComment($quoteId, $commentText, $userId, $authorName, $parentId);
            $_SESSION['success'] = 'Comment added successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to add comment';
        }
        
        // Redirect back to the quote
        header('Location: /quote/view?id=' . $quoteId);
        exit;
    }
}
