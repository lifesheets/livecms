<?php

# Основний файл керування та виклику сторінки
# Торкатися категорично заборонено цей файл

use livecms\direct;

$baseUrl = direct::get('base');
$filePath = direct::get('path');
$subPath = direct::get('sub');
$currentSection = direct::get('section');

# Перевірка на наявність небезпечних параметрів у URL
if (str_contains(REQUEST_URI, 'base=') ||
    str_contains(REQUEST_URI, 'path=') ||
    str_contains(REQUEST_URI, 'sub=') ||
    str_contains(REQUEST_URI, 'section=')) {
    redirect('/');
}

# Формуємо шлях до головного файлу
$mainFile = ROOT_DIR . "/nucleus/modules/main.php";

# Перевіряємо існування та підключаємо відповідний файл
if (!direct::e_dir("/$baseUrl/")) {
    require_once $mainFile;
    return;
}

$paths = [
    "/$baseUrl/$filePath/$subPath/$currentSection.php",
    "/$baseUrl/$filePath/$currentSection/index.php",
    "/$baseUrl/$filePath/$currentSection.php",
    "/$baseUrl/$filePath/index.php",
    "/$baseUrl/$currentSection.php",
    "/$baseUrl/index.php"
];

foreach ($paths as $path) {
    if (direct::e_file($path)) {
        require_once ROOT_DIR . $path;
        return;
    }
}

require_once $mainFile;
