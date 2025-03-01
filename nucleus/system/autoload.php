<?php

/**
 * Автоматичне завантаження класів та функцій
 */

# Завантаження функцій
$functions_dir = ROOT_DIR . '/nucleus/functions';
if (is_dir($functions_dir)) {
    foreach (glob($functions_dir . '/*.php') as $file_function) {
        require_once $file_function;
    }
}

# Завантаження класів
spl_autoload_register(function ($name_class) {
    # Заміна зворотних слешів на прямі для сумісності з файловою системою
    $name_class = str_replace('\\', '/', $name_class);

    # Видаляємо префікс 'livecms/' з шляху, якщо він вже є
    if (strpos($name_class, 'livecms/') === 0) {
        $name_class = substr($name_class, strlen('livecms/'));
    }

    # Формуємо повний шлях до файлу
    $class_file = ROOT_DIR . '/nucleus/classes/' . $name_class . '.class.php';

    # Перевіряємо, чи існує файл
    if (is_file($class_file)) {
        require_once $class_file;
    } else {
        # Для відладки
        echo "Клас не знайдено: " . $name_class . '<br>' . $class_file;
    }
});
