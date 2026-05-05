<?php
class HomeController {
    private $postModel;
    
    public function __construct($pdo) {
        $this->postModel = new Post($pdo);
    }
    
    public function index() {
        $posts = $this->postModel->getAll();
        require_once __DIR__ . '/../views/home/index.php';
    }
    
    public function search() {
        $query = $_GET['q'] ?? '';
        $posts = [];
        if (!empty($query)) {
            $posts = $this->postModel->search($query);
        }
        require_once __DIR__ . '/../views/home/search.php';
    }
}
?>