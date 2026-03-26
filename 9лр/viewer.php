<?php
// модуль viewer.php
// содержит функцию для вывода списка контактов с пагинацией и сортировкой

function getContactsList($sort, $page) {
    // параметры подключения к БД
    $host = 'localhost';
    $user = 'root';        // измените под свои настройки
    $password = '';        // измените под свои настройки
    $database = 'friends';
    
    // подключаемся к БД
    $mysqli = mysqli_connect($host, $user, $password, $database);
    if (mysqli_connect_errno()) {
        return '<div class="src_error">ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    }
    
    // устанавливаем кодировку
    mysqli_set_charset($mysqli, 'utf8');
    
    // определяем общее количество записей
    $countRes = mysqli_query($mysqli, 'SELECT COUNT(*) FROM contacts');
    if (mysqli_errno($mysqli) || !$countRes) {
        return '<div class="src_error">ошибка выполнения запроса</div>';
    }
    $totalRow = mysqli_fetch_row($countRes);
    $total = $totalRow[0];
    
    if ($total == 0) {
        return '<div class="src_text">в таблице нет данных</div>';
    }
    
    $recordsPerPage = 10;
    $totalPages = ceil($total / $recordsPerPage);
    
    // корректируем номер страницы
    if ($page >= $totalPages) {
        $page = $totalPages - 1;
    }
    if ($page < 0) {
        $page = 0;
    }
    $offset = $page * $recordsPerPage;
    
    // формируем сортировку
    switch ($sort) {
        case 'fam':
            $orderBy = 'last_name, first_name';
            break;
        case 'birth':
            $orderBy = 'birth_date';
            break;
        default:
            $orderBy = 'id';
    }
    
    // запрос на выборку данных
    $sql = "SELECT id, last_name, first_name, middle_name, gender, birth_date, phone, address, email, comment 
            FROM contacts 
            ORDER BY $orderBy 
            LIMIT $offset, $recordsPerPage";
    
    $result = mysqli_query($mysqli, $sql);
    if (mysqli_errno($mysqli)) {
        return '<div class="src_error">ошибка выборки данных: ' . mysqli_error($mysqli) . '</div>';
    }
    
    // формируем таблицу
    $html = '<table>';
    $html .= '<tr>
                <th>id</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Пол</th>
                <th>Дата рождения</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>E-mail</th>
                <th>Комментарий</th>
              </tr>';
    
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['last_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['first_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['middle_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['birth_date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    
    // пагинация
    if ($totalPages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 0; $i < $totalPages; $i++) {
            if ($i == $page) {
                $html .= '<span>' . ($i + 1) . '</span>';
            } else {
                $html .= '<a href="?p=viewer&sort=' . urlencode($sort) . '&pg=' . $i . '">' . ($i + 1) . '</a>';
            }
        }
        $html .= '</div>';
    }
    
    mysqli_close($mysqli);
    return $html;
}
?>