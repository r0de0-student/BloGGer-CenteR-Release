<?php
class Comment {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    // Создание комментария
    public function create($postId, $userId, $content, $parentId = null) {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content, parent_comment_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$postId, $userId, $content, $parentId]);
    }
    
    // Получить комментарии поста (только активные)
    public function getByPostId($postId) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name as user_name 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.post_id = ? AND c.is_deleted = FALSE 
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
    
    // Мягкое удаление комментария
    public function delete($commentId) {
        $stmt = $this->db->prepare("UPDATE comments SET is_deleted = TRUE WHERE id = ?");
        return $stmt->execute([$commentId]);
    }
    
    // Найти комментарий по ID (для проверки прав)
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name as user_name, u.id as user_id, u.role as user_role, p.blog_id, p.id as post_id
            FROM comments c
            JOIN users u ON c.user_id = u.id
            JOIN posts p ON c.post_id = p.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Получить все комментарии для админа
    public function getAllForAdmin() {
        $stmt = $this->db->query("
            SELECT c.*, u.name as user_name, p.title as post_title
            FROM comments c
            JOIN users u ON c.user_id = u.id
            JOIN posts p ON c.post_id = p.id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    // Полное удаление (для админа)
    public function hardDelete($commentId) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$commentId]);
    }
}
?>