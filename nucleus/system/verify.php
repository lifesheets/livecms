<?php

/**
* Перевірка, чи знаходиться користувач у панелі управління
* @return bool Повертає true, якщо користувач у панелі, інакше false
*/

function isPanel(): bool {
    return str_contains(REQUEST_URI, '/admin/');
}
