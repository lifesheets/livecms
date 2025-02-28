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

