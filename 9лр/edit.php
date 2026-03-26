<?php
// модуль edit.php
// редактирование существующей записи

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

// обработка изменения записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $last_name = mysqli_real_escape_string($mysqli, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($mysqli, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($mysqli, $_POST['middle_name']);
    $gender = mysqli_real_escape_string($mysqli, $_POST['gender']);
    $birth_date = mysqli_real_escape_string($mysqli, $_POST['birth_date']);
    $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $address = mysqli_real_escape_string($mysqli, $_POST['address']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $comment = mysqli_real_escape_string($mysqli, $_POST['comment']);
    
    $sql = "UPDATE contacts SET 
            last_name='$last_name',
            first_name='$first_name',
            middle_name='$middle_name',
            gender='$gender',
            birth_date='$birth_date',
            phone='$phone',
            address='$address',
            email='$email',
            comment='$comment'
            WHERE id=$id";
    
    if (mysqli_query($mysqli, $sql)) {
        $message = 'запись изменена';
        $messageType = 'success';
    } else {
        $message = 'ошибка: запись не изменена. ' . mysqli_error($mysqli);
        $messageType = 'error';
    }
}

// определяем текущую запись
$currentId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
$currentRow = null;

if ($currentId) {
    // получаем запись по id
    $result = mysqli_query($mysqli, "SELECT * FROM contacts WHERE id=$currentId LIMIT 1");
    if ($result && mysqli_num_rows($result) > 0) {
        $currentRow = mysqli_fetch_assoc($result);
    }
}

// если текущая запись не найдена, берём первую
if (!$currentRow) {
    $result = mysqli_query($mysqli, "SELECT * FROM contacts LIMIT 1");
    if ($result && mysqli_num_rows($result) > 0) {
        $currentRow = mysqli_fetch_assoc($result);
        $currentId = $currentRow['id'];
    }
}

// получаем все записи для списка ссылок
$allResult = mysqli_query($mysqli, "SELECT id, last_name, first_name, middle_name FROM contacts ORDER BY last_name, first_name");
?>

<h2>Редактирование записи</h2>

<?php if ($message): ?>
    <div class="message-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="links-list">
    <strong>Выберите запись для редактирования:</strong><br>
    <?php
    if ($allResult && mysqli_num_rows($allResult) > 0) {
        while ($row = mysqli_fetch_assoc($allResult)) {
            $fullName = htmlspecialchars($row['last_name'] . ' ' . $row['first_name'] . ' ' . ($row['middle_name'] ? substr($row['middle_name'], 0, 1) . '.' : ''));
            if ($currentId == $row['id']) {
                echo '<span class="current">' . $fullName . '</span>';
            } else {
                echo '<a href="?p=edit&id=' . $row['id'] . '">' . $fullName . '</a>';
            }
        }
    } else {
        echo 'записей пока нет';
    }
    ?>
</div>

<?php if ($currentRow): ?>
    <form method="post" action="/?p=edit">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo $currentRow['id']; ?>">
        
        <div class="form-group">
            <label>Фамилия *</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($currentRow['last_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Имя *</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($currentRow['first_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Отчество</label>
            <input type="text" name="middle_name" value="<?php echo htmlspecialchars($currentRow['middle_name']); ?>">
        </div>
        
        <div class="form-group">
            <label>Пол</label>
            <select name="gender">
                <option value="м" <?php echo $currentRow['gender'] == 'м' ? 'selected' : ''; ?>>Мужской</option>
                <option value="ж" <?php echo $currentRow['gender'] == 'ж' ? 'selected' : ''; ?>>Женский</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Дата рождения</label>
            <input type="date" name="birth_date" value="<?php echo htmlspecialchars($currentRow['birth_date']); ?>">
        </div>
        
        <div class="form-group">
            <label>Телефон</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($currentRow['phone']); ?>">
        </div>
        
        <div class="form-group">
            <label>Адрес</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($currentRow['address']); ?>">
        </div>
        
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($currentRow['email']); ?>">
        </div>
        
        <div class="form-group">
            <label>Комментарий</label>
            <textarea name="comment" rows="3"><?php echo htmlspecialchars($currentRow['comment']); ?></textarea>
        </div>
        
        <div class="form-group">
            <input type="submit" value="Изменить запись">
        </div>
    </form>
<?php else: ?>
    <div class="src_text">записей пока нет</div>
<?php endif; ?>

<?php mysqli_close($mysqli); ?>