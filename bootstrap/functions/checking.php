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

