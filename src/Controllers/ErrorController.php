<?php

namespace App\Controllers;

class ErrorController {
    public function notFound() {
        header("HTTP/1.0 404 Not Found");
        
        ob_start();
        include __DIR__ . '/../../views/404.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../../views/layouts/main.php';
    }
}
