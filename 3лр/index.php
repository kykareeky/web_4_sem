<?php
session_start();// начинаем сессию в самом начале
$result = '';// обработка GET-параметров


if (!isset($_SESSION['counter'])) {// инициализация счетчика
    $_SESSION['counter'] = 0;
}


if (isset($_GET['store'])) {// получение текущего результата из параметра store
    $result = $_GET['store'];
}


if (isset($_GET['key'])) {// обработка нажатия кнопки
    if ($_GET['key'] == 'reset') {
        $result = '';
    } else {
        $result .= $_GET['key'];
    }
    
    
    $_SESSION['counter']++;// увеличиваем счетчик 
}

// кодируем результат для использования в URL
$encodedResult = urlencode($result);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Власова Мария, 241-352, лаб. работа №3"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
    <header>
        <div class="header-text">
            <p>Лабораторная работа №3  Власова Мария 241-352</p>
        </div>
    </header>
    <div class="calculator">
        <h2>Веб-калькулятор ввода цифр</h2>
        
        <div class="result">
            <?php echo htmlspecialchars($result); ?>
        </div>
        
        <div class="buttons">
            <?php
            
            for ($i = 1; $i <= 9; $i++) {// кнопки цифр от 1 до 9
                $url = "?key=" . $i . "&store=" . $encodedResult;
                echo '<a href="' . $url . '" class="button">' . $i . '</a>';
            }
            ?>
        </div>
        
        <div class="button-row">
            <?php
            
            $url = "?key=0&store=" . $encodedResult;// Кнопка 0 - по центру
            echo '<a href="' . $url . '" class="button">0</a>';
            ?>
        </div>
        
        <div class="button-row">
            <?php
            
            echo '<a href="?key=reset" class="button reset">СБРОС</a>';//  СБРОС
            ?>
        </div>
    </div>
    </main>
</body>
</html>