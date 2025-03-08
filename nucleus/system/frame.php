<?php

/**
 * Встановлення заголовків безпеки та підключення відповідного хедера
 * Захист від XSS атак та MIME sniffing
 */

function livecms_header(): void {
    header('X-XSS-Protection: 1; mode=block');
    header('X-Content-Type-Options: nosniff');
    $module = get_module_from_url(REQUEST_URI);
    $headerPath = ROOT_DIR . "/modules/$module/template/header.php";
    error_log("Header path: $headerPath");
    file_exists($headerPath) ? require_once $headerPath : error_log("Header not found for module: $module");
}

/**
 * Підключення відповідного футера та завершення виводу буферу
 * @param int $exit - якщо 0, виконується вихід з програми
 */

function livecms_footer(int $exit = 0): void {
    $module = get_module_from_url(REQUEST_URI);
    $footerPath = ROOT_DIR . "/modules/$module/template/footer.php";
    error_log("Footer path: $footerPath");
    file_exists($footerPath) ? require_once $footerPath : error_log("Footer not found for module: $module");
    ob_end_flush();
    if (!$exit) exit;
}

/**
 * Функція для автоматичного визначення модуля з URL
 * Визначає перший сегмент шляху як ім’я модуля
 * @param string $url - URL, з якого буде отримано модуль
 * @return string - ім’я модуля
 */

function get_module_from_url(string $url): string {
    return strtok(parse_url($url, PHP_URL_PATH), '/');
}
