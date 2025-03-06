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
 * Функція визначення версії сайту (мобільна чи десктопна)
 *
 * @return bool - true, якщо мобільний пристрій, інакше false
 */

function type_version(): bool {
    $mobile_array = [
        'ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce',
        'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson',
        'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola',
        'smartphone', 'blackberry', 'playstation portable', 'tablet browser'
    ];
    $agent = strtolower(BROWSER);
    return array_any($mobile_array, fn ($value) => str_contains($agent, $value));
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
 * Отримання значення з $_COOKIE
 * @param string $name - Назва cookie
 * @return mixed - Значення або false, якщо параметр відсутній
 */

function cookie(string $name): mixed {
    return $_COOKIE[$name] ?? false ? remove_script($_COOKIE[$name]) : false;
}

/**
 * Робота з $_SESSION
 * @param string $data - Ключ у сесії
 * @param mixed $param - Значення (за замовчуванням 'no_data' - тільки читання)
 * @return mixed - Значення або false, якщо параметр відсутній
 */

function session(string $data, mixed $param = 'no_data'): mixed {
    if ($param === 'no_data') {
        return $_SESSION[$data] ?? false ? (!is_array($_SESSION[$data]) ? remove_script($_SESSION[$data]) : $_SESSION[$data]) : false;
    }
    return $_SESSION[$data] = $param;
}

/**
 * Робота з конфігураційним масивом
 * @param string $data - Ключ у масиві $config
 * @param mixed|null $param - Значення (якщо null - тільки читання)
 * @return mixed - Значення або оновлений параметр
 */

function config(string $data, mixed $param = null): mixed {
    global $config;
    return $param === null ? _filter($config[$data] ?? null) : ($config[$data] = $param);
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
