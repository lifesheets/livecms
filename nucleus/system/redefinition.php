<?php

/**
 * Видаляє потенційно небезпечний код (скрипти, вбудовані об'єкти тощо)
 * @param string|null $string Вхідний рядок
 * @return string Очищений рядок
 */

function remove_script(?string $string = null): string {
    # Якщо рядок порожній (null), повертаємо порожній рядок
    if ($string === null) {
        return '';
    }
    # Видаляємо невидимі символи керування
    $string = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F\\x7F]+/S', '', $string);
    # Небезпечні теги та події, які можуть містити шкідливий код
    $dangerous_tags = ['vbscript', 'expression', 'applet', 'xml', 'blink', 'embed', 'object', 'frameset', 'ilayer', 'layer', 'bgsound'];
    $dangerous_events = ['onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'];
    # Повертаємо очищений рядок без небезпечних елементів
    return str_ireplace(array_merge($dangerous_tags, $dangerous_events), '', $string);
}

/**
 * Фільтрує вхідні дані, захищаючи від XSS та SQL-ін'єкцій
 * @param string $data Вхідний рядок
 * @return string Очищений рядок
 */

function _filter(string $data): string {
    # Перевіряємо, що строка не порожня
    if ($data === '') {
        return '';
    }
    # Використовуємо htmlspecialchars для екранування спеціальних символів
    return remove_script(addslashes(htmlspecialchars($data, ENT_QUOTES, 'UTF-8')));
}

/**
 * Визначення повного URL-адресу запитуваної сторінки
 *
 * Використовує змінну сервера $_SERVER["REQUEST_URI"].
 * Якщо значення відсутнє або некоректне, використовується значення за замовчуванням ('/').
 * Значення фільтрується через функцію _filter() для запобігання потенційним атакам.
 */

if (!empty($_SERVER["REQUEST_URI"])) {
    define('REQUEST_URI', _filter($_SERVER["REQUEST_URI"]));
} else {
    define('REQUEST_URI', '/');
}

/**
 * Отримання значення з $_GET
 * @param string $data - Ключ у масиві $_GET
 * @param int $d - Якщо 0, то фільтруємо значення
 * @return mixed - Значення або false, якщо параметр відсутній
 */

function get(string $data, int $d = 0): mixed {
    return $_GET[$data] ?? false ? ($d === 0 ? remove_script($_GET[$data]) : $_GET[$data]) : false;
}

/**
 * Отримання значення з $_POST
 * @param string $data - Ключ у масиві $_POST
 * @param int $d - Якщо 0, то фільтруємо значення
 * @return mixed - Значення або false, якщо параметр відсутній
 */

function post(string $data, int $d = 0): mixed {
    return $_POST[$data] ?? false ? ($d === 0 ? remove_script($_POST[$data]) : $_POST[$data]) : false;
}

/**
 * Виконує редирект на вказану ссылку.
 *
 * @param string $url Ссилка для перенаправлення.
 * @param int $refresh Час затримки в секундах перед перенаправленням. За замовчуванням 0 — без затримки.
 * @return void
 */

function redirect(string $url, int $refresh = 0): void
{
    # Використовуємо оператор if для вибору між негайним редиректом і редиректом з затримкою
    $refresh <= 0
        ? header('Location: ' . $url)                       // Виконуємо редирект без затримки
        : header('Refresh: ' . $refresh . '; URL=' . $url); // Виконуємо редирект з затримкою
    # Завершуємо виконання скрипта після редиректу
    exit();
}
