<!DOCTYPE html>
<html>
<head>
    <title>BloGGing CenteR</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f7fa; }
        
        /* Навигация */
        nav { background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%); color: white; padding: 15px 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        nav a { color: white; text-decoration: none; margin-right: 25px; font-size: 14px; transition: 0.3s; }
        nav a:hover { color: #3498db; text-decoration: none; transform: translateY(-2px); display: inline-block; }
        nav .float-right { float: right; }
        
        /* Контейнер */
        .container { max-width: 1200px; margin: 30px auto; padding: 30px; background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); min-height: 500px; }
        
        /* Заголовки */
        h2 { color: #34495e; margin: 20px 0 15px; font-size: 22px; }
        h3 { color: #2c3e50; margin: 10px 0; }
        
        /* Карточки статей */
        .post-card { background: white; border: 1px solid #e0e0e0; border-radius: 16px; padding: 20px; margin-bottom: 20px; transition: all 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .post-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-color: #3498db; }
        .post-card h3 { margin-bottom: 10px; }
        .post-card h3 a { color: #2c3e50; text-decoration: none; transition: 0.3s; }
        .post-card h3 a:hover { color: #3498db; }
        .post-meta { color: #7f8c8d; font-size: 13px; margin-bottom: 10px; display: flex; gap: 15px; flex-wrap: wrap; }
        
        /* Кнопки */
        .btn { display: inline-block; padding: 8px 18px; background: #3498db; color: white; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; transition: all 0.3s; }
        .btn:hover { background: #2980b9; transform: translateY(-2px); }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        .btn-warning { background: #f39c12; color: #333; }
        .btn-warning:hover { background: #e67e22; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }
        
        /* Формы */
        form input, form textarea, form select { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; transition: 0.3s; }
        form input:focus, form textarea:focus, form select:focus { outline: none; border-color: #3498db; box-shadow: 0 0 0 3px rgba(52,152,219,0.1); }
        form button { padding: 12px 25px; background: #3498db; color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 14px; transition: 0.3s; }
        form button:hover { background: #2980b9; transform: translateY(-2px); }
        
        /* Таблицы */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background: #f8f9fa; font-weight: 600; color: #2c3e50; }
        tr:hover { background: #f8f9fa; }
        
        /* Бейджи */
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-admin { background: #e74c3c; color: white; }
        .badge-author { background: #27ae60; color: white; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-blocked { background: #f8d7da; color: #721c24; }
        
        /* Комментарии */
        .comment { border-left: 3px solid #3498db; padding-left: 15px; margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 12px; }
        
        /* Уведомления */
        .alert-success { background: linear-gradient(135deg, #d4edda 0%, #a3dfb0 100%); color: #155724; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #28a745; }
        .alert-error { background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%); color: #721c24; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #dc3545; }
        
        /* Футер */
        footer { text-align: center; padding: 25px; color: #7f8c8d; font-size: 13px; border-top: 1px solid #ecf0f1; margin-top: 40px; }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .container { padding: 20px; margin: 15px; }
            nav a { margin-right: 12px; font-size: 12px; }
            .post-meta { flex-direction: column; gap: 5px; }
            table, th, td { font-size: 12px; }
            th, td { padding: 8px 10px; }
        }
        
        /* Поиск */
        .search-form { background: #f8f9fa; padding: 25px; border-radius: 16px; margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap; }
        .search-form input { flex: 1; margin: 0; }
        .search-form button { margin: 0; }
        
        /* Админ-панель */
        .admin-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; color: white; margin-bottom: 30px; }
        .admin-header h1 { margin: 0 0 10px; border-left: none; padding-left: 0; color: white; }
        .admin-header p { margin: 0; opacity: 0.9; }
        .admin-table { width: 100%; background: white; border-radius: 20px; overflow: hidden; }
        .admin-table th { background: #f8f9fa; padding: 15px; font-weight: bold; color: #2c3e50; }
        .admin-table td { padding: 15px; border-bottom: 1px solid #f0f0f0; }
        .admin-table tr:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <nav>
        <a href="<?= BASE_PATH ?>/?action=home">🏠 Главная</a>
        <a href="<?= BASE_PATH ?>/?action=search">🔍 Поиск</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_PATH ?>/?action=my-blog">📝 Мой блог</a>
            <a href="<?= BASE_PATH ?>/?action=reports">📊 Отчёты</a>
            
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="<?= BASE_PATH ?>/?action=admin-users">👥 Пользователи</a>
                <a href="<?= BASE_PATH ?>/?action=admin-posts">📄 Статьи</a>
                <a href="<?= BASE_PATH ?>/?action=admin-comments">💬 Комментарии</a>
            <?php endif; ?>
            
            <a href="<?= BASE_PATH ?>/?action=logout" class="float-right">🚪 Выйти (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
        <?php else: ?>
            <a href="<?= BASE_PATH ?>/?action=login" class="float-right">🔐 Вход</a>
            <a href="<?= BASE_PATH ?>/?action=register" class="float-right" style="margin-right: 20px;">📝 Регистрация</a>
        <?php endif; ?>
    </nav>
    
    <div class="container">