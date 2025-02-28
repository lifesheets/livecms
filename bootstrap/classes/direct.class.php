<?php

/**
 * Клас для роботи з напрямками (шляхами) та перевірки існування файлів і директорій.
 */

namespace livecms;

class direct {

    /**
     * Отримує значення з GET-запиту, фільтрує та повертає очищений рядок.
     *
     * @param string $get_name Назва параметра в запиті
     * @return string Очищене значення параметра або 'no_data', якщо значення відсутнє
     */

    public static function get(string $get_name): string
    {
        $filter = filter_input(INPUT_GET, $get_name, FILTER_SANITIZE_ENCODED) ?? '';
        $get = clearSpecialChars($filter);
        return getStringLength($get) > 0 ? $get : 'no_data';
    }

    /**
     * Перевіряє існування файлу за вказаним шляхом.
     *
     * @param string $path Шлях до файлу відносно кореневої директорії
     * @return bool true, якщо файл існує, інакше false
     */

    public static function e_file(string $path): bool
    {
        return is_file(ROOT_DIR . '/' . $path);
    }

    /**
     * Перевіряє існування директорії за вказаним шляхом.
     *
     * @param string $path Шлях до директорії відносно кореневої директорії
     * @return bool true, якщо директорія існує, інакше false
     */

    public static function e_dir(string $path): bool
    {
        return is_dir(ROOT_DIR . '/' . $path);
    }

    /**
     * Функція для роботи з компонентами (поки не реалізована).
     *
     * @param string $path Шлях до компонентів
     * @param int $count Кількість компонентів
     * @param int $limit Ліміт на кількість компонентів
     * @param string $ext Розширення файлів компонентів (за замовчуванням 'php')
     */

    public static function components(string $path, int $count = 1, int $limit = 100, string $ext = 'php') {
        // Todo
    }
}
