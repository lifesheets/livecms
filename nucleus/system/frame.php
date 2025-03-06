<?php

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
    require ROOT_DIR . (isPanel() ? '/styling/backend/web/header.php' : '/styling/frontend/web/header.php');
}

