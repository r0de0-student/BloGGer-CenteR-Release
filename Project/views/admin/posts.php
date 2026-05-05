<?php include __DIR__ . '/../layout.php'; ?>

<div class="admin-container">
    <div class="admin-header">
        <h1>📄 Модерация статей</h1>
        <p>Управление статьями пользователей (просмотр и удаление)</p>
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
                <th>Заголовок</th>
                <th>Автор</th>
                <th>Просмотры</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($posts as $post): ?>
                <tr>
                    <td><?= $post['id'] ?></td>
                    <td><a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $post['id'] ?>" style="color: #2c3e50; text-decoration: none;"><?= htmlspecialchars($post['title']) ?></a></td>
                    <td><?= htmlspecialchars($post['author_name']) ?></td>
                    <td><?= $post['views_count'] ?></td>
                    <td><?= date('d.m.Y', strtotime($post['created_at'])) ?></td>
                    <td>
                        <a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $post['id'] ?>" class="btn btn-sm">👁️ Просмотр</a>
                        <a href="<?= BASE_PATH ?>/?action=admin-delete-post&id=<?= $post['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Удалить статью «<?= htmlspecialchars($post['title']) ?>»?')">
                           🗑️ Удалить
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../footer.php'; ?>