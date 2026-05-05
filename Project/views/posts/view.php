<?php include __DIR__ . '/../layout.php'; ?>

<style>
    .post-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .post-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        text-align: center;
    }
    .post-header h1 {
        font-size: 32px;
        margin-bottom: 20px;
        color: white;
        border-left: none;
    }
    .post-meta {
        display: flex;
        justify-content: center;
        gap: 25px;
        flex-wrap: wrap;
        font-size: 16px;
        color: rgba(255,255,255,0.95);
        background: rgba(0,0,0,0.2);
        padding: 12px 20px;
        border-radius: 50px;
        display: inline-flex;
        margin-top: 15px;
    }
    .post-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Содержание статьи — ГЛАВНОЕ, ЧТОБЫ БЫЛО ВИДНО */
    .post-content {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        line-height: 1.8;
        font-size: 18px;
        color: #000000;
        border: 1px solid #e0e0e0;
    }
    .post-content p {
        margin-bottom: 15px;
        color: #ffffff;
    }
    
    .comments-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .comments-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    .comments-header h3 {
        font-size: 22px;
        color: #000000;
        margin: 0;
    }
    .comments-count {
        background: #667eea;
        color: white;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 14px;
    }
    .comment-form {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .comment-form textarea {
        width: 100%;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        font-size: 14px;
        resize: vertical;
        font-family: inherit;
    }
    .comment-form textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    .comment-form button {
        margin-top: 10px;
        background: #667eea;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        transition: transform 0.2s;
    }
    .comment-form button:hover {
        transform: translateY(-2px);
        background: #5a67d8;
    }
    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .comment-item {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        transition: transform 0.2s;
    }
    .comment-item:hover {
        transform: translateX(5px);
    }
    .comment-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }
    .comment-author {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .comment-avatar {
        width: 40px;
        height: 40px;
        background: #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    .comment-info {
        flex: 1;
    }
    .comment-name {
        font-weight: bold;
        color: #2c3e50;
    }
    .comment-date {
        font-size: 12px;
        color: #999;
        margin-top: 3px;
    }
    .comment-text {
        color: #555;
        line-height: 1.5;
        margin-left: 52px;
        background: white;
        padding: 12px 15px;
        border-radius: 12px;
    }
    .delete-comment-btn {
        background: #ffadad;
        color: white;
        padding: 5px 12px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        transition: all 0.2s;
    }
    .delete-comment-btn:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }
    .empty-comments {
        text-align: center;
        padding: 40px;
        color: #000000;
    }
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #888888;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        margin-bottom: 20px;
        transition: all 0.2s;
    }
    .back-btn:hover {
        background: #000000;
        transform: translateX(-3px);
    }
    .alert-success {
        background: #cbffd7;
        color: #155724;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
    @media (max-width: 768px) {
        .post-header h1 { font-size: 24px; }
        .post-content { padding: 25px; font-size: 16px; }
        .comment-text { margin-left: 0; margin-top: 10px; }
    }
</style>

<div class="post-container">
    <a href="<?= BASE_PATH ?>/?action=home" class="back-btn">← Назад к статьям</a>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <div class="post-header">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <div class="post-meta">
            <span>✍️ Автор: <?= htmlspecialchars($post['author_name']) ?></span>
            <span>👁️ <?= $post['views_count'] ?> просмотров</span>
            <span>📅 <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></span>
        </div>
    </div>
    
    <!-- СОДЕРЖАНИЕ СТАТЬИ — ТЕПЕРЬ ВИДНО! -->
    <div class="post-content">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>
    
    <div class="comments-section">
        <div class="comments-header">
            <h3>💬 Комментарии</h3>
            <span class="comments-count"><?= count($comments) ?></span>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="comment-form">
                <form method="POST" action="<?= BASE_PATH ?>/?action=add-comment&id=<?= $post['id'] ?>">
                    <textarea name="content" placeholder="Написать комментарий..." rows="3" required></textarea>
                    <button type="submit">✉️ Отправить комментарий</button>
                </form>
            </div>
        <?php else: ?>
            <div class="empty-comments">
                <p>🔐 <a href="<?= BASE_PATH ?>/?action=login">Войдите</a>, чтобы оставить комментарий</p>
            </div>
        <?php endif; ?>
        
        <?php if (empty($comments)): ?>
            <div class="empty-comments">
                <p>💭 Пока нет комментариев. Будьте первым!</p>
            </div>
        <?php else: ?>
            <div class="comments-list">
                <?php foreach($comments as $comment): 
                    $canDelete = false;
                    if (isset($_SESSION['user_id'])) {
                        $isCommentAuthor = ($_SESSION['user_id'] == $comment['user_id']);
                        $isAdmin = ($_SESSION['role'] === 'admin');
                        $isPostAuthor = (isset($post['author_id']) && $_SESSION['user_id'] == $post['author_id']);
                        $canDelete = ($isCommentAuthor || $isAdmin || $isPostAuthor);
                    }
                ?>
                    <div class="comment-item" id="comment-<?= $comment['id'] ?>">
                        <div class="comment-header-row">
                            <div class="comment-author">
                                <div class="comment-avatar">
                                    <?= mb_substr($comment['user_name'], 0, 1) ?>
                                </div>
                                <div class="comment-info">
                                    <div class="comment-name"><?= htmlspecialchars($comment['user_name']) ?></div>
                                    <div class="comment-date"><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></div>
                                </div>
                            </div>
                            
                            <?php if ($canDelete): ?>
                                <a href="<?= BASE_PATH ?>/?action=delete-comment&id=<?= $comment['id'] ?>" 
                                   class="delete-comment-btn"
                                   onclick="return confirm('🗑️ Удалить этот комментарий? Действие необратимо.');">
                                   🗑️ Удалить
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="comment-text">
                            <?= nl2br(htmlspecialchars($comment['content'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>