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

}
