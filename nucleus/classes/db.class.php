<?php

namespace livecms;

define('DB_HOST', 'mariadb-10.2');
define('DB_NAME', 'livecms');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

/**
 * Клас для роботи з базою даних
 *
 * Цей клас містить методи для підключення до бази даних, виконання SQL запитів
 * та отримання результатів з бази даних.
 */

class db {
    # Об'єкти PDO для роботи з базою даних
    private static $DB = null;
    private static $ST = null;

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

    public static function checkConnection(string $host = DB_HOST, string $name = DB_NAME, string $user = DB_USER, string $pass = DB_PASSWORD): bool|string
    {
        try {
            # Пробуємо підключитись до бази даних
            $pdo = new \PDO('mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8', $user, $pass, [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
            ]);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            # Підключення успішне
            return true;
        } catch (\PDOException $e) {
            # Виводимо помилку
            return $e->getMessage();
        }
    }

    /**
     * Підключення до бази даних
     *
     * Цей метод встановлює підключення до бази даних, якщо воно ще не було створене,
     * і повертає об'єкт PDO для подальших операцій з базою даних.
     * Якщо підключення не вдається, виводиться повідомлення про помилку.
     *
     * @return \PDO|null Об'єкт PDO при успішному підключенні або null у разі помилки
     */

    public static function connect(): ?\PDO
    {
        # Перевірка підключення
        if (self::checkConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD) === true) {
            if (!self::$DB) {
                self::$DB = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD, [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"]);
                self::$DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            }
        } else {
            echo "Помилка підключення до бази даних.";
            exit();
        }
        return self::$DB;
    }

    /**
     * Отримання 1 рядка з таблиці
     *
     * Цей метод виконує SQL запит до бази даних і повертає перший рядок результату.
     * Якщо запит не знайде жодного рядка, метод поверне false або null.
     *
     * @param string $query SQL запит, який має бути виконаний
     * @param array $param Масив параметрів для запиту (за замовчуванням порожній масив)
     * @return array|null Масив з результатами першого рядка або null, якщо рядок не знайдений
     */

    public static function getString(string $query, array $param = []): ?array
    {
        if (self::connect()) {
            self::$ST = self::connect()->prepare($query);
            self::$ST->execute($param);
            return self::$ST->fetch(\PDO::FETCH_ASSOC);
        }
    }

    /**
     * Отримання всіх рядків з таблиці
     *
     * Цей метод виконує SQL запит до бази даних і повертає всі рядки результату.
     * Використовується для отримання множини рядків із таблиці або декількох записів.
     *
     * @param string $query SQL запит, який має бути виконаний
     * @param array $param Масив параметрів для запиту (за замовчуванням порожній масив)
     * @return \PDOStatement|null Об'єкт PDOStatement, що містить усі рядки результату
     */

    public static function getStringAll(string $query, array $param = []): ?\PDOStatement
    {
        if (self::connect()) {
            self::$ST = self::connect()->prepare($query);
            self::$ST->execute($param);
            return self::$ST;
        }
    }

    /**
     * Отримання 1 стовпця з таблиці
     *
     * Цей метод виконує SQL запит і повертає значення першого стовпця першого рядка результату.
     * Зазвичай використовується для отримання одного значення, наприклад, підрахунку або отримання певного поля.
     *
     * @param string $query SQL запит, який має бути виконаний
     * @param array $param Масив параметрів для запиту (за замовчуванням порожній масив)
     * @return mixed Значення першого стовпця першого рядка або false, якщо рядок не знайдений
     */

    public static function getColumn(string $query, array $param = []): mixed
    {
        if (self::connect()) {
            self::$ST = self::connect()->prepare($query);
            self::$ST->execute($param);
            return self::$ST->fetchColumn();
        }
    }

    /**
     * Додавання рядка в таблицю
     *
     * Цей метод виконує SQL запит для додавання нового запису в таблицю.
     * Після виконання запиту повертається останній доданий ідентифікатор або 0, якщо додавання не вдалося.
     *
     * @param string $query SQL запит для додавання запису
     * @param array $param Масив параметрів для запиту (за замовчуванням порожній масив)
     * @return int Ідентифікатор останнього доданого запису або 0, якщо додавання не вдалося
     */

    public static function getAdd(string $query, array $param = []): int
    {
        if (self::connect()) {
            self::$ST = self::connect()->prepare($query);
            return self::$ST->execute($param) ? self::connect()->lastInsertId() : 0;
        }
    }

    /**
     * Оновлення/видалення рядка в таблиці
     *
     * Цей метод виконує SQL запит для оновлення або видалення запису з таблиці.
     * Повертає true, якщо операція успішна, і false в разі помилки.
     *
     * @param string $query SQL запит для оновлення або видалення запису
     * @param array $param Масив параметрів для запиту (за замовчуванням порожній масив)
     * @return bool True, якщо операція вдала, або false у разі помилки
     */

    public static function getSet(string $query, array $param = []): bool
    {
        if (self::connect()) {
            self::$ST = self::connect()->prepare($query);
            return self::$ST->execute($param);
        }
    }

    /**
     * Виконання запиту з SQL файлу
     *
     * Цей метод дозволяє виконати SQL запити, збережені в файлі. Файл має містити SQL запити,
     * розділені крапкою з комою.
     *
     * @param string $path_file Шлях до файлу, що містить SQL запити
     * @return int 1, якщо запити успішно виконані, або 0, якщо файл не існує
     */

    public static function getSqlFile(string $path_file): int
    {
        if (file_exists($path_file)) {
            $file = file_get_contents($path_file);
            $data = explode(';', $file);
            foreach ($data as $el) {
                self::getAdd($el);
            }
            return 1;
        } else {
            return 0;
        }
    }
}
