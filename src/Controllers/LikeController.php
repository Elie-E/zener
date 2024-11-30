<?php

namespace App\Controllers;

use App\Models\Like;

class LikeController {
    private $likeModel;

    public function __construct() {
        $this->likeModel = new Like();
    }

    public function toggle() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Must be logged in to like quotes']);
            return;
        }

        $quoteId = $_POST['quote_id'] ?? null;
        if (!$quoteId) {
            http_response_code(400);
            echo json_encode(['error' => 'Quote ID is required']);
            return;
        }

        $isLiked = $this->likeModel->toggleLike($_SESSION['user_id'], $quoteId);
        $likeCount = $this->likeModel->getLikeCount($quoteId);

        echo json_encode([
            'success' => true,
            'isLiked' => $isLiked,
            'likeCount' => $likeCount
        ]);
    }
}
