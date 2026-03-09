<!DOCTYPE html>
<html>
<head>
    <title>Лабораторная работа А-7</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        // функция добавляет одно поле ввода
        function addElement() {
            var table = document.getElementById('elements');
            var rowCount = table.rows.length;          // текущее количество строк
            var newRow = table.insertRow(rowCount);    // добавляем строку

            // ячейка с номером элемента
            var cellNum = newRow.insertCell(0);
            cellNum.className = 'element-index';
            cellNum.innerHTML = rowCount;               // индекс нового элемента

            // ячейка с полем ввода
            var cellInput = newRow.insertCell(1);
            cellInput.innerHTML = '<input type="text" name="element' + rowCount + '">';

            // обновить скрытое поле с количеством элементов
            document.getElementById('arrLength').value = rowCount + 1;
        }

        // при загрузке страницы установить начальное значение скрытого поля
        window.onload = function() {
            document.getElementById('arrLength').value = document.getElementById('elements').rows.length;
        };
    </script>
</head>
<body>
    <header>Лабораторная работа № А-7</header>
    <main>
        <div class="form-container">
            <form method="POST" action="sort.php" target="_blank">
                <div class="form-row">
                    <label>Элементы массива:</label>
                    <table id="elements">
                        <tr>
                            <td class="element-index">0</td>
                            <td><input type="text" name="element0"></td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="arrLength" id="arrLength" value="1">

                <div class="form-row">
                    <label>Выберите алгоритм:</label>
                    <select name="algorithm">
                        <option value="selection">Сортировка выбором</option>
                        <option value="bubble">Пузырьковый алгоритм</option>
                        <option value="shell">Алгоритм Шелла</option>
                        <option value="gnome">Алгоритм садового гнома</option>
                        <option value="quick">Быстрая сортировка</option>
                        <option value="builtin">Встроенная функция PHP</option>
                    </select>
                </div>

                <div class="form-row button-row">
                    <button type="button" onclick="addElement()">Добавить еще один элемент</button>
                    <button type="submit">Сортировать массив</button>
                </div>
            </form>
        </div>
    </main>
    <footer>Власова Мария 241-352 2026</footer>
</body>
</html>