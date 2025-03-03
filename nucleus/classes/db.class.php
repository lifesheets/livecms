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

    /**
     * Підключення до бази даних
     *
     * Цей метод виконує перевірку підключення до бази даних за допомогою методу
     * checkConnection та, якщо підключення успішне, зберігає об'єкт PDO для подальшого використання.
     * Якщо підключення не вдалось, виводиться помилка.
     *
     * @param int $status Статус підключення (за замовчуванням 1, не використовується в даному методі)
     * @return \PDO|null Об'єкт PDO при успішному підключенні або null у разі помилки
     */

    public static function connect($status = 1) {
        # Використовуємо перевірку підключення з checkConnection
        $connectionCheck = self::checkConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);

        if ($connectionCheck === true) {
            # Якщо підключення пройшло успішно, виконуємо подальші дії
            if (!self::$DB) {
                self::$DB = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));
                self::$DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            }
        } else {
            # Якщо перевірка підключення не пройшла, виводимо помилку
            echo "Помилка підключення до бази даних: " . $connectionCheck;
        }

        return self::$DB;
    }
}
