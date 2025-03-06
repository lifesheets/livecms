<?php

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
 * Визначення поточного системного часу
 */

define('TM', time());

/**
 * Визначення імені файлу, до якого здійснюється звернення
 */

define('PHP_SELF', _filter($_SERVER['PHP_SELF']));

/**
 * Визначення домену сайту
 */

define('HTTP_HOST', _filter($_SERVER['HTTP_HOST']));

/**
 * Визначення імені сервера
 */

define('SERVER_NAME', _filter($_SERVER['SERVER_NAME']));

/**
 * Визначення реферера (звідки прийшов користувач)
 */

if (!empty($_SERVER['HTTP_REFERER'])) {
    define('HTTP_REFERER', _filter($_SERVER['HTTP_REFERER']));
} else {
    define('HTTP_REFERER', 'none');
}

/**
 * Визначення браузера користувача
 */

if (!empty($_SERVER['HTTP_USER_AGENT'])) {
    define('BROWSER', _filter($_SERVER["HTTP_USER_AGENT"]));
} else {
    define('BROWSER', 'none');
}

/**
 * Визначення IP-адреси користувача
 */

define('IP', _filter(filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP)));

/**
 * Визначення протоколу (HTTP або HTTPS)
 */

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    define('SCHEME', 'https://');
} else {
    define('SCHEME', 'http://');
}

