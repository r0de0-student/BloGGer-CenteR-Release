<?php include __DIR__ . '/../layout.php'; ?>

<div class="admin-container">
    <div class="admin-header">
        <h1>👥 Управление пользователями</h1>
        <p>Просмотр, блокировка и управление ролями пользователей</p>
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
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php if ($user['role'] == 'admin'): ?>
                            <span class="badge badge-admin">👑 Админ</span>
                        <?php else: ?>
                            <span class="badge badge-author">✍️ Автор</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['is_blocked']): ?>
                            <span class="badge badge-blocked">🔒 Заблокирован</span>
                        <?php else: ?>
                            <span class="badge badge-active">✅ Активен</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="<?= BASE_PATH ?>/?action=admin-toggle-block&id=<?= $user['id'] ?>" 
                               class="btn <?= $user['is_blocked'] ? 'btn-success' : 'btn-danger' ?> btn-sm"
                               onclick="return confirm('<?= $user['is_blocked'] ? 'Разблокировать' : 'Заблокировать' ?> пользователя <?= htmlspecialchars($user['name']) ?>?')">
                                <?= $user['is_blocked'] ? '🔓 Разблокировать' : '🔒 Заблокировать' ?>
                            </a>
                            
                            <form method="POST" action="<?= BASE_PATH ?>/?action=admin-update-role" style="display: inline-block;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="role" class="btn-sm" style="padding: 5px 10px; border-radius: 8px;" onchange="this.form.submit()">
                                    <option value="author" <?= $user['role'] == 'author' ? 'selected' : '' ?>>📝 Автор</option>
                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>👑 Админ</option>
                                </select>
                            </form>
                        <?php else: ?>
                            <span class="badge" style="background:#ecf0f1; padding: 6px 12px;">⭐ Это вы</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../footer.php'; ?>