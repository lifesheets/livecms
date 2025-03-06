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

