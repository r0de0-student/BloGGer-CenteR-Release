<?php include __DIR__ . '/../layout.php'; ?>

<div class="search-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 40px; color: white; margin-bottom: 30px; text-align: center;">
    <h1 style="color: white; border-left: none;">🔍 Поиск статей</h1>
    <p>Найдите интересующие вас статьи</p>
</div>

<div class="search-form">
    <form method="GET" action="<?= BASE_PATH ?>/index.php" style="display: flex; gap: 15px; flex-wrap: wrap;">
        <input type="hidden" name="action" value="search">
        <input type="text" name="q" placeholder="Введите слово для поиска..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="flex: 1;">
        <button type="submit" class="btn">🔍 Найти</button>
    </form>
</div>

<?php if (isset($_GET['q']) && !empty($_GET['q'])): ?>
    <h2>Результаты поиска: "<?= htmlspecialchars($_GET['q']) ?>"</h2>
    
    <?php if (empty($posts)): ?>
        <div style="text-align: center; padding: 60px; color: #999;">
            <p>😕 Ничего не найдено по вашему запросу</p>
            <p>Попробуйте изменить ключевые слова</p>
        </div>
    <?php else: ?>
        <p>Найдено статей: <strong><?= count($posts) ?></strong></p>
        
        <?php foreach($posts as $post): ?>
            <div class="post-card">
                <h3><a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                <div class="post-meta">
                    <span>✍️ <?= htmlspecialchars($post['author_name']) ?></span>
                    <span>👁️ <?= $post['views_count'] ?> просмотров</span>
                    <span>📅 <?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
                </div>
                <p><?= htmlspecialchars(mb_substr($post['content'], 0, 200)) ?>...</p>
                <a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $post['id'] ?>" class="btn btn-sm">Читать далее →</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <div style="text-align: center; padding: 60px; color: #999;">
        <p>💡 Введите ключевое слово в поле поиска выше</p>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../footer.php'; ?>