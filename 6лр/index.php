<?php
// Лабораторная работа № А-6
// Обработка данных формы

$show_results = false;
$out_text = '';
$email_sent_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['A'])) {
    // Получение и очистка данных
    $fio = trim($_POST['FIO'] ?? '');
    $group = trim($_POST['GROUP'] ?? '');
    $about = trim($_POST['ABOUT'] ?? '');
    $a = str_replace(',', '.', trim($_POST['A'] ?? ''));
    $b = str_replace(',', '.', trim($_POST['B'] ?? ''));
    $c = str_replace(',', '.', trim($_POST['C'] ?? ''));
    $user_answer = str_replace(',', '.', trim($_POST['result'] ?? ''));
    $task = $_POST['TASK'] ?? '';
    $view = $_POST['VIEW'] ?? 'browser';
    $send_mail = isset($_POST['send_mail']);
    $mail = trim($_POST['MAIL'] ?? '');

    // Проверка, что A, B, C — числа
    $valid_numbers = is_numeric($a) && is_numeric($b) && is_numeric($c);
    if (!$valid_numbers) {
        $out_text = '<p class="error">Ошибка: значения A, B, C должны быть числами.</p>';
    } else {
        $a = (float)$a;
        $b = (float)$b;
        $c = (float)$c;

        // Определение задачи и вычисление правильного ответа
        $correct_result = null;
        $task_name = '';
        switch ($task) {
            case 'area_triangle':
                $task_name = 'Площадь треугольника (формула Герона)';
                $s = ($a + $b + $c) / 2;
                if ($a + $b > $c && $a + $c > $b && $b + $c > $a) {
                    $correct_result = sqrt($s * ($s - $a) * ($s - $b) * ($s - $c));
                } else {
                    $correct_result = null; // треугольник не существует
                }
                break;
            case 'perimeter':
                $task_name = 'Периметр треугольника';
                $correct_result = $a + $b + $c;
                break;
            case 'volume':
                $task_name = 'Объем параллелепипеда';
                $correct_result = $a * $b * $c;
                break;
            case 'average':
                $task_name = 'Среднее арифметическое';
                $correct_result = ($a + $b + $c) / 3;
                break;
            case 'sum_squares':
                $task_name = 'Сумма квадратов';
                $correct_result = $a*$a + $b*$b + $c*$c;
                break;
            case 'max':
                $task_name = 'Максимальное из чисел';
                $correct_result = max($a, $b, $c);
                break;
            default:
                $task_name = 'Неизвестная задача';
        }

        // Округление результата до 2 знаков
        if (is_float($correct_result) || is_int($correct_result)) {
            $correct_result = round($correct_result, 2);
        }

        // Обработка ответа пользователя
        $user_answer_val = is_numeric($user_answer) ? round((float)$user_answer, 2) : null;
        $user_answer_empty = ($user_answer === '');

        // Формирование отчёта
        $out_text = '<div class="report">';
        $out_text .= '<p><strong>ФИО:</strong> ' . htmlspecialchars($fio) . '</p>';
        $out_text .= '<p><strong>Группа:</strong> ' . htmlspecialchars($group) . '</p>';
        if (!empty($about)) {
            $out_text .= '<p><strong>О себе:</strong> ' . nl2br(htmlspecialchars($about)) . '</p>';
        }
        $out_text .= '<p><strong>Тип задачи:</strong> ' . $task_name . '</p>';
        $out_text .= '<p><strong>Входные данные:</strong> A = ' . $a . ', B = ' . $b . ', C = ' . $c . '</p>';
        $out_text .= '<p><strong>Ваш ответ:</strong> ' . ($user_answer_empty ? 'не был введен' : htmlspecialchars($_POST['result'])) . '</p>';

        if ($correct_result === null && $task === 'area_triangle' && !($a+$b>$c && $a+$c>$b && $b+$c>$a)) {
            $out_text .= '<p><strong>Вычисленный программой результат:</strong> Треугольник не существует</p>';
            $out_text .= '<p><strong>Результат теста:</strong> Ошибка: тест не пройден</p>';
        } elseif ($correct_result === null) {
            $out_text .= '<p><strong>Вычисленный программой результат:</strong> не определен</p>';
            $out_text .= '<p><strong>Результат теста:</strong> Ошибка: тест не пройден</p>';
        } else {
            $out_text .= '<p><strong>Вычисленный программой результат:</strong> ' . $correct_result . '</p>';
            if ($user_answer_empty) {
                $out_text .= '<p><strong>Результат теста:</strong> Задача самостоятельно решена не была</p>';
            } elseif ($user_answer_val === $correct_result) {
                $out_text .= '<p><strong>Результат теста:</strong> Тест пройден</p>';
            } else {
                $out_text .= '<p><strong>Результат теста:</strong> Ошибка: тест не пройден</p>';
            }
        }
        $out_text .= '</div>';
    }

    // Отправка результата по email, если установлен флажок
    if ($send_mail && !empty($mail) && $valid_numbers) {
        $plain_text = strip_tags(str_replace('<br>', "\r\n", $out_text));
        $subject = 'Результат тестирования';
        $headers = "From: auto@mani.ru\r\n" .
                   "Content-Type: text/plain; charset=utf-8\r\n";
        if (mail($mail, $subject, $plain_text, $headers)) {
            $email_sent_message = '<p>Результаты теста были автоматически отправлены на e-mail ' . htmlspecialchars($mail) . '</p>';
        } else {
            $email_sent_message = '<p>Не удалось отправить email.</p>';
        }
    }

    $show_results = true;
}

// Подготовка данных для формы (при первой загрузке или повторном тесте)
$default_fio = htmlspecialchars($_GET['FIO'] ?? '', ENT_QUOTES);
$default_group = htmlspecialchars($_GET['GROUP'] ?? '', ENT_QUOTES);
$default_a = mt_rand(0, 10000) / 100;
$default_b = mt_rand(0, 10000) / 100;
$default_c = mt_rand(0, 10000) / 100;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа № А-6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>Лабораторная работа № А-6</header>
<main>
<?php if ($show_results): ?>
    <!-- Вывод отчёта -->
    <?php echo $out_text; ?>
    <?php if (!empty($email_sent_message)) echo $email_sent_message; ?>
    <?php if ($view === 'browser'): ?>
        <a href="?FIO=<?php echo urlencode($fio); ?>&GROUP=<?php echo urlencode($group); ?>" class="back-button">Повторить тест</a>
    <?php endif; ?>
<?php else: ?>
    <!-- Отображение формы -->
    <div class="form-container">
        <form method="post" action="">
            <div class="form-row">
                <label for="FIO">ФИО:</label>
                <input type="text" id="FIO" name="FIO" value="<?php echo $default_fio; ?>" required>
            </div>
            <div class="form-row">
                <label for="GROUP">Номер группы:</label>
                <input type="text" id="GROUP" name="GROUP" value="<?php echo $default_group; ?>" required>
            </div>
            <div class="form-row">
                <label for="A">Значение А:</label>
                <input type="text" id="A" name="A" value="<?php echo $default_a; ?>" required>
            </div>
            <div class="form-row">
                <label for="B">Значение В:</label>
                <input type="text" id="B" name="B" value="<?php echo $default_b; ?>" required>
            </div>
            <div class="form-row">
                <label for="C">Значение С:</label>
                <input type="text" id="C" name="C" value="<?php echo $default_c; ?>" required>
            </div>
            <div class="form-row">
                <label for="result">Ваш ответ:</label>
                <input type="text" id="result" name="result">
            </div>
            <div class="form-row">
                <label for="ABOUT">Немного о себе:</label>
                <textarea id="ABOUT" name="ABOUT"></textarea>
            </div>
            <div class="form-row">
                <label for="TASK">Выберите задачу:</label>
                <select id="TASK" name="TASK">
                    <option value="area_triangle">Площадь треугольника</option>
                    <option value="perimeter">Периметр треугольника</option>
                    <option value="volume">Объем параллелепипеда</option>
                    <option value="average">Среднее арифметическое</option>
                    <option value="sum_squares">Сумма квадратов</option>
                    <option value="max">Максимальное из чисел</option>
                </select>
            </div>
            <div class="form-row">
                <label for="VIEW">Версия:</label>
                <select id="VIEW" name="VIEW">
                    <option value="browser" selected>Версия для просмотра в браузере</option>
                    <option value="print">Версия для печати</option>
                </select>
            </div>
            <div class="form-row checkbox-row">
                <label for="send_mail">отправить результат теста по e-майл</label>
                <input type="checkbox" id="send_mail" name="send_mail" onclick="toggleEmail()">
            </div>
            <div id="email_block" class="hidden">
                <div class="form-row">
                    <label for="MAIL">Ваш е-майл:</label>
                    <input type="text" id="MAIL" name="MAIL">
                </div>
            </div>
            <div class="form-row button-row">
                <button type="submit">Проверить</button>
            </div>
        </form>
    </div>
    <script>
        function toggleEmail() {
            const emailBlock = document.getElementById('email_block');
            const checkbox = document.getElementById('send_mail');
            const emailInput = document.getElementById('MAIL');
            if (checkbox.checked) {
                emailBlock.classList.remove('hidden');
                emailInput.disabled = false;
            } else {
                emailBlock.classList.add('hidden');
                emailInput.disabled = true;
            }
        }
        window.onload = function() {
            const checkbox = document.getElementById('send_mail');
            if (checkbox && checkbox.checked) {
                document.getElementById('email_block').classList.remove('hidden');
                document.getElementById('MAIL').disabled = false;
            } else {
                document.getElementById('MAIL').disabled = true;
            }
        }
    </script>
<?php endif; ?>
<hr>
<h2>Контрольные вопросы к лабораторной работе</h2>
<ol>
    <li><strong>Что такое форма?</strong> — элемент HTML, предназначенный для сбора и отправки данных пользователя на сервер. Оборачивается тегом <code>&lt;form&gt;</code>.</li>
    <li><strong>Какие типы форм бывают?</strong> — по методу передачи: GET и POST. Также различают формы с файлами (enctype="multipart/form-data") и обычные.</li>
    <li><strong>В чем отличие POST- и GET-форм?</strong> — GET передаёт данные в URL (видимы пользователю, ограничена длина), POST — в теле запроса (не отображается в адресной строке, нет ограничения по длине, безопаснее для конфиденциальных данных).</li>
    <li><strong>Какие элементы формы Вы знаете?</strong> — <code>&lt;input&gt;</code> (text, password, checkbox, radio, hidden, file, submit и др.), <code>&lt;select&gt;</code> (выпадающий список), <code>&lt;textarea&gt;</code> (многострочное поле), <code>&lt;button&gt;</code>.</li>
    <li><strong>Как передает свое значение каждый элемент формы в PHP-программу?</strong> — через атрибут <code>name</code>. В PHP значение доступно в массивах <code>$_GET</code> или <code>$_POST</code>. Для чекбоксов значение передаётся только если флажок установлен (по умолчанию "on", можно задать value). Файлы попадают в массив <code>$_FILES</code>.</li>
    <li><strong>Как округлить вещественное число до определенного количества разрядов после запятой?</strong> — функция <code>round($number, $precision)</code>, также можно использовать <code>number_format()</code> или <code>sprintf()</code>.</li>
    <li><strong>Сколько символов может максимально содержать строка в которой буферизуется HTML-код?</strong> — ограничение зависит от доступной памяти (параметр <code>memory_limit</code> в php.ini) и времени выполнения. Теоретически строка может быть до 2 ГБ (если позволяет память и архитектура).</li>
    <li><strong>Как отправить сообщение по электронной почте средствами PHP?</strong> — функция <code>mail($to, $subject, $message, $headers)</code> или использование специализированных библиотек (PHPMailer, SwiftMailer) для более надёжной отправки.</li>
    <li><strong>Почему в листинге А-6.5 при сравнении переданного и вычисленного результата используется оператор эквивалентности?</strong> — оператор <code>===</code> сравнивает значения и их типы. Это важно, потому что числа из формы приходят как строки, а вычисленный результат — число. <code>==</code> мог бы привести типы и дать ложное совпадение (например, "2.0" == 2 — true), но при строгом сравнении типы не совпадают, и тест не будет пройден, если тип не соответствует.</li>
    <li><strong>На какую функцию можно заменить array_key_exists() в листинге А6.6?</strong> — можно использовать <code>isset()</code>, но с осторожностью: <code>isset()</code> вернёт false, если элемент существует, но равен null. <code>array_key_exists()</code> проверяет именно наличие ключа. Альтернатива: <code>!empty()</code> (проверяет и наличие, и непустое значение).</li>
    <li><strong>Можно ли в листинге А6.6 буферизовать сообщение об отправке письма в переменной $out_text? Если да – то как это сделать и будет ли такой код более оптимальным?</strong> — Да, можно просто добавить строку в конец <code>$out_text</code> перед выводом. Например: <code>$out_text .= '&lt;p&gt;Результаты теста были автоматически отправлены...&lt;/p&gt;';</code>. Это не сделает код оптимальнее, но объединит весь вывод в одну переменную, что удобно для последующей обработки (например, если нужно сохранить отчёт в файл). Однако отправка письма при этом должна происходить до добавления этого текста, чтобы не дублировать сообщение.</li>
    <li><strong>Какие события могут обрабатываться в JavaScript? Какими способами можно задать обработчик события для объекта?</strong> — События: клик (<code>onclick</code>), изменение (<code>onchange</code>), отправка формы (<code>onsubmit</code>), загрузка страницы (<code>onload</code>), наведение мыши и др. Способы задания: через атрибуты HTML (<code>&lt;button onclick="func()"&gt;</code>), через свойства DOM-элемента (<code>element.onclick = func</code>), через <code>addEventListener()</code> (рекомендуемый, позволяет добавить несколько обработчиков).</li>
</ol>
</main>
<footer>© 2025 Лабораторная работа 6 Власова Мария 241-352</footer>
</body>
</html>