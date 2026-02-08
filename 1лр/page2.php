<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Власова Мария, 241-352, лаб. работа №1"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            $menu = [
                'index.php' => 'Главная',
                'page2.php' => 'Вопросы',
                'page3.php' => 'Примеры'
            ];
            
            foreach ($menu as $link => $name) {
                $class = ($current_page == $link) ? 'class="selected"' : '';
                echo "<a href=\"$link\" $class>$name</a> ";
            }
            ?>
        </nav>
    </header>

    <main>
        <h1>Что изучили по итогам лаб.работы №1:</h1>
        <h2>Ответы на контрольные вопросы</h2>
        <ol style="list-style: none; padding-left: 40px;">
            <li><strong>Что такое PHP?</strong> — Серверный язык для создания динамических веб-страниц.</li>
            <li><strong>Какое основное назначение PHP?</strong> — Динамически формировать HTML-код страниц, обрабатывать формы, работать с базами данных, управлять сессиями.</li>
            <li><strong>В чем отличие статического и динамического контента?</strong> — Статический контент хранится в готовых файлах (HTML, CSS, изображения), а динамический генерируется сервером в момент запроса.</li>
            <li><strong>Как внедрить PHP-код на статическую страницу?</strong> — Переименовать файл с ".html" на ".php" и добавить PHP-код внутри тегов.</li>
            <li><strong>Какие общепринятые требования к страницам с PHP-кодом?</strong> — Файл должен иметь расширение ".php", сервер должен поддерживать PHP, код должен быть структурирован и безопасен.</li>
            <li><strong>Как трансформировать код вывода ссылки А-1.8 так, чтобы он не содержал статических фрагментов?</strong> — Весь HTML-код ссылки, включая теги, должен генерироваться через "echo" или переменные PHP, без статичных частей в HTML-файле.</li>
            <li><strong>Как будет выглядеть словесное описание алгоритмов, реализуемых в листингах А-1.5 … А-1.8?</strong>
                <ul>
                    <li><strong>А-1.5:</strong> Вывести готовую HTML-ссылку через `echo`.</li>
                    <li><strong>А-1.6:</strong> Разделить вывод ссылки на два PHP-блока без промежуточных пробелов.</li>
                    <li><strong>А-1.7:</strong> Встроить PHP-код внутрь HTML-тегов для вывода атрибутов и текста ссылки.</li>
                    <li><strong>А-1.8:</strong> Использовать переменные для хранения параметров ссылки и условно выводить класс, если страница активна.</li>
                </ul>
            </li>
        </ol>
        
        <h2>Сравнительная таблица</h2>
        <table>
            <?php
            echo '<tr>';
            echo '<td><b>Язык</b></td>';
            echo '<td><b>Дата создания</b></td>';
            echo '<td><b>Применение</b></td>';
            echo '</tr>';
            ?>
            <tr>
                <td><?php echo "PHP"; ?></td>
                <td><?php echo "1995"; ?></td>
                <td><?php echo "Веб-разработка"; ?></td>
            </tr>
            <tr>
                <td><?php echo "HTML"; ?></td>
                <td><?php echo "1993"; ?></td>
                <td><?php echo "Разметка веб-страниц"; ?></td>
            </tr>
            <tr>
                <td><?php echo "CSS"; ?></td>
                <td><?php echo "1996"; ?></td>
                <td><?php echo "Стилизация веб-страниц"; ?></td>
            </tr>
        </table>

        <h2>Фотографии</h2>
        <?php
        $second = date('s');
        $photo_num = ($second % 2 == 0) ? 3 : 4;
        $photo_path = "photos/foto$photo_num.jpg";
        echo "<img src=\"$photo_path\" alt=\"Динамическое фото\">";
        ?>
        <br><br>
    </main>

    <footer>
        <?php date_default_timezone_set('Europe/Moscow');?>
        <p>Сформировано <?php echo date('d.m.Y в H:i:s'); ?></p>
    </footer>
</body>
</html> 