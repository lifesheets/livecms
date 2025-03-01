<?php

/**
 * Функція підрахунку довжини рядка без переносів рядка
 *
 * @param string|null $text Вхідний рядок
 * @return int Довжина рядка без символів нового рядка
 */

function getStringLength(?string $text): int
{
    // Якщо $text дорівнює null, повертаємо 0, Видаляємо всі символи переносу рядка
    return $text === null ? 0 : mb_strlen(str_replace(["\r", "\n"], '', $text), 'UTF-8');
}
