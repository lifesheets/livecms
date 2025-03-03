<?php

namespace livecms;

define('DB_HOST', 'mariadb-10.2');
define('DB_NAME', 'livecms');
define('DB_USER', 'root');
define('DB_PASSWORD', 'a');

/*
-------------------------------
Клас для роботи з базою даних
-------------------------------
*/

class db {

    # Об'єкти PDO для роботи з базою даних
    public static $DB = null;
    public static $ST = null;

    # SQL запит
    public $QUERY = '';

    /**
     * Перевірка підключення до бази даних
     *
     * Метод намагається підключитись до бази даних, використовуючи надані параметри.
     * Якщо підключення успішне, повертається true, інакше - текст помилки.
     *
     * @param string $host Хост бази даних
     * @param string $name Назва бази даних
     * @param string $user Користувач бази даних
     * @param string $pass Пароль користувача
     * @return bool|string True якщо підключення успішне, або рядок помилки
     */

    public static function checkConnection($host = "localhost", $name = "livecms", $user = "root", $pass = "usbw") {
        try {
            # Пробуємо підключитись до бази даних
            $pdo = new \PDO('mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8', $user, $pass, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            # Якщо підключення пройшло успішно
            return true;
        } catch (\PDOException $e) {
            # Якщо не вдалося підключитись, виводимо помилку
            return $e->getMessage();
        }
    }

}
