<?php include __DIR__ . '/../layout.php'; ?>

<style>
    .hero-section {
        background: #007bff;
        border-radius: 30px;
        padding: 50px;
        color: white;
        margin-bottom: 40px;
        text-align: center;
    }
    .hero-section h1 {
        font-size: 42px;
        margin-bottom: 20px;
        color: white;
        border-left: none;
    }
    .hero-section p {
        font-size: 18px;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    .feature-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s;
        border: 1px solid #e0e0e0;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .feature-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    .feature-card h3 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .feature-card p {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .rules-section {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 40px;
        border-left: 4px solid #667eea;
    }
    .rules-section h2 {
        color: #2c3e50;
        margin-bottom: 20px;
        font-size: 24px;
    }
    .rules-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 15px;
        list-style: none;
        padding: 0;
    }
    .rules-list li {
        padding: 10px 0;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #555;
        border-bottom: 1px solid #e0e0e0;
    }
    .rules-list li:before {
        content: "📌";
        font-size: 16px;
    }
    
    .posts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .posts-header h2 {
        margin: 0;
        color: #2c3e50;
    }
    .create-post-link {
        background: #667eea;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        transition: 0.3s;
    }
    .create-post-link:hover {
        background: #5a67d8;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .hero-section { padding: 30px 20px; }
        .hero-section h1 { font-size: 28px; }
        .features { grid-template-columns: 1fr; }
        .rules-list { grid-template-columns: 1fr; }
    }
</style>

<!-- ПРИВЕТСТВЕННЫЙ БЛОК -->
<div class="hero-section">
    <h1>📝 Добро пожаловать в BloGGing CenteR</h1>
    <p>Платформа для ведения тематических блогов, где авторы делятся мыслями, а читатели находят интересные статьи и единомышленников.</p>
</div>

<!-- ПРЕИМУЩЕСТВА / ВОЗМОЖНОСТИ -->
<div class="features">
    <div class="feature-card">
        <div class="feature-icon">✍️</div>
        <h3>Создавайте блоги</h3>
        <p>Зарегистрируйтесь и создайте свой собственный блог. Делитесь мыслями, идеями и опытом.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">📖</div>
        <h3>Читайте статьи</h3>
        <p>Откройте для себя множество интересных статей от авторов со всего мира.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">💬</div>
        <h3>Общайтесь</h3>
        <p>Комментируйте статьи, обсуждайте темы, находите единомышленников.</p>
    </div>
</div>

<!-- ПРАВИЛА ПЛАТФОРМЫ -->
<div class="rules-section">
    <h2>📋 Правила платформы</h2>
    <ul class="rules-list">
        <li>Будьте вежливы и уважайте других пользователей</li>
        <li>Публикуйте только уникальный и качественный контент</li>
        <li>Вы можете редактировать и удалять свои статьи и комментарии</li>
        <li>Администратор имеет право блокировать нарушителей</li>
        <li>Авторы могут удалять комментарии под своими статьями</li>
        <li>Запрещён спам, оскорбления и нецензурная лексика</li>
    </ul>
</div>

<!-- СПИСОК СТАТЕЙ -->
<div class="posts-header">
    <h2>📰 Все статьи</h2>
    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'author'): ?>
        <a href="<?= BASE_PATH ?>/?action=create-post" class="create-post-link">➕ Написать статью</a>
    <?php endif; ?>
</div>

<?php if (empty($posts)): ?>
    <div class="empty-state" style="text-align: center; padding: 60px; color: #999;">
        <p>📭 Пока нет статей. Будьте первым!</p>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'author'): ?>
            <a href="<?= BASE_PATH ?>/?action=create-post" class="btn">➕ Создать первую статью</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php foreach($posts as $post): ?>
        <div class="post-card">
            <h3><a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
            <div class="post-meta" style="margin-bottom: 10px; font-size: 13px; color: #7f8c8d;">
                <span>✍️ <?= htmlspecialchars($post['author_name']) ?></span>
                <span>👁️ <?= $post['views_count'] ?> просмотров</span>
                <span>📅 <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></span>
            </div>
            <p style="color: #555;"><?= htmlspecialchars(mb_substr($post['content'], 0, 150)) ?>...</p>
            <a href="<?= BASE_PATH ?>/?action=view-post&id=<?= $post['id'] ?>" class="btn btn-sm">Читать далее →</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../footer.php'; ?>