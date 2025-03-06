<?php

use livecms\direct;

/**
 * Встановлення заголовків безпеки та підключення відповідного хедера
 * Захист від XSS та MIME sniffing
 */

function livecms_header(): void {
    // Захист від XSS атак
    header('X-XSS-Protection: 1; mode=block');
    // Запобігає MIME sniffing
    header('X-Content-Type-Options: nosniff');
    // Визначаємо, який хедер підключати: бекенд чи фронтенд
    require ROOT_DIR . (isPanel() ? '/styling/dashboard/header.php' : '/styling/dashboard/header.php');
}

/**
 * Підключення відповідного футера та завершення виводу буферу
 * @param int $exit - якщо 0, виконується вихід з програми
 */

function livecms_footer(int $exit = 0): void {
    // Визначаємо, який футер підключати: бекенд чи фронтенд
    require ROOT_DIR . (isPanel() ? '/styling/dashboard/footer.php' : '/styling/dashboard/footer.php');
    // Завершуємо вивід буферу
    ob_end_flush();
    // Завершуємо виконання скрипта
    if ($exit === 0) exit;
}
