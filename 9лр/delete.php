<?php
// модуль delete.php
// удаление записи

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'friends';

$mysqli = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect_errno()) {
    echo '<div class="src_error">ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    exit();
}
mysqli_set_charset($mysqli, 'utf8');

$message = '';
$messageType = '';
$deletedLastName = '';

// обработка удаления
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    // сначала получаем фамилию для сообщения
    $nameResult = mysqli_query($mysqli, "SELECT last_name FROM contacts WHERE id=$id");
    if ($nameResult && mysqli_num_rows($nameResult) > 0) {
        $nameRow = mysqli_fetch_assoc($nameResult);
        $deletedLastName = $nameRow['last_name'];
    }
    
    // удаляем запись
    $sql = "DELETE FROM contacts WHERE id=$id";
    if (mysqli_query($mysqli, $sql)) {
        $message = 'запись с фамилией "' . htmlspecialchars($deletedLastName) . '" удалена';
        $messageType = 'success';
    } else {
        $message = 'ошибка: запись не удалена. ' . mysqli_error($mysqli);
        $messageType = 'error';
    }
}

// получаем список всех записей
$result = mysqli_query($mysqli, "SELECT id, last_name, first_name, middle_name FROM contacts ORDER BY last_name, first_name");
?>

<h2>Удаление записи</h2>

<?php if ($message): ?>
    <div class="message-<?php echo $messageType; ?>"><?php echo $message; ?></div>
<?php endif; ?>

<div class="links-list">
    <strong>Выберите запись для удаления:</strong><br>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // формируем ФИО: фамилия и инициалы
            $initials = '';
            if ($row['first_name']) {
                $initials .= substr($row['first_name'], 0, 1) . '.';
            }
            if ($row['middle_name']) {
                $initials .= substr($row['middle_name'], 0, 1) . '.';
            }
            $fullName = htmlspecialchars($row['last_name'] . ' ' . $initials);
            echo '<a href="?p=delete&delete_id=' . $row['id'] . '" onclick="return confirm(\'удалить запись ' . $fullName . '?\')">' . $fullName . '</a>';
        }
    } else {
        echo 'записей пока нет';
    }
    ?>
</div>

<?php mysqli_close($mysqli); ?>