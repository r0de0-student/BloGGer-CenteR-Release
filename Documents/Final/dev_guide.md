# 👨‍💻 Руководство разработчика


## Содержание

1. [Структура проекта](#структура-проекта)
2. [Архитектура и паттерны](#архитектура-и-паттерны)
3. [Технологический стек](#технологический-стек)
4. [Возможные ошибки и их решение](#возможные-ошибки-и-их-решение)
5. [Работа с Docker](#работа-с-docker)
6. [Безопасность](#безопасность)
7. [Примеры кода](#примеры-кода)
8. [Полезные команды](#полезные-команды)


## Структура проекта

![Структура проекта](../Assets/structura.png)


### Поток запроса пользователя

| Шаг | Действие | Пример |
|-----|----------|--------|
| 1 | Пользователь вводит URL | `/?action=view-post&id=1` |
| 2 | **index.php** (Front Controller) анализирует `action` | `$action = 'view-post'` |
| 3 | Вызывается **PostController->view(1)** | `(new PostController($pdo))->view(1)` |
| 4 | Контроллер запрашивает данные у **модели** | `$post = $this->postModel->getById(1)` |
| 5 | **Модель** выполняет SQL запрос | `SELECT * FROM posts WHERE id = 1` |
| 6 | **База данных** возвращает результат | Массив с данными статьи |
| 7 | **Контроллер** передаёт данные в **представление** | `require_once 'views/posts/view.php'` |
| 8 | **Представление** генерирует HTML | Страница с отображением статьи |
| 9 | **Браузер** получает и отображает HTML | Готовая страница |

### Единая точка входа (Front Controller)

**Файл:** [`index.php`](../../index.php)

```php
<?php
session_start();
define('BASE_PATH', '/blogger-center/Project');

// Подключение всех необходимых файлов
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/PostController.php';
// ... остальные контроллеры

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        (new HomeController($pdo))->index();
        break;
    case 'view-post':
        (new PostController($pdo))->view($_GET['id'] ?? 0);
        break;
    case 'delete-comment':
        (new PostController($pdo))->deleteComment($_GET['id'] ?? 0);
        break;
    case 'admin-users':
        (new AdminController($pdo))->users();
        break;
    // ... остальные маршруты
    default:
        header('Location: ' . BASE_PATH . '/?action=home');
        break;
}
?>
```


## Технологический стек

![Технологический стек](../Assets/tex.png)

## Возможные ошибки и их решение

1. Ошибка подключения к БД
Решение:
```bash
# Проверить статус контейнеров
docker compose ps
# Перезапустить MySQL
docker compose restart db
# Проверить подключение из контейнера
docker exec -it blogger_web php -r "new PDO('mysql:host=db;dbname=blogger_center', 'root', 'root'); echo 'OK';"
```

2. Ошибка 404 при переходе по ссылкам
Решение:
```php
// Проверьте путь в index.php
define('BASE_PATH', '/blogger-center/Project');  // Правильно
```

3. Ошибка кодировки (кракозябры вместо русских букв)
Решение:
```sql
ALTER DATABASE blogger_center CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE posts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE comments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Ошибка при удалении комментария
Решение:
```php
$isCommentAuthor = ($comment['user_id'] == $_SESSION['user_id']);
$isAdmin = ($_SESSION['role'] === 'admin');
$isPostAuthor = ($_SESSION['user_id'] == $post['author_id']);

if ($isCommentAuthor || $isAdmin || $isPostAuthor) {
    $this->commentModel->delete($commentId);
    $_SESSION['success'] = "Комментарий удалён";
} else {
    $_SESSION['error'] = "У вас нет прав";
}
```

5. Порт 8080 уже занят
Решение:
```yaml
ports:
  - "8082:80"   # вместо 8080
```

## Работа с Docker

![Архитектура Docker](../Assets/Docker.png)

Файл docker-compose.yml:
```yaml
version: '3.8'

services:
  web:
    build: .
    container_name: blogger_web
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_NAME=blogger_center
      - DB_USER=root
      - DB_PASS=root

  db:
    image: mysql:8.0
    container_name: blogger_db
    restart: always
    environment:
      MYSQL_DATABASE: blogger_center
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3307:3306"
    volumes:
      - db_data:/var/lib/mysql
      - ./database.sql:/docker-entrypoint-initdb.d/init.sql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: blogger_phpmyadmin
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "8081:80"
    depends_on:
      - db

volumes:
  db_data:
```

Полезные Docker команды:
```bash
# Запуск всех контейнеров
docker compose up -d --build
# Остановка всех контейнеров
docker compose down
# Остановка с удалением томов (очистка БД)
docker compose down -v
# Просмотр статуса
docker compose ps
# Просмотр логов
docker compose logs -f web
docker compose logs -f db
# Вход в контейнер для отладки
docker exec -it blogger_web bash
docker exec -it blogger_db bash
# Подключение к MySQL внутри контейнера
docker exec -it blogger_db mysql -u root -proot
```

Переменные окружения:
```php
// config/database.php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'blogger_center';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
```


### Безопасность

1. Защита от SQL-инъекций (PDO Prepare)
```php
// ❌ НЕПРАВИЛЬНО (уязвимо)
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

// ✅ ПРАВИЛЬНО (защищено)
$stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
```

2. Защита от XSS (межсайтовый скриптинг)
```php
// ❌ НЕПРАВИЛЬНО (уязвимо)
<h1><?= $post['title'] ?></h1>

// ✅ ПРАВИЛЬНО (защищено)
<h1><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h1>
```

3. Хэширование паролей
```php
// При регистрации
$hashed = password_hash($password, PASSWORD_BCRYPT);

// При входе
if (password_verify($password, $user['password'])) {
    // Успешный вход
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
}
```

4. Проверка прав доступа
```php
// controllers/AdminController.php
private function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        $_SESSION['error'] = "Доступ запрещён";
        header('Location: ' . BASE_PATH . '/?action=login');
        exit;
    }
}

// Использование
public function users() {
    $this->checkAdmin();  // Только админ может зайти
    $users = $this->userModel->getAll();
    require_once __DIR__ . '/../views/admin/users.php';
}
```

### Примеры кода

![Контроллеры](C:\xampp\htdocs\blogger-center\Project\controllers)
![Модели](C:\xampp\htdocs\blogger-center\Project\models)
![Представления](C:\xampp\htdocs\blogger-center\Project\views)

### Полезные команды

Git командды:
```bash
# Проверка статуса
git status
# Добавление всех изменений
git add .
# Создание коммита
git commit -m "feat: add new feature"
# Отправка в репозиторий
git push origin main
# Просмотр истории
git log --oneline -10
# Создание тега
git tag -a v1.0.0 -m "Release version"
git push origin v1.0.0
```

Docker команды:
```bash
# Запуск
docker compose up -d --build
# Остановка
docker compose down
# Просмотр статуса
docker compose ps
# Логи
docker compose logs -f web
# Вход в контейнер
docker exec -it blogger_web bash
docker exec -it blogger_db bash
# Очистка (удалить всё)
docker compose down -v
docker system prune -a
```


### Контакты
При возникновении вопросов обращайтесь к разработчику:
Автор: Гармонов Родион Андреевич (ПИб-242)
GitHub: r0de0-student

---

Документация актуальна для версии 1.0.0