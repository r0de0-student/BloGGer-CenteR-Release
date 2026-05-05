<?php
class AdminController {
    private $db;
    private $userModel;
    private $postModel;
    private $commentModel;
    private $blogModel;
    
    public function __construct($pdo) {
        $this->db = $pdo;
        $this->userModel = new User($pdo);
        $this->postModel = new Post($pdo);
        $this->commentModel = new Comment($pdo);
        $this->blogModel = new Blog($pdo);
    }
    
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Доступ запрещён";
            header('Location: ' . BASE_PATH . '/?action=login');
            exit;
        }
    }
    
    public function users() {
        $this->checkAdmin();
        $users = $this->userModel->getAll();
        require_once __DIR__ . '/../views/admin/users.php';
    }
    
    public function toggleBlock($userId) {
        $this->checkAdmin();
        $user = $this->userModel->findById($userId);
        if (!$user) {
            $_SESSION['error'] = "Пользователь не найден";
            header('Location: ' . BASE_PATH . '/?action=admin-users');
            exit;
        }
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = "Нельзя заблокировать себя";
            header('Location: ' . BASE_PATH . '/?action=admin-users');
            exit;
        }
        $this->userModel->toggleBlock($userId);
        $status = $user['is_blocked'] ? "разблокирован" : "заблокирован";
        $_SESSION['success'] = "Пользователь " . $user['name'] . " " . $status;
        header('Location: ' . BASE_PATH . '/?action=admin-users');
        exit;
    }
    
    public function updateRole($userId, $role) {
        $this->checkAdmin();
        $validRoles = ['author', 'admin'];
        if (!in_array($role, $validRoles)) {
            $_SESSION['error'] = "Некорректная роль";
            header('Location: ' . BASE_PATH . '/?action=admin-users');
            exit;
        }
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = "Нельзя изменить свою роль";
            header('Location: ' . BASE_PATH . '/?action=admin-users');
            exit;
        }
        $user = $this->userModel->findById($userId);
        if (!$user) {
            $_SESSION['error'] = "Пользователь не найден";
            header('Location: ' . BASE_PATH . '/?action=admin-users');
            exit;
        }
        $this->userModel->updateRole($userId, $role);
        $_SESSION['success'] = "Роль изменена на " . $role;
        header('Location: ' . BASE_PATH . '/?action=admin-users');
        exit;
    }
    
    public function posts() {
        $this->checkAdmin();
        $posts = $this->postModel->getAllForAdmin();
        require_once __DIR__ . '/../views/admin/posts.php';
    }
    
    public function deletePost($postId) {
        $this->checkAdmin();
        $post = $this->postModel->getByIdForAdmin($postId);
        if (!$post) {
            $_SESSION['error'] = "Статья не найдена";
            header('Location: ' . BASE_PATH . '/?action=admin-posts');
            exit;
        }
        $this->postModel->deleteByAdmin($postId);
        $_SESSION['success'] = "Статья удалена";
        header('Location: ' . BASE_PATH . '/?action=admin-posts');
        exit;
    }
    
    public function comments() {
        $this->checkAdmin();
        $comments = $this->commentModel->getAllForAdmin();
        require_once __DIR__ . '/../views/admin/comments.php';
    }
    
    public function deleteComment($commentId) {
        $this->checkAdmin();
        $comment = $this->commentModel->findById($commentId);
        if (!$comment) {
            $_SESSION['error'] = "Комментарий не найден";
            header('Location: ' . BASE_PATH . '/?action=admin-comments');
            exit;
        }
        $this->commentModel->hardDelete($commentId);
        $_SESSION['success'] = "Комментарий удалён";
        header('Location: ' . BASE_PATH . '/?action=admin-comments');
        exit;
    }
}
?>