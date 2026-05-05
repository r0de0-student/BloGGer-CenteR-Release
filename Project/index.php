<?php
session_start();
define('BASE_PATH', '/blogger-center/Project');

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Blog.php';
require_once __DIR__ . '/models/Post.php';
require_once __DIR__ . '/models/Comment.php';
require_once __DIR__ . '/models/Subscription.php';
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/PostController.php';
require_once __DIR__ . '/controllers/BlogController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/ReportController.php';
require_once __DIR__ . '/controllers/ExportController.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    // ========== ГОЛОВНАЯ СТРАНИЦА ==========
    case 'home':
        $controller = new HomeController($pdo);
        $controller->index();
        break;

    // ========== ПОИСК ==========
    case 'search':
        $controller = new HomeController($pdo);
        $controller->search();
        break;

    // ========== АУТЕНТИФИКАЦИЯ ==========
    case 'login':
        $controller = new AuthController($pdo);
        $controller->loginForm();
        break;
    case 'do-login':
        $controller = new AuthController($pdo);
        $controller->login();
        break;
    case 'register':
        $controller = new AuthController($pdo);
        $controller->registerForm();
        break;
    case 'do-register':
        $controller = new AuthController($pdo);
        $controller->register();
        break;
    case 'logout':
        $controller = new AuthController($pdo);
        $controller->logout();
        break;

    // ========== СТАТЬИ И КОММЕНТАРИИ ==========
    case 'view-post':
        $controller = new PostController($pdo);
        $controller->view($_GET['id'] ?? 0);
        break;
    case 'create-post':
        $controller = new PostController($pdo);
        $controller->createForm();
        break;
    case 'do-create-post':
        $controller = new PostController($pdo);
        $controller->create();
        break;
    case 'edit-post':
        $controller = new PostController($pdo);
        $controller->editForm($_GET['id'] ?? 0);
        break;
    case 'do-update-post':
        $controller = new PostController($pdo);
        $controller->update($_GET['id'] ?? 0);
        break;
    case 'delete-post':
        $controller = new PostController($pdo);
        $controller->delete($_GET['id'] ?? 0);
        break;
    case 'add-comment':
        $controller = new PostController($pdo);
        $controller->addComment($_GET['id'] ?? 0);
        break;
    case 'delete-comment':
        $controller = new PostController($pdo);
        $controller->deleteComment($_GET['id'] ?? 0);
        break;

    // ========== БЛОГИ ==========
    case 'my-blog':
        $controller = new BlogController($pdo);
        $controller->myBlog();
        break;
    case 'create-blog':
        $controller = new BlogController($pdo);
        $controller->createForm();
        break;
    case 'do-create-blog':
        $controller = new BlogController($pdo);
        $controller->create();
        break;
    case 'edit-blog':
        $controller = new BlogController($pdo);
        $controller->editForm();
        break;
    case 'do-update-blog':
        $controller = new BlogController($pdo);
        $controller->update();
        break;
    case 'all-blogs':
        $controller = new BlogController($pdo);
        $controller->allBlogs();
        break;

    // ========== АДМИН-ПАНЕЛЬ ==========
    case 'admin-users':
        $adminController = new AdminController($pdo);
        $adminController->users();
        break;
    case 'admin-toggle-block':
        $adminController = new AdminController($pdo);
        $adminController->toggleBlock($_GET['id'] ?? 0);
        break;
    case 'admin-update-role':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController = new AdminController($pdo);
            $adminController->updateRole($_POST['user_id'] ?? 0, $_POST['role'] ?? 'author');
        }
        break;
    case 'admin-posts':
        $adminController = new AdminController($pdo);
        $adminController->posts();
        break;
    case 'admin-delete-post':
        $adminController = new AdminController($pdo);
        $adminController->deletePost($_GET['id'] ?? 0);
        break;
    case 'admin-comments':
        $adminController = new AdminController($pdo);
        $adminController->comments();
        break;
    case 'admin-delete-comment':
        $adminController = new AdminController($pdo);
        $adminController->deleteComment($_GET['id'] ?? 0);
        break;

    // ========== ОТЧЁТЫ ==========
    case 'reports':
        $controller = new ReportController($pdo);
        $controller->index();
        break;
    case 'report-users':
        $controller = new ReportController($pdo);
        $controller->users();
        break;
    case 'report-posts':
        $controller = new ReportController($pdo);
        $controller->posts();
        break;
    case 'report-top-blogs':
        $controller = new ReportController($pdo);
        $controller->topBlogs();
        break;
    case 'author-stats':
        $controller = new ReportController($pdo);
        $controller->authorStats();
        break;

    // ========== ЭКСПОРТ ==========
    case 'export-users-excel':
        $controller = new ExportController($pdo);
        $controller->usersToExcel();
        break;
    case 'export-users-word':
        $controller = new ExportController($pdo);
        $controller->usersToWord();
        break;
    case 'export-posts-excel':
        $controller = new ExportController($pdo);
        $controller->postsToExcel();
        break;
    case 'export-posts-word':
        $controller = new ExportController($pdo);
        $controller->postsToWord();
        break;
    case 'export-top-blogs-excel':
        $controller = new ExportController($pdo);
        $controller->topBlogsToExcel();
        break;
    case 'export-top-blogs-word':
        $controller = new ExportController($pdo);
        $controller->topBlogsToWord();
        break;

    // ========== ОШИБКИ ==========
    case 'access-denied':
        require_once __DIR__ . '/views/error/access-denied.php';
        break;

    // ========== ПО УМОЛЧАНИЮ ==========
    default:
        header('Location: ' . BASE_PATH . '/?action=home');
        break;
}
?>