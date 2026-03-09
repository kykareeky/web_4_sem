<?php
// ------------------------- вспомогательные функции -------------------------
// проверка, не является ли строка числом (true – не число)
function arg_is_not_num($val) {
    return !is_numeric($val);
}

// красивое представление массива
function printArray($arr) {
    return '[' . implode(', ', $arr) . ']';
}

// ------------------------- алгоритмы сортировки с выводом итераций -------------------------
// сортировка выбором (листинг А-7.1, доработан)
function selectionSort(&$arr, &$iter) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $minIdx = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            $iter++;
            echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
            if ($arr[$j] < $arr[$minIdx]) {
                $minIdx = $j;
            }
        }
        if ($minIdx != $i) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$minIdx];
            $arr[$minIdx] = $temp;
            $iter++;
            echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
        }
    }
}

// пузырьковая сортировка (классическая)
function bubbleSort(&$arr, &$iter) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            $iter++;
            echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
            if ($arr[$j] > $arr[$j + 1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
                $iter++;
                echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
            }
        }
    }
}

// сортировка Шелла (листинг А-7.12)
function shellSort(&$arr, &$iter) {
    $n = count($arr);
    for ($k = ceil($n / 2); $k >= 1; $k = ceil($k / 2)) {
        for ($i = $k; $i < $n; $i++) {
            $val = $arr[$i];
            $j = $i - $k;
            while ($j >= 0 && $arr[$j] > $val) {
                $iter++;
                echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
                $arr[$j + $k] = $arr[$j];
                $j -= $k;
            }
            $arr[$j + $k] = $val;
            $iter++;
            echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
        }
    }
}

// сортировка гнома (листинг А-7.10)
function gnomeSort(&$arr, &$iter) {
    $i = 1;
    $n = count($arr);
    while ($i < $n) {
        $iter++;
        echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
        if ($i == 0 || $arr[$i - 1] <= $arr[$i]) {
            $i++;
        } else {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $temp;
            $i--;
        }
    }
}

// быстрая сортировка (листинг А-7.13, рекурсивная)
function quickSortRec(&$arr, $left, $right, &$iter) {
    if ($left >= $right) return;
    $l = $left;
    $r = $right;
    $point = $arr[floor(($left + $right) / 2)];
    do {
        while ($arr[$l] < $point) $l++;
        while ($arr[$r] > $point) $r--;
        if ($l <= $r) {
            $temp = $arr[$l];
            $arr[$l] = $arr[$r];
            $arr[$r] = $temp;
            $iter++;
            echo "<div class='iteration-item'>итерация $iter: " . printArray($arr) . "</div>";
            $l++;
            $r--;
        }
    } while ($l <= $r);
    if ($left < $r) quickSortRec($arr, $left, $r, $iter);
    if ($l < $right) quickSortRec($arr, $l, $right, $iter);
}

function quickSort(&$arr, &$iter) {
    quickSortRec($arr, 0, count($arr) - 1, $iter);
}

// встроенная функция (без подсчёта итераций)
function builtinSort(&$arr) {
    sort($arr);
}

// ------------------------- основная логика -------------------------
// проверка, переданы ли данные
if (!isset($_POST['arrLength']) || !isset($_POST['element0'])) {
    echo "<!DOCTYPE html><html><head><link rel='stylesheet' href='style.css'></head><body><main><div class='report'>ошибка: массив не задан, сортировка невозможна.</div><a href='index.php' class='back-button'>вернуться</a></main></body></html>";
    exit();
}

$length = (int)$_POST['arrLength'];
$rawArr = [];
for ($i = 0; $i < $length; $i++) {
    $key = 'element' . $i;
    if (isset($_POST[$key])) {
        $rawArr[] = trim($_POST[$key]);
    } else {
        echo "<main><div class='report'>ошибка: не хватает данных.</div><a href='index.php'>назад</a></main>";
        exit();
    }
}

// валидация чисел
foreach ($rawArr as $val) {
    if (arg_is_not_num($val)) {
        echo "<main><div class='report'>ошибка: элемент массива \"$val\" - не число. сортировка невозможна.</div><a href='index.php' class='back-button'>вернуться</a></main>";
        exit();
    }
}

// преобразуем в числа
$arr = array_map('floatval', $rawArr);

// определяем выбранный алгоритм
$algo = $_POST['algorithm'] ?? '';
$algoNames = [
    'selection' => 'сортировка выбором',
    'bubble'    => 'пузырьковый алгоритм',
    'shell'     => 'алгоритм Шелла',
    'gnome'     => 'алгоритм садового гнома',
    'quick'     => 'быстрая сортировка',
    'builtin'   => 'встроенная функция php'
];
$algoName = $algoNames[$algo] ?? 'неизвестный алгоритм';

// начинаем вывод
echo '<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"></head><body>';
echo '<header>лабораторная работа № а-7 – результат сортировки</header>';
echo '<main>';
echo '<div class="report">';
echo '<h2>' . $algoName . '</h2>';
echo '<p><strong>исходный массив:</strong> ' . implode(', ', $rawArr) . '</p>';
echo '<p><strong>валидация:</strong> все элементы являются числами.</p>';

// сортировка
$time_start = microtime(true);
$iter = 0;

if ($algo == 'selection') {
    selectionSort($arr, $iter);
} elseif ($algo == 'bubble') {
    bubbleSort($arr, $iter);
} elseif ($algo == 'shell') {
    shellSort($arr, $iter);
} elseif ($algo == 'gnome') {
    gnomeSort($arr, $iter);
} elseif ($algo == 'quick') {
    quickSort($arr, $iter);
} elseif ($algo == 'builtin') {
    builtinSort($arr);
    echo '<p><strong>итоговый массив:</strong> ' . implode(', ', $arr) . '</p>';
} else {
    echo '<p>алгоритм не выбран.</p>';
    exit();
}

$time_end = microtime(true);
$time_taken = $time_end - $time_start;

if ($algo != 'builtin') {
    echo '<p><strong>сортировка завершена, проведено ' . $iter . ' итераций. сортировка заняла ' . round($time_taken, 6) . ' секунд.</strong></p>';
} else {
    echo '<p><strong>сортировка завершена с использованием встроенной функции. время: ' . round($time_taken, 6) . ' секунд. количество итераций не определено.</strong></p>';
}
echo '</div>'; // report

// ------------------------- ответы на контрольные вопросы -------------------------
echo '<div class="report">';
echo '<h3>ответы на контрольные вопросы:</h3>';
echo '<ol>';
echo '<li><strong>какие изменения необходимо внести в программу, чтобы она корректно работала без использования информации из скрытого поля arrlength?</strong> можно передавать количество элементов через массив $_POST, например, проверять наличие element0, element1, ... до первого отсутствующего, или добавить маркер конца (например, element_end).</li>';
echo '<li><strong>как при нажатии кнопки "добавить еще один элемент" добавлять не один, а несколько элементов сразу?</strong> изменить функцию addelement, добавив параметр количества, и в цикле вызывать вставку строк.</li>';
echo '<li><strong>как будет выглядеть обратная к arg_is_not_num() функция (возвращает true, если аргумент число)?</strong> function arg_is_num($arg) { return is_numeric($arg); }</li>';
echo '<li><strong>детально разберите и расскажите: как работают алгоритмы шейкерной сортировки массива, сортировки слиянием и вставками?</strong> шейкерная — двунаправленная пузырьковая: сначала «лёгкие» элементы всплывают вверх, затем «тяжёлые» тонут вниз, границы отсортированной части сужаются. сортировка слиянием — рекурсивно делит массив пополам, сортирует каждую половину и сливает упорядоченные части. сортировка вставками — каждый следующий элемент вставляется в уже отсортированную часть на своё место.</li>';
echo '<li><strong>в каком случае на листинге 7.9 без условия $j>=0 будет бесконечный цикл?</strong> когда $j станет отрицательным, условие $arr[$j] > $val будет обращаться к несуществующему элементу, но цикл продолжит выполняться, так как $j-- будет уходить в минус, и условие никогда не станет ложным.</li>';
echo '<li><strong>как изменится работа программы листинга 7.9, если в цикле с предусловием будет $j>0?</strong> тогда первый элемент массива (индекс 0) никогда не будет сравниваться, и сортировка вставками может работать некорректно: элемент может остаться не на своём месте.</li>';
echo '<li><strong>какой метод сортировки использует стандартная функция php?</strong> в современных версиях php функция sort() использует стабильную сортировку, основанную на алгоритме "merge sort" (точнее, timsort в некоторых реализациях).</li>';
echo '<li><strong>как досрочно прекратить выполнения php-программы?</strong> с помощью функции exit() или die().</li>';
echo '<li><strong>если была вызвана функция exit() – будет ли в браузер передана статическая вёрстка, размещённая после последнего фрагмента кода php?</strong> нет, выполнение скрипта прекращается сразу, и код после exit() не выполняется.</li>';
echo '<li><strong>как получить системное время с помощью php?</strong> функция microtime(true) возвращает текущую метку времени с микросекундами. также можно использовать time() для секунд.</li>';
echo '<li><strong>какие параметры присутствуют у функции microtime() и для чего они используются?</strong> microtime() принимает необязательный параметр get_as_float. если true, возвращает время как float (секунды с микросекундами). если false или параметр опущен, возвращает строку в формате "msec sec".</li>';
echo '</ol>';
echo '</div>';

echo '<a href="index.php" class="back-button">вернуться к вводу</a>';
echo '</main>';
echo '<footer>&copy; 2025</footer>';
echo '</body></html>';
?>