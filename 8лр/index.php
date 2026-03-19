<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анализ текста - Ввод</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Лабораторная работа № А-8</h1>
        <h2>Анализ текста</h2>
        <p>Введите текст для анализа (русский или английский):</p>

        <!-- форма отправляет данные методом POST на файл result.php -->
        <form action="result.php" method="post">
            <textarea name="data" id="data" rows="10" placeholder="Введите ваш текст здесь..."></textarea>
            <br>
            <button type="submit">Анализировать</button>
        </form>
    </div>
</body>
</html>