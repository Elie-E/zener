<?php

namespace App\Controllers;

use App\Models\Quote;
use App\Models\Comment;

class QuoteController {
    private $quoteModel;
    private $commentModel;

    public function __construct() {
        $this->quoteModel = new Quote();
        $this->commentModel = new Comment();
    }

    public function index() {
        $quotes = $this->quoteModel->getAllQuotes();
        
        ob_start();
        include __DIR__ . '/../../views/quotes/index.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../../views/layouts/main.php';
    }

    public function view() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /');
            exit;
        }

        $quote = $this->quoteModel->getQuoteById($id);
        if (!$quote) {
            header('Location: /');
            exit;
        }

        // Get comments for this quote
        $comments = $this->commentModel->getCommentsByQuoteId($id);

        ob_start();
        include __DIR__ . '/../../views/quotes/view.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../../views/layouts/main.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $error = $_SESSION['quote_error'] ?? null;
        unset($_SESSION['quote_error']);

        ob_start();
        include __DIR__ . '/../../views/quotes/create.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../../views/layouts/main.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /quote/create');
            exit;
        }

        $text = trim($_POST['quote_text'] ?? '');
        $artist = trim($_POST['artist'] ?? '');
        $songTitle = trim($_POST['song_title'] ?? '');

        // Validation
        if (empty($text) || empty($artist) || empty($songTitle)) {
            $_SESSION['quote_error'] = 'Please fill in all fields';
            header('Location: /quote/create');
            exit;
        }

        if (strlen($text) > 1000) {
            $_SESSION['quote_error'] = 'Quote text is too long (maximum 1000 characters)';
            header('Location: /quote/create');
            exit;
        }

        try {
            $quoteId = $this->quoteModel->createQuote($_SESSION['user_id'], $text, $artist, $songTitle);
            header('Location: /quote/view?id=' . $quoteId);
        } catch (\Exception $e) {
            $_SESSION['quote_error'] = 'Failed to create quote. Please try again.';
            header('Location: /quote/create');
        }
        exit;
    }
}
