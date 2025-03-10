<?php

/**
 * Фільтрація тексту перед записом у базу даних.
 * Додає слеші для захисту від SQL-ін'єкцій.
 *
 * @param string|null $text Вхідний текст для обробки.
 * @return string Відфільтрований текст.
 */

function escape_input(?string $text): string
{
    # Якщо текст є null, повертаємо порожній рядок
    return $text === null ? '' : addslashes($text);
}

/**
 * Фільтрація даних cookie.
 * Очищає значення кукі від шкідливих символів.
 *
 * @param string|null $data Назва кукі, значення якої потрібно відфільтрувати.
 * @return string Відфільтроване значення кукі.
 */

function filter_cookie(?string $data): string
{
    # Фільтрація вхідних даних з cookie, щоб уникнути шкідливих символів та забезпечити безпеку
    return $data === null ? '' : filter_input(INPUT_COOKIE, $data, FILTER_SANITIZE_ENCODED);
}

/**
 * Видалення спеціальних символів з тексту без заміни.
 * Видаляє всі потенційно небезпечні символи, залишаючи тільки безпечний текст.
 *
 * @param string $text Вхідний текст для очищення.
 * @return string Очищений текст без спеціальних символів.
 */

function clearSpecialChars(string $text): string
{
    # Список спеціальних символів, які необхідно видалити
    $special_chars = array('?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr(0));

    # Замінюємо нерозривний пробіл на звичайний
    $text = preg_replace("#\x{00a0}#siu", ' ', $text);

    # Видаляємо всі символи зі списку
    $text = str_replace($special_chars, '', $text);

    # Видаляємо залишки пробілів та певні символи на краях рядка
    $text = str_replace(array('%20', '+'), '', $text);
    $text = trim($text, '.-_');

    # Екрануємо HTML-символи для безпеки
    return htmlspecialchars($text);
}

/**
 * Транслітерація російських літер в англійські.
 * Замінює кириличні символи на їх латинські аналоги.
 *
 * @param string $value Вхідний текст для транслітерації.
 * @return string Транслітерований текст.
 */

function translit(string $value): string
{
    # Масив відповідностей кириличних символів латинським
    $converter = array(
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
        'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
        'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch',
        'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
    );

    # Виконуємо заміну символів за допомогою асоціативного масиву
    $value = strtr($value, $converter);

    # Повертаємо транслітерований текст
    return $value;
}

/**
 * Перевіряє, чи входить переданий колір у список дозволених.
 *
 * @param string $color Код кольору у форматі HEX (наприклад, "#FF0000").
 * @return bool Повертає true, якщо колір є у списку дозволених, і false, якщо його немає.
 */

function color_filter(string $color): bool
{

    /**
     * Масив дозволених кольорів у форматі HEX
     * Використовується для перевірки відповідності переданого кольору
     */

    $array = array(
        '#7DFFFF', '#241E1E', '#486CF0', '#78BEFF', '#11D1BB', '#C0C6D1',
        '#2F2E33', '#F4FF7D', '#887DFF', '#FF7DFB', '#45FF83', '#222926',
        '#FFFE00', '#FF00F6', '#0900FF', '#00E2FF', '#0067FF', '#546E7A',
        '#FF7900', '#FFE500', '#00FF0A', '#DA00FF', '#3BFFE7', '#FFEB3B',
        '#FF5F53', '#2196F3', '#FF00F6', '#FFC669', '#00E409', '#FFFE31',
        '#FFCD31', '#FF317C', '#9B31FF', '#31FFEE', '#97A6B0', '#FF00F6',
        '#6A1B9A', '#C62828', '#EF6C00', '#F9A825', '#FFAB91', '#2E7D32',
        '#2196F3', '#00BCD4', '#47E64D', '#55E7FA', '#FDED5C', '#FFAB91',
        '#F094FF', '#000000', '#3A474C', '#97A6B0', '#804F29', '#62DD9D',
        '#FF3B2C', '#FF2CE3', '#4448FF', '#F5FF44', '#00FF1D', '#9F00FF'
    );

    # Перевіряє, чи переданий колір міститься в списку дозволених
    return in_array($color, $array, true);
}
