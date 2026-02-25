<?php
// Инициализация хранилища
if (!isset($_GET['store'])) {
    $_GET['store'] = ''; // пустое хранилище при первой загрузке
}

// cчетчик нажатий через GET
if (!isset($_GET['counter'])) {//проверяет установлена ли переменная и не равняется ли она null
    $counter = 0;
} else {
    $counter = (int)$_GET['counter'];//заводим переменную, если ее нет
}

//Листинг А‐3. 2
// обработка нажатия кнопки
if (isset($_GET['key'])) {
    if ($_GET['key'] == 'reset') {
        $_GET['store'] = ''; // очистка при сбросе
    } else {
        $_GET['store'] .= $_GET['key']; // добавление цифры
    }
    $counter++;// lkz aenthf
}

// kодируем store для безопасной передачи в URL
$encodedStore = urlencode($_GET['store']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Власова Мария, 241-352, лаб. работа №3</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-text">
            <p>Лабораторная работа №3 Власова Мария 241-352</p>
        </div>
    </header>
    
    <main>
        <div class="calculator">
            <h2>Веб-калькулятор ввода цифр</h2>
            
            <!-- отображение текущего результата -->
            <div class="result">
                <?php echo htmlspecialchars($_GET['store']); ?>
            </div>
            
            <div class="buttons">
                <?php
                // генерация кнопок с цифрами от 1 до 9
                for ($i = 1; $i <= 9; $i++) {
                    $url = "?key=" . $i . "&store=" . $encodedStore . "&counter=" . $counter;
                    echo '<a href="' . $url . '" class="button">' . $i . '</a>';
                }
                ?>
            </div>
            
            <div class="button-row">
                <?php
                // кнопка для цифры 0
                $url = "?key=0&store=" . $encodedStore . "&counter=" . $counter;
                echo '<a href="' . $url . '" class="button">0</a>';
                ?>
            </div>
            
            <div class="button-row">
                <?php
                // кнопка сброса (без store, как в методичке)
                echo '<a href="?key=reset&counter=' . $counter . '" class="button reset">СБРОС</a>';
                ?>
            </div>
        </div>

        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <div class="answers">
            <h2>Ответы на вопросы:</h2>
            <ol>
                <li>
                    <strong>Как передать GET-параметр через ссылку?</strong> — GET-параметр передается через ссылку путем добавления к URL знака вопроса "?" и имени параметра со значением в формате: <code>?имя=значение</code>. Например: <code>&lt;a href="page.php?key=1"&gt;1&lt;/a&gt;</code>. В данном коде это реализовано в строках с формированием переменной <code>$url</code>.
                </li>
                
                <li>
                    <strong>Что такое $_GET?</strong> — $_GET — это суперглобальный массив PHP, который содержит данные, переданные через URL методом GET. Ключами массива являются имена параметров, значениями — их значения. Доступ к данным осуществляется через <code>$_GET['key']</code> или <code>$_GET['store']</code>, как используется в коде.
                </li>
                
                <li>
                    <strong>Как передать в ссылке несколько параметров?</strong> — Несколько GET-параметров передаются через символ амперсанда "&" между парами ключ=значение. Пример: <code>?key=1&store=123</code>. В коде это реализовано в строке: <code>$url = "?key=" . $i . "&store=" . $encodedStore;</code>.
                </li>
                
                <li>
                    <strong>Как проверить существование переменной?</strong> — Для проверки существования переменной в PHP используется функция <code>isset()</code>. Она возвращает true, если переменная существует и не равна null. Пример из кода: <code>if (isset($_GET['store']))</code> и <code>if (isset($_GET['key']))</code>.
                </li>
                
                <li>
                    <strong>Как узнать, был ли передан параметр в скрипт?</strong> — Узнать, был ли передан параметр, можно с помощью функции <code>isset($_GET['имя_параметра'])</code>. Если параметр не был передан, isset вернет false. Это используется в коде для проверки наличия параметров 'store' и 'key'.
                </li>
                
                <li>
                    <strong>Как узнать значение переданного в сценарий параметра?</strong> — Значение переданного параметра получается через обращение к элементу массива $_GET: <code>$_GET['имя_параметра']</code>. Примеры из кода: <code>$_GET['store']</code>, <code>$_GET['key']</code>.
                </li>
                
                <li>
                    <strong>Изменится ли работа программы А-3.1, если между двумя условными операторами поставить else? Если да – то как?</strong> — Да, изменится. Без else оба условия проверяются независимо. С else второй блок выполнится только если первое условие ложно. В А-3.1 проверяются разные параметры, поэтому использование else приведет к тому, что если передан store, проверка key будет пропущена, и нажатие кнопки не обработается.
                </li>
                
                <li>
                    <strong>Изменится ли работа программы А-3.4, если между двумя условными операторами убрать else? Если да – то как?</strong> — Да, изменится. Без else оба условия будут проверяться всегда. В А-3.4 проверяются: <code>$_GET['key'] == 'reset'</code> и остальные значения key. Если убрать else и оставить два отдельных if, то при key=reset сработают оба: сначала сброс, а потом добавление 'reset' к результату — логика нарушится.
                </li>
                
                <li>
                    <strong>Изменится ли работа программы А-3.4, если убрать второй условный оператор, оставив else? Если да – то как?</strong> — Да, изменится. Конструкция if-else без второго if не имеет смысла. В А-3.4 else относится ко второму условию (проверка reset). Если оставить только else без if, возникнет синтаксическая ошибка PHP, и скрипт не выполнится.
                </li>
            </ol>
        </div>
    </main>
    
    <footer>
        <p>Общее число нажатий: <?php echo $counter; ?></p>
    </footer>
</body>
</html>