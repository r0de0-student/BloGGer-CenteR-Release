<?php
class PostController {
    private $postModel;
    private $commentModel;
    private $blogModel;
    public $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
        $this->postModel = new Post($pdo);
        $this->commentModel = new Comment($pdo);
        $this->blogModel = new Blog($pdo);
    }
    
    public function view($id) {
        $this->postModel->incrementViews($id);
        $post = $this->postModel->getById($id);
        $comments = $this->commentModel->getByPostId($id);
        require_once __DIR__ . '/../views/posts/view.php';
    }
    
    public function createForm() {
        requireLogin();
        require_once __DIR__ . '/../views/posts/create.php';
    }
    
    public function create() {
        requireLogin();
        $blog = $this->blogModel->findByUserId($_SESSION['user_id']);
        if (!$blog) {
            $_SESSION['error'] = "Сначала создайте блог!";
            header('Location: ' . BASE_PATH . '/?action=my-blog');
            exit;
        }
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if (empty($title)) {
            $_SESSION['error'] = "Заголовок обязателен!";
            header('Location: ' . BASE_PATH . '/?action=create-post');
            exit;
        }
        $this->postModel->create($blog['id'], $title, $content);
        $_SESSION['success'] = "Статья опубликована!";
        header('Location: ' . BASE_PATH . '/?action=my-blog');
        exit;
    }
    
    public function editForm($id) {
        requireLogin();
        $post = $this->postModel->getById($id);
        if (!$post) {
            $_SESSION['error'] = "Статья не найдена";
            header('Location: ' . BASE_PATH . '/?action=my-blog');
            exit;
        }
        $blog = $this->blogModel->findByUserId($_SESSION['user_id']);
        if (!$blog || $post['blog_id'] != $blog['id']) {
            $_SESSION['error'] = "У вас нет прав для редактирования этой статьи";
            header('Location: ' . BASE_PATH . '/?action=my-blog');
            exit;
        }
        require_once __DIR__ . '/../views/posts/edit.php';
    }
    
    public function update($id) {
        requireLogin();
        $post = $this->postModel->getById($id);
        if (!$post) {
            $_SESSION['error'] = "Статья не найдена";
            header('Location: ' . BASE_PATH . '/?action=my-blog');
            exit;
        }
        $blog = $this->blogModel->findByUserId($_SESSION['user_id']);
        if (!$blog || $post['blog_id'] != $blog['id']) {
            $_SESSION['error'] = "У вас нет прав";
            header('Location: ' . BASE_PATH . '/?action=edit-post&id=' . $id);
            exit;
        }
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if (empty($title)) {
            $_SESSION['error'] = "Заголовок обязателен!";
            header('Location: ' . BASE_PATH . '/?action=edit-post&id=' . $id);
            exit;
        }
        $this->postModel->update($id, $title, $content);
        $_SESSION['success'] = "Статья обновлена!";
        header('Location: ' . BASE_PATH . '/?action=my-blog');
        exit;
    }
    
    public function delete($id) {
        requireLogin();
        $post = $this->postModel->getById($id);
        if (!$post) {
            $_SESSION['error'] = "Статья не найдена";
            header('Location: ' . BASE_PATH . '/?action=my-blog');
            exit;
        }
        $blog = $this->blogModel->findByUserId($_SESSION['user_id']);
        if (!$blog || $post['blog_id'] != $blog['id']) {
            $_SESSION['error'] = "У вас нет прав";
            header('Location: ' . BASE_PATH . '/?action=my-blog');
            exit;
        }
        $this->postModel->delete($id);
        $_SESSION['success'] = "Статья удалена!";
        header('Location: ' . BASE_PATH . '/?action=my-blog');
        exit;
    }
    
    public function addComment($postId) {
        requireLogin();
        $content = trim($_POST['content'] ?? '');
        if (!empty($content)) {
            $this->commentModel->create($postId, $_SESSION['user_id'], $content);
            $_SESSION['success'] = "Комментарий добавлен!";
        } else {
            $_SESSION['error'] = "Комментарий не может быть пустым";
        }
        header('Location: ' . BASE_PATH . '/?action=view-post&id=' . $postId);
        exit;
    }
    
    // Удаление комментария
    public function deleteComment($commentId) {
        requireLogin();
        
        $comment = $this->commentModel->findById($commentId);
        if (!$comment) {
            $_SESSION['error'] = "Комментарий не найден";
            header('Location: ' . BASE_PATH . '/');
            exit;
        }
        
        $post = $this->postModel->getById($comment['post_id']);
        
        // Проверка прав
        $isCommentAuthor = ($comment['user_id'] == $_SESSION['user_id']);
        $isAdmin = ($_SESSION['role'] === 'admin');
        
        // Проверка, является ли пользователь автором статьи
        $isPostAuthor = false;
        if ($post && isset($post['author_id'])) {
            $isPostAuthor = ($_SESSION['user_id'] == $post['author_id']);
        }
        
        // Админ может удалить всё
        if ($isAdmin) {
            $this->commentModel->delete($commentId);
            $_SESSION['success'] = "Комментарий удалён администратором";
        }
        // Автор статьи может удалить любой комментарий (кроме админов, если хотите)
        elseif ($isPostAuthor) {
    // Проверяем, не является ли автор комментария администратором
            if ($comment['user_role'] === 'admin') {
                $_SESSION['error'] = "Нельзя удалять комментарий администратора";
            } else {
                $this->commentModel->delete($commentId);
                $_SESSION['success'] = "Комментарий удалён автором статьи";
            }
        }
        // Автор комментария может удалить только свой
        elseif ($isCommentAuthor) {
            $this->commentModel->delete($commentId);
            $_SESSION['success'] = "Ваш комментарий удалён";
        }
        // Нет прав
        else {
            $_SESSION['error'] = "У вас нет прав для удаления этого комментария";
        }
        
        header('Location: ' . BASE_PATH . '/?action=view-post&id=' . $comment['post_id']);
        exit;
    }
}
?>