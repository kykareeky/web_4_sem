<?php
session_start(); // подключаем механизм сессий

// инициализация сессии при первой загрузке
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];   // массив для истории
    $_SESSION['iteration'] = 0;  // счётчик загрузок
}
$_SESSION['iteration']++; // увеличиваем счётчик при каждом заходе

// функция проверки, является ли строка числом
// исправленная функция проверки, является ли значение числом
function isnum($x)
{
    // если это уже число (int или float), возвращаем true
    if (is_int($x) || is_float($x)) {
        return true;
    }
    
    // если не строка, преобразуем в строку
    if (!is_string($x)) {
        $x = (string)$x;
    }
    
    if ($x === '') return false; // пустая строка – не число
    if ($x[0] == '.' || $x[0] == '0') return false; // не начинается с точки или нуля
    if ($x[strlen($x) - 1] == '.') return false; // не заканчивается точкой

    $point_count = false;
    for ($i = 0; $i < strlen($x); $i++) {
        $ch = $x[$i];
        // допустимые символы: цифры и точка
        if (!($ch >= '0' && $ch <= '9') && $ch != '.') {
            return false;
        }
        if ($ch == '.') {
            if ($point_count) return false; // вторая точка
            $point_count = true;
        }
    }
    return true;
}

// функция вычисления выражения без скобок (сложение, вычитание, умножение, деление)
function calculate($val)
{
    if (!$val) return 'Выражение не задано!';
    if (isnum($val)) return $val; // если просто число

    // сначала обрабатываем сложение
    $args = explode('+', $val);
    if (count($args) > 1) {
        $sum = 0;
        foreach ($args as $arg) {
            $res = calculate($arg);
            if (!isnum($res)) return $res; // ошибка
            $sum += $res;
        }
        return $sum;
    }

    // затем вычитание
    $args = explode('-', $val);
    if (count($args) > 1) {
        $diff = calculate($args[0]);
        for ($i = 1; $i < count($args); $i++) {
            $res = calculate($args[$i]);
            if (!isnum($res)) return $res;
            $diff -= $res;
        }
        return $diff;
    }

    // затем умножение
    $args = explode('*', $val);
    if (count($args) > 1) {
        $prod = 1;
        foreach ($args as $arg) {
            if (!isnum($arg)) return 'Неправильная форма числа!';
            $prod *= $arg;
        }
        return $prod;
    }

    // затем деление (поддерживаем / и :)
    $args = preg_split('/(\/|:)/', $val);
    if (count($args) > 1) {
        $div = $args[0];
        if (!isnum($div)) return 'Неправильная форма числа!';
        for ($i = 1; $i < count($args); $i++) {
            $arg = $args[$i];
            if (!isnum($arg)) return 'Неправильная форма числа!';
            if ($arg == 0) return 'Деление на ноль!';
            $div /= $arg;
        }
        return $div;
    }

    return 'Недопустимые символы в выражении';
}

// функция проверки правильности скобок
function sqValidator($val)
{
    $open = 0;
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] == '(') $open++;
        elseif ($val[$i] == ')') {
            $open--;
            if ($open < 0) return false;
        }
    }
    return $open == 0;
}

// функция вычисления выражения со скобками
function calculateSq($val)
{
    if (!sqValidator($val)) return 'Неправильная расстановка скобок';

    $start = strpos($val, '(');
    if ($start === false) return calculate($val); // нет скобок

    // ищем соответствующую закрывающую скобку
    $end = $start + 1;
    $open = 1;
    while ($open && $end < strlen($val)) {
        if ($val[$end] == '(') $open++;
        if ($val[$end] == ')') $open--;
        $end++;
    }

    // левая часть + вычисленное содержимое скобок + правая часть
    $new_val = substr($val, 0, $start);
    $new_val .= calculateSq(substr($val, $start + 1, $end - $start - 2));
    $new_val .= substr($val, $end);

    return calculateSq($new_val);
}

// обработка формы
$res = null;
if ($_POST['val'] && isset($_POST['iteration']) && $_POST['iteration'] + 1 == $_SESSION['iteration']) {
    $expression = trim($_POST['val']);
    $res = calculateSq($expression); // вычисляем

    // сохраняем в историю, если результат не null
    if ($res !== null) {
        $_SESSION['history'][] = $expression . ' = ' . $res;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Арифметический калькулятор</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    Арифметический калькулятор
</header>

<main class="container">
    <h1>Калькулятор целых чисел и десятичных дробей</h1>

    <form method="post">
        <div class="form-group">
            <label for="val">Выражение:</label>
            <input type="text" name="val" id="val" required placeholder="например: 2+3*(4-1)/2">
        </div>
        <input type="hidden" name="iteration" value="<?php echo $_SESSION['iteration']; ?>">
        <input type="submit" value="Вычислить">
    </form>

    <?php if ($res !== null): ?>
        <div class="src_text">
            <?php if (isnum($res)): ?>
                <h2>Результат вычисления:</h2>
                <div class="highlighted"><?php echo htmlspecialchars($res); ?></div>
            <?php else: ?>
                <div class="src_error">Ошибка вычисления: <?php echo htmlspecialchars($res); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h2>История вычислений</h2>
    <div class="history">
        <?php if (empty($_SESSION['history'])): ?>
            <p>История пуста.</p>
        <?php else: ?>
            <?php foreach ($_SESSION['history'] as $record): ?>
                <div class="highlighted" style="margin-bottom: 10px;"><?php echo htmlspecialchars($record); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- контрольные вопросы -->
    <div class="questions-section">
        <h2>Контрольные вопросы</h2>
        <ul class="questions-list">
            <li><strong>Как изменится работа функции из листинга В-2.4, если из нее убрать проверку на пустую строку?</strong>
                <div class="answer"><p>Если убрать проверку на пустую строку, то при передаче пустого выражения функция вернёт 0 (из-за <code>explode('+', '')</code> получится массив с одним пустым элементом, который не пройдёт проверку <code>isnum()</code> и вернёт ошибку "Неправильная форма числа!", либо при неверной логике – неопределённое поведение. Проверка пустой строки необходима для корректной обработки этого случая.</p></div>
            </li>
            <li><strong>Что такое рекурсия в программировании? Приведите примеры рекурсии.</strong>
                <div class="answer"><p>Рекурсия — это вызов функции из неё же самой. В данной работе рекурсия используется в функциях <code>calculate()</code> (для разбора слагаемых и множителей) и <code>calculateSq()</code> (для обработки вложенных скобок). Пример: вычисление факториала <code>n! = n * (n-1)!</code>.</p></div>
            </li>
            <li><strong>Что такое база рекурсии?</strong>
                <div class="answer"><p>База рекурсии — условие, при котором функция завершает свою работу без рекурсивного вызова. В нашем случае это проверка: если выражение является числом — возвращаем его, если скобок нет — вызываем <code>calculate()</code>.</p></div>
            </li>
            <li><strong>Почему математические действия в функции calculate() выполняются именно в такой последовательности?</strong>
                <div class="answer"><p>Последовательность соответствует приоритету операций: сначала сложение/вычитание (левая ассоциативность), затем умножение/деление. Такой порядок позволяет правильно обрабатывать выражения без скобок.</p></div>
            </li>
            <li><strong>Что такое сессия в PHP?</strong>
                <div class="answer"><p>Сессия — это механизм сохранения данных между разными запросами одного пользователя. Данные хранятся на сервере, а клиенту передаётся идентификатор сессии (обычно в cookie).</p></div>
            </li>
            <li><strong>Почему нельзя начинать инициализировать данные сессии после любого вывода?</strong>
                <div class="answer"><p>Потому что <code>session_start()</code> отправляет HTTP-заголовки (например, Set-Cookie). Заголовки должны быть отправлены до любого вывода содержимого, иначе возникнет ошибка "headers already sent".</p></div>
            </li>
            <li><strong>Можно ли инициализировать данные сессии внутри тега &lt;body&gt;, но до вывода данных средствами PHP?</strong>
                <div class="answer"><p>Нет, нельзя. <code>session_start()</code> должна быть вызвана до любого вывода, даже если этот вывод находится внутри <code>&lt;body&gt;</code>. Иначе заголовки уже будут отправлены.</p></div>
            </li>
            <li><strong>Как PHP разделяет пользователей для доступа к разным хранилищам сессий?</strong>
                <div class="answer"><p>По уникальному идентификатору сессии (session ID), который передаётся через cookie или URL. Каждому пользователю соответствует свой ID, и сервер по нему находит нужный файл с данными сессии.</p></div>
            </li>
            <li><strong>Как получить доступ к данным сессии из PHP?</strong>
                <div class="answer"><p>Через суперглобальный массив <code>$_SESSION</code> после вызова <code>session_start()</code>.</p></div>
            </li>
            <li><strong>Как долго можно обращаться к данным сессии?</strong>
                <div class="answer"><p>Данные сессии доступны до тех пор, пока сессия не будет уничтожена вручную (<code>session_destroy()</code>) или не истечёт время жизни сессии (задаётся в настройках PHP).</p></div>
            </li>
            <li><strong>Можно ли хранить в сессии массивы и строки больше 1024 символов?</strong>
                <div class="answer"><p>Да, ограничений на размер нет (кроме лимитов памяти и размера хранилища).</p></div>
            </li>
            <li><strong>Как записать данные в сессию?</strong>
                <div class="answer"><p>Например: <code>$_SESSION['key'] = 'value';</code> после вызова <code>session_start()</code>.</p></div>
            </li>
            <li><strong>Можно ли прочитать данные из сессии, если они туда не записаны? Как быть в том случае, если такая ситуация возможна?</strong>
                <div class="answer"><p>Можно, но будет ошибка уровня E_NOTICE. Чтобы её избежать, используйте <code>isset($_SESSION['key'])</code> перед чтением.</p></div>
            </li>
            <li><strong>Как быстро вычислить любое выражение средствами PHP?</strong>
                <div class="answer"><p>С помощью <code>eval('$res = ' . $expression . ';');</code>. Но этот способ опасен, если выражение приходит от пользователя (возможна инъекция). В лабораторной работе используется безопасный разбор.</p></div>
            </li>
            <li><strong>Можно ли в PHP интерпретировать символ строки как число?</strong>
                <div class="answer"><p>Да, PHP автоматически приводит строку к числу в числовом контексте. Но для проверки мы написали свою функцию <code>isnum()</code>, так как требуется контроль над преобразованием.</p></div>
            </li>
        </ul>
    </div>
</main>

<footer>
    &copy; Арифметический калькулятор | Лабораторная работа В-2
</footer>

</body>
</html>