<?php

/**
 * Статистика по словам
 *
 * @param string $text
 * @return array
 */

function wordStat(string $text): array
{
    $text = preg_replace('/[[:punct:][:digit:]]/u', '', $text);
    $text = preg_replace('/[[:space:]]/u', ' ', $text);
    $words_array = preg_split("/[\n\r\t ]+/u", $text, 0, PREG_SPLIT_NO_EMPTY);

    return array_count_values($words_array);
}
