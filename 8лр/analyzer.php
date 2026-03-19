<?php
/**
 * Подсчитывает количество вхождений каждого символа в тексте (без учета регистра).
 * @param string $text Текст в кодировке CP1251.
 * @return array Ассоциативный массив символов и их количества.
 */
function test_symbs($text) {
    // массив символов текста
    $symbs = array();

    // переводим текст в нижний регистр (функция работает корректно с CP1251)
    $l_text = strtolower($text);

    // последовательно перебираем все символы текста
    for ($i = 0; $i < strlen($l_text); $i++) {
        $char = $l_text[$i];
        if (isset($symbs[$char])) {
            // если символ есть в массиве - увеличиваем счетчик
            $symbs[$char]++;
        } else {
            // иначе - добавляем символ в массив
            $symbs[$char] = 1;
        }
    }

    // возвращаем массив с числом вхождений символов в тексте
    return $symbs;
}

/**
 * Главная функция анализа текста.
 * @param string $text Текст для анализа в кодировке CP1251.
 */
function test_it($text) {
    // --- 1. БАЗОВАЯ ИНФОРМАЦИЯ ---
    // количество символов (strlen в CP1251 считает правильно)
    echo '<h2>Информация о тексте:</h2>';
    echo '<table border="1" cellpadding="5" cellspacing="0">';

    // количество символов
    $total_chars = strlen($text);
    echo '<tr><td>Количество символов (с пробелами)</td><td>' . $total_chars . '</td></tr>';

    // --- 2. ПОДГОТОВКА ДАННЫХ ДЛЯ АНАЛИЗА ---
    // ассоциированный массив цифр (как в листинге А-8.2)
    $cifra = array(
        '0' => true, '1' => true, '2' => true, '3' => true, '4' => true,
        '5' => true, '6' => true, '7' => true, '8' => true, '9' => true
    );

    // массивы для букв и знаков препинания (для кириллицы в CP1251)
    $lower_letters = array();
    $upper_letters = array();
    // знаки препинания: . , ! ? : ; - ( ) [ ] { } " ' ...
    $punctuation_marks = array(
        '.' => true, ',' => true, '!' => true, '?' => true, ':' => true,
        ';' => true, '-' => true, '(' => true, ')' => true, '[' => true,
        ']' => true, '{' => true, '}' => true, '"' => true, "'" => true,
        '...' => true // многоточие, но в данном случае будем обрабатывать посимвольно
    );

    // инициализируем счетчики
    $cifra_amount = 0;
    $letter_amount = 0;
    $lower_amount = 0;
    $upper_amount = 0;
    $punctuation_amount = 0;

    $word_amount = 0;
    $word = ''; // текущее слово
    $words = array(); // список всех слов

    // --- 3. ОСНОВНОЙ ЦИКЛ АНАЛИЗА (доработанный из листинга А-8.2) ---
    for ($i = 0; $i < strlen($text); $i++) {
        $current_char = $text[$i];

        // --- Анализ цифр ---
        if (array_key_exists($current_char, $cifra)) {
            $cifra_amount++;
        }

        // --- Анализ букв и регистра ---
        // определяем, является ли символ буквой (проверяем по ASCII-кодам для CP1251)
        $is_letter = false;
        // английские буквы
        if (($current_char >= 'a' && $current_char <= 'z') || ($current_char >= 'A' && $current_char <= 'Z')) {
            $is_letter = true;
        }
        // русские буквы в CP1251 (примерный диапазон: А-Я а-я)
        // используем ord() для сравнения кодов
        $ord_char = ord($current_char);
        // русские заглавные (А-Я) в CP1251: 192-223
        // русские строчные (а-я) в CP1251: 224-255
        if (($ord_char >= 192 && $ord_char <= 223) || ($ord_char >= 224 && $ord_char <= 255)) {
            $is_letter = true;
        }
        // буква ё (код 184) и Ё (код 168) в CP1251
        if ($ord_char == 184 || $ord_char == 168) {
            $is_letter = true;
        }

        if ($is_letter) {
            $letter_amount++;
            // проверяем регистр
            if (ctype_upper($current_char) || $ord_char == 168) { // Ё или заглавная латиница/кириллица
                $upper_amount++;
            } else {
                $lower_amount++;
            }
        }

        // --- Анализ знаков препинания ---
        if (array_key_exists($current_char, $punctuation_marks)) {
            $punctuation_amount++;
        }

        // --- Анализ слов (доработан для учета знаков препинания) ---
        // признак окончания слова: пробел, знак препинания или конец текста
        if ($current_char == ' ' || array_key_exists($current_char, $punctuation_marks) || $i == strlen($text) - 1) {
            // если это последний символ и он не является разделителем, добавляем его к слову
            if ($i == strlen($text) - 1 && $current_char != ' ' && !array_key_exists($current_char, $punctuation_marks)) {
                $word .= $current_char;
            }

            if ($word) { // если есть текущее слово
                // приводим слово к нижнему регистру для единообразия
                $word_lower = strtolower($word);
                if (isset($words[$word_lower])) {
                    $words[$word_lower]++; // увеличиваем число его повторов
                } else {
                    $words[$word_lower] = 1; // первый повтор слова
                }
                $word = ''; // сбрасываем текущее слово
            }
        } else {
            // если слово продолжается
            $word .= $current_char;
        }
    }

    // --- 4. ВЫВОД РЕЗУЛЬТАТОВ В ТАБЛИЦЕ ---
    echo '<tr><td>Количество букв</td><td>' . $letter_amount . '</td></tr>';
    echo '<tr><td>Количество строчных букв</td><td>' . $lower_amount . '</td></tr>';
    echo '<tr><td>Количество заглавных букв</td><td>' . $upper_amount . '</td></tr>';
    echo '<tr><td>Количество знаков препинания</td><td>' . $punctuation_amount . '</td></tr>';
    echo '<tr><td>Количество цифр</td><td>' . $cifra_amount . '</td></tr>';
    echo '<tr><td>Количество слов</td><td>' . count($words) . '</td></tr>';

    // --- 5. АНАЛИЗ СИМВОЛОВ (вызов test_symbs) ---
    $symbols_count = test_symbs($text);

    echo '<tr><td colspan="2"><strong>Количество вхождений каждого символа (без учета регистра):</strong></td></tr>';
    // сортируем массив символов по ключам (символам) для удобства
    ksort($symbols_count);
    foreach ($symbols_count as $char => $count) {
        // пропускаем пробелы и служебные символы, если нужно, но по заданию нужны все символы
        // перед выводом символа перекодируем его обратно в UTF-8
        $char_utf8 = iconv("cp1251", "utf-8//IGNORE", $char);
        echo '<tr><td>' . htmlspecialchars($char_utf8) . '</td><td>' . $count . '</td></tr>';
    }

    // --- 6. АНАЛИЗ СЛОВ (список всех слов и количество их вхождений, отсортированный по алфавиту) ---
    echo '<tr><td colspan="2"><strong>Список всех слов и количество их вхождений (по алфавиту):</strong></td></tr>';
    // сортируем массив слов по ключам (алфавитный порядок)
    ksort($words);
    foreach ($words as $word_lower => $count) {
        // перед выводом слова перекодируем его обратно в UTF-8
        $word_utf8 = iconv("cp1251", "utf-8//IGNORE", $word_lower);
        echo '<tr><td>' . htmlspecialchars($word_utf8) . '</td><td>' . $count . '</td></tr>';
    }

    echo '</table>';
}

?>