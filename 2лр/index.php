<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Власова Мария, 241-352, лаб. работа №2"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="logo.png" alt="Логотип университета" class="logo" width="65" height="65">
        </div>
        <div class="header-text">
            <p>Л.р №2  Власова Мария Вариант: 5</p>
        </div>
    </header>

    <main>
        <h1>Циклические алгоритмы. Условия в алгоритмах. </h1>
        <h2>Вариант задания</h2>
        <img src="foto.png" alt="Условие задачи">
        <?php
        // 1. Явная инициализация переменных
        $x = -10;                   // начальное значение аргумента
        $encounting = 20;           // количество вычисляемых значений
        $step = 2;                  // шаг изменения аргумента
        $min_value = -100;          // минимальное значение функции для остановки
        $max_value = 100;           // максимальное значение функции для остановки
        $type = 'C';                // тип верстки: A, B, C, D, E

        // 2. Инициализация строковой переменной для типа верстки
        $layout_type = $type;
        
        // Словарь для отображения типа верстки
        $layout_names = [
            'A' => 'Простая текстовая',
            'B' => 'Маркированный список',
            'C' => 'Нумерованный список',
            'D' => 'Табличная',
            'E' => 'Блочная'
        ];
        $layout_name = isset($layout_names[$layout_type]) ? $layout_names[$layout_type] : $layout_type;

        // 3. Массив для хранения значений функции
        $values = [];
        $sum = 0;
        $count = 0;
        $error_count = 0;  // Счетчик ошибок деления на ноль

        // 4. Функция для вычисления f(x) с округлением и проверкой деления на ноль
        function computeF($x) {
            if ($x <= 10) {
                return round(3 * $x + 9, 3);
            } elseif ($x > 10 && $x < 20) {
                // Проверка деления на ноль
                $denominator = $x**2 - 121;
                if ($denominator == 0) {
                    return "error";
                }
                return round(($x + 3) / $denominator, 3);
            } elseif ($x >= 20) {
                return round(4 * $x**2 - 11, 3);
            }
        }

        // 5. Начало вывода в зависимости от типа верстки
        if ($type == 'B') echo '<ul>';
        if ($type == 'C') echo '<ol>';
        if ($type == 'D') echo '<table class="bordered-table"><tr><th>№</th><th>x</th><th>f(x)</th></tr>';
        if ($type == 'E') echo '<div class="block-container">';

        // 6. Цикл вычислений
        for ($i = 0; $i < $encounting; $i++) {
            $f = computeF($x);

            // Проверка на остановку по min/max
            if (is_numeric($f)) {
                if ($f < $min_value || $f > $max_value) {
                    break;
                }
                $values[] = $f;
                $sum += $f;
                $count++;
            } elseif ($f == "error") {
                $error_count++;
            }

            // Вывод в зависимости от типа верстки
            switch ($type) {
                case 'A':
                    echo "f($x) = $f";
                    if ($i < $encounting - 1) echo '<br>';
                    break;
                case 'B':
                case 'C':
                    echo "<li>f($x) = $f</li>";
                    break;
                case 'D':
                    echo "<tr><td>" . ($i + 1) . "</td><td>$x</td><td>$f</td></tr>";
                    break;
                case 'E':
                    echo "<div class='block'>f($x) = $f</div>";
                    break;
            }

            $x += $step;
        }

        // 7. Закрытие контейнеров верстки
        if ($type == 'B') echo '</ul>';
        if ($type == 'C') echo '</ol>';
        if ($type == 'D') echo '</table>';
        if ($type == 'E') echo '</div>';

        // 8. Вычисление статистики
        if ($count > 0) {
            $min = min($values);
            $max = max($values);
            $average = round($sum / $count, 3);
        } else {
            $min = $max = $average = "нет данных";
            $sum = 0;
        }
        ?>

        <div class="stats">
            <h2>Статистика вычислений</h2>
            <p>Минимальное значение: <?php echo $min; ?></p>
            <p>Максимальное значение: <?php echo $max; ?></p>
            <p>Среднее арифметическое: <?php echo $average; ?></p>
            <p>Сумма всех значений: <?php echo round($sum, 3); ?></p>
            <p>Количество ошибок (деление на ноль): <?php echo $error_count; ?></p>
        </div>
        <br><br>
    </main>

    <footer>
        <p>Тип верстки: <?php echo $layout_name; ?></p>
        <p>&copy; <?php echo date('Y'); ?> Лабораторная работа №2</p>
    </footer>
</body>
</html>