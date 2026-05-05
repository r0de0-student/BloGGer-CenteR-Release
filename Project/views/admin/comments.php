<?php include __DIR__ . '/../layout.php'; ?>

<div class="admin-container">
    <div class="admin-header">
        <h1>💬 Модерация комментариев</h1>
        <p>Управление комментариями пользователей (просмотр и удаление)</p>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Автор</th>
                <th>Комментарий</th>
                <th>Статья</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($comments as $comment): ?>
                <tr>
                    <td><?= $comment['id'] ?></td>
                    <td><?= htmlspecialchars($comment['user_name']) ?></td>
                    <td><?= htmlspecialchars(mb_substr($comment['content'], 0, 60)) ?><?= mb_strlen($comment['content']) > 60 ? '...' : '' ?></td>
                    <td><a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $comment['post_id'] ?>"><?= htmlspecialchars($comment['post_title']) ?></a></td>
                    <td><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></td>
                    <td>
                        <a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $comment['post_id'] ?>" class="btn btn-sm">👁️ К статье</a>
                        <a href="<?= BASE_PATH ?>/?action=admin-delete-comment&id=<?= $comment['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Удалить комментарий пользователя <?= htmlspecialchars($comment['user_name']) ?>?')">
                           🗑️ Удалить
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../footer.php'; ?>