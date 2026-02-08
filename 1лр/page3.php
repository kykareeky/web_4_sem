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
        <h1>Практические примеры</h1>
        <h2>Пример PHP-кода для динамической загрузки фото</h2>

        <pre><code>
    &lt;?php
        $s = date('s');
        $os = $s % 2;
        if($os === 0) 
            $name='photos/foto1.jpg';
        else 
            $name='photos/foto2.jpg';
        echo '&lt;img src="'. $name. '" alt="Фото"&gt;';
    ?&gt;
</code></pre>
       
        
        <h2>Различия языков</h2>
        <table>
            <?php
            echo '<tr>';
            echo '<td><b>Статический html</b></td>';
            echo '<td><b>Динамический PHP</b></td>';
            echo '</tr>';
            ?>
            <tr>
                <td><?php echo "Файл .html"; ?></td>
                <td><?php echo "Файл .php"; ?></td>
            </tr>
            <tr>
                <td><?php echo "Контент неизменен"; ?></td>
                <td><?php echo "Контент генерируется"; ?></td>
            </tr>
            <tr>
                <td><?php echo "Нет связи с БД"; ?></td>
                <td><?php echo "Работа с БД"; ?></td>
            </tr>
            <tr>
                <td><?php echo "Быстрая загрузка"; ?></td>
                <td><?php echo "Требует обработки"; ?></td>
            </tr>
        </table>

        <h2>Структура работы</h2>
        <pre>
            lab1/
                ├── index.php      (главная, теория PHP)
                ├── page2.php      (ответы на вопросы)
                ├── page3.php      (практические примеры)
                ├── style.css      (стили)
                └── photos/  
        </pre>


        <h2>Фотографии</h2>
        <?php
        $second = date('s');
        $photo_num = ($second % 2 == 0) ? 5 : 6;
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