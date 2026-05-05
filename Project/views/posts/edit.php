<?php include __DIR__ . '/../layout.php'; ?>

<style>
    .edit-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        text-align: center;
    }
    .edit-header h1 {
        margin: 0;
        font-size: 28px;
    }
    .edit-form {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .edit-form input,
    .edit-form textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        margin-bottom: 15px;
        font-family: inherit;
    }
    .edit-form input:focus,
    .edit-form textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }
    .edit-form textarea {
        resize: vertical;
        min-height: 300px;
    }
    .form-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    .btn-save {
        background: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
    }
    .btn-save:hover {
        background: #218838;
        transform: translateY(-2px);
    }
    .btn-cancel {
        background: #6c757d;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.2s;
    }
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
</style>

<div class="edit-container">
    <div class="edit-header">
        <h1>✏️ Редактировать статью</h1>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <div class="edit-form">
        <form method="POST" action="<?= BASE_PATH ?>/?action=do-update-post&id=<?= $post['id'] ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" placeholder="Заголовок статьи" required>
            <textarea name="content" rows="15" placeholder="Содержание статьи..." required><?= htmlspecialchars($post['content']) ?></textarea>
            
            <div class="form-buttons">
                <button type="submit" class="btn-save">💾 Сохранить изменения</button>
                <a href="<?= BASE_PATH ?>/?action=my-blog" class="btn-cancel">❌ Отмена</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>