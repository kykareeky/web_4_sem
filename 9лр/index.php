<?php
// главный файл сайта
// подключаем модуль меню
require 'menu.php';

// получаем строку меню и выводим её
$menuHtml = getMenu();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записная книжка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>Записная книжка</header>
    
    <?php echo $menuHtml; ?>
    
    <div class="container">
        <?php
        // определяем какой модуль подключить
        $page = isset($_GET['p']) ? $_GET['p'] : 'viewer';
        
        // проверяем допустимость значения параметра
        $allowedPages = ['viewer', 'add', 'edit', 'delete'];
        if (!in_array($page, $allowedPages)) {
            echo '<div class="src_error">Ошибка: недопустимый параметр</div>';
        } else {
            // подключаем соответствующий модуль
            if ($page == 'viewer') {
                include 'viewer.php';
                // устанавливаем параметры по умолчанию
                $sort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';
                $pg = isset($_GET['pg']) && is_numeric($_GET['pg']) && $_GET['pg'] >= 0 ? (int)$_GET['pg'] : 0;
                // вызываем функцию из модуля viewer
                echo getContactsList($sort, $pg);
            } else {
                if (file_exists($page . '.php')) {
                    include $page . '.php';
                } else {
                    echo '<div class="src_error">Ошибка: модуль не найден</div>';
                }
            }
        }
        ?>
        <!-- ответы на контрольные вопросы -->
<div class="questions-section">
    <h2>Ответы на контрольные вопросы</h2>
    <ol class="questions-list">
        <li><strong>Что такое внешние модули? Зачем они нужны? Как они подключаются?</strong>
            <div class="answer"><p>Внешние модули — это отдельные файлы с PHP-кодом, которые подключаются к основной программе. Они нужны для повторного использования кода, упрощения разработки и поддержки. Подключаются с помощью конструкций <code>include</code>, <code>require</code> и их вариантов <code>_once</code>.</p></div>
        </li>
        <li><strong>Что такое библиотека функций? Зачем они нужны? Как они подключаются?</strong>
            <div class="answer"><p>Библиотека функций — это набор пользовательских функций, собранных в одном файле. Она нужна для организации повторно используемого кода. Подключается так же, как и обычные модули, с помощью <code>include</code> или <code>require</code>.</p></div>
        </li>
        <li><strong>В чем отличие конструкций include и require?</strong>
            <div class="answer"><p><code>include</code> при ошибке выдаёт предупреждение и продолжает выполнение скрипта, а <code>require</code> при ошибке вызывает фатальную ошибку и останавливает выполнение.</p></div>
        </li>
        <li><strong>В чем отличие конструкций include и include_once?</strong>
            <div class="answer"><p><code>include_once</code> проверяет, был ли файл уже подключён, и если да — не подключает его повторно. <code>include</code> подключает файл каждый раз при вызове.</p></div>
        </li>
        <li><strong>В чем отличие конструкций require и require_once?</strong>
            <div class="answer"><p><code>require_once</code> также проверяет, был ли файл уже подключён, и предотвращает повторное подключение, в отличие от <code>require</code>.</p></div>
        </li>
        <li><strong>Что такое SQL?</strong>
            <div class="answer"><p>SQL (Structured Query Language) — язык структурированных запросов, используемый для работы с реляционными базами данных: выборки, вставки, обновления и удаления данных.</p></div>
        </li>
        <li><strong>Основные операторы языка SQL?</strong>
            <div class="answer"><p>Основные операторы: <code>SELECT</code> (выборка), <code>INSERT</code> (вставка), <code>UPDATE</code> (обновление), <code>DELETE</code> (удаление), <code>CREATE</code> (создание), <code>ALTER</code> (изменение), <code>DROP</code> (удаление).</p></div>
        </li>
        <li><strong>Оператор INSERT. Для чего он предназначен, какие формы может иметь?</strong>
            <div class="answer"><p><code>INSERT</code> предназначен для добавления записей в таблицу. Формы: <code>INSERT INTO table VALUES (...)</code> (все поля по порядку), <code>INSERT INTO table (col1, col2) VALUES (val1, val2)</code> (явное указание полей), <code>INSERT INTO table SELECT ...</code> (добавление из другой таблицы).</p></div>
        </li>
        <li><strong>Источники данных для оператора INSERT: как добавить записи в таблицу из другой таблицы?</strong>
            <div class="answer"><p>Используется форма <code>INSERT INTO table1 SELECT col1, col2 FROM table2 WHERE ...</code>. Результат SELECT-запроса вставляется в целевую таблицу.</p></div>
        </li>
        <li><strong>Оператор UPDATE: назначение, синтаксис, форма записи?</strong>
            <div class="answer"><p><code>UPDATE</code> используется для изменения существующих записей. Синтаксис: <code>UPDATE table SET col1=val1, col2=val2 WHERE condition</code>. Без WHERE обновляются все записи.</p></div>
        </li>
        <li><strong>Формы оператора SELECT?</strong>
            <div class="answer"><p><code>SELECT * FROM table</code> (все поля), <code>SELECT col1, col2 FROM table</code> (выбранные поля), <code>SELECT DISTINCT col FROM table</code> (уникальные значения), с условиями <code>WHERE</code>, сортировкой <code>ORDER BY</code>, группировкой <code>GROUP BY</code>, ограничением <code>LIMIT</code>.</p></div>
        </li>
        <li><strong>Условие отбора в операторе SELECT?</strong>
            <div class="answer"><p>Условие указывается после <code>WHERE</code>. Используются операторы сравнения (<code>=</code>, <code>&gt;</code>, <code>&lt;</code>, <code>&lt;&gt;</code>), логические (<code>AND</code>, <code>OR</code>, <code>NOT</code>), а также <code>LIKE</code>, <code>IN</code>, <code>BETWEEN</code>.</p></div>
        </li>
        <li><strong>Функции в операторе SELECT?</strong>
            <div class="answer"><p>Агрегатные функции: <code>COUNT()</code>, <code>SUM()</code>, <code>AVG()</code>, <code>MIN()</code>, <code>MAX()</code>. Строковые: <code>CONCAT()</code>, <code>SUBSTRING()</code>. Математические: <code>ROUND()</code>, <code>FLOOR()</code>. Функции для работы с датами и другие.</p></div>
        </li>
        <li><strong>Сортировка записей и ее типы?</strong>
            <div class="answer"><p>Сортировка задаётся <code>ORDER BY column [ASC|DESC]</code>. ASC — по возрастанию (по умолчанию), DESC — по убыванию. Можно сортировать по нескольким полям.</p></div>
        </li>
        <li><strong>Группировка записей: назначение и отличие от сортировки?</strong>
            <div class="answer"><p>Группировка (<code>GROUP BY</code>) объединяет записи с одинаковыми значениями указанных полей. Сортировка (<code>ORDER BY</code>) только упорядочивает записи, не объединяя их. Группировка часто используется с агрегатными функциями.</p></div>
        </li>
        <li><strong>Работа SQL-функций с группировкой и без группировки?</strong>
            <div class="answer"><p>Без группировки агрегатные функции применяются ко всем записям результата. С группировкой — к каждой группе отдельно, возвращая результат для каждой группы.</p></div>
        </li>
        <li><strong>Ограничение результата SQL-запроса: способы и параметры?</strong>
            <div class="answer"><p>Используется <code>LIMIT</code>. <code>LIMIT n</code> — первые n записей. <code>LIMIT offset, count</code> — count записей с пропуском offset первых. Применяется для пагинации.</p></div>
        </li>
        <li><strong>Псевдонимы полей в операторе SELECT?</strong>
            <div class="answer"><p>Псевдонимы задаются ключевым словом <code>AS</code> или просто пробелом: <code>SELECT column AS alias</code>. Используются для удобства чтения или когда имя поля содержит вычисления.</p></div>
        </li>
        <li><strong>Расширение i – назначение и способ применения?</strong>
            <div class="answer"><p>MySQLi — расширение PHP для работы с MySQL. Поддерживает процедурный и объектно-ориентированный стили, подготовленные запросы. Применяется для подключения, выполнения запросов и обработки результатов.</p></div>
        </li>
        <li><strong>Отличие процедурного и объектно-ориентированного стиля в MySQLi?</strong>
            <div class="answer"><p>Процедурный стиль использует функции (<code>mysqli_connect()</code>, <code>mysqli_query()</code>). Объектно-ориентированный — методы объекта (<code>$mysqli->query()</code>). Оба дают одинаковую функциональность, ООП-стиль более удобен в сложных проектах.</p></div>
        </li>
        <li><strong>Буферизированные и не буферизированные запросы в MySQLi?</strong>
            <div class="answer"><p>Буферизированные запросы загружают весь результат в память сразу, позволяя использовать <code>num_rows</code>. Небуферизированные — экономят память, но требуют закрытия курсора перед следующим запросом.</p></div>
        </li>
        <li><strong>Функции и методы расширения MySQLi?</strong>
            <div class="answer"><p><code>mysqli_connect()</code>, <code>mysqli_query()</code>, <code>mysqli_fetch_assoc()</code>, <code>mysqli_num_rows()</code>, <code>mysqli_real_escape_string()</code>, <code>mysqli_prepare()</code>, <code>mysqli_stmt_bind_param()</code> и другие.</p></div>
        </li>
        <li><strong>Что такое PDO?</strong>
            <div class="answer"><p>PDO (PHP Data Objects) — универсальное расширение для работы с базами данных. Поддерживает множество СУБД, подготовленные запросы, обработку ошибок через исключения.</p></div>
        </li>
        <li><strong>Чем отличаются расширения PDO и MySQLi?</strong>
            <div class="answer"><p>MySQLi работает только с MySQL, PDO — с 12 различными СУБД. PDO имеет более удобный интерфейс для подготовленных запросов, использует исключения, но не поддерживает некоторые специфичные для MySQL функции.</p></div>
        </li>
        <li><strong>В чем отличие именованных и неименованных псевдопеременных в PDO?</strong>
            <div class="answer"><p>Неименованные псевдопеременные — <code>?</code>, порядок передачи значений важен. Именованные — <code>:name</code>, значения передаются в ассоциативном массиве с соответствующими ключами.</p></div>
        </li>
        <li><strong>Способы передачи значений в псевдопеременные запроса?</strong>
            <div class="answer"><p>Через <code>bindParam()</code>/<code>bindValue()</code> или через массив в <code>execute()</code>. При передаче массива значения подставляются по порядку для <code>?</code> или по ключам для именованных псевдопеременных.</p></div>
        </li>
        <li><strong>Какова цель и преимущества использования псевдопеременных в SQL?</strong>
            <div class="answer"><p>Цель — защита от SQL-инъекций и повышение производительности при многократном выполнении однотипных запросов. Преимущества: автоматическое экранирование, возможность кэширования запроса на стороне сервера БД.</p></div>
        </li>
        <li><strong>Как изменится работа программы, если в листинге B-1.1 в ссылках подменю не будет передаваться параметр "p"?</strong>
            <div class="answer"><p>Если не передавать <code>p</code>, то при переходе по ссылкам подменю параметр <code>p</code> потеряется, и меню может перестать выделять активный пункт "Просмотр", так как текущее значение <code>p</code> станет неопределённым.</p></div>
        </li>
        <li><strong>Почему в листинге B-1.4 при выводе пагинации в тексте ссылок страницы нумеруются, начиная с единицы, а в адресе ссылок начиная с нуля?</strong>
            <div class="answer"><p>В адресе используется номер страницы для SQL-запроса, где первая страница имеет индекс 0. Для пользователя удобнее отображать нумерацию с 1, поэтому в тексте ссылки прибавляется 1.</p></div>
        </li>
        <li><strong>В каких случаях имеет смысл делать отдельный SQL-запрос для определения текущей записи из списка выводимых записей таблицы базы данных?</strong>
            <div class="answer"><p>Когда количество записей велико, а текущая запись может не попасть в выводимую страницу (при пагинации). Также когда в таблице много полей или большие объёмы данных, чтобы не загружать лишнюю информацию.</p></div>
        </li>
        <li><strong>Как изменится работа программы в листинге B-1.11, если внутренний блок try-catch вынести в отдельную функцию?</strong>
            <div class="answer"><p>Исключения, выброшенные внутри функции, будут перехвачены ближайшим catch в месте вызова функции, если он есть. Если вынести блок try-catch в функцию, структура выполнения сохранится, но обработка исключений будет происходить внутри этой функции.</p></div>
        </li>
        <li><strong>Как будет выглядеть сформированная программой на листинге B-1.11 строка, если во вложенный try-catch блок добавить finally, выводящий символ «Z»?</strong>
            <div class="answer"><p>Строка будет "12A4B6Z", так как <code>finally</code> выполняется всегда, независимо от наличия исключения. Сначала выводятся 1, 2, исключение A перехватывается, выводится A, затем 4, исключение B, перехватывается внешним catch, выводится B, 6, а затем из finally выводится Z.</p></div>
        </li>
    </ol>
</div>
    </div>
    
    <footer>&copy; Записная книжка, 2025</footer>
</body>
</html>