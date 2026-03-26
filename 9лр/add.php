<?php
// модуль add.php
// форма для добавления новой записи и её обработка

$message = '';
$messageType = '';

// обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    // параметры подключения к БД
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'friends';
    
    $mysqli = mysqli_connect($host, $user, $password, $database);
    if (mysqli_connect_errno()) {
        $message = 'ошибка подключения к БД: ' . mysqli_connect_error();
        $messageType = 'error';
    } else {
        mysqli_set_charset($mysqli, 'utf8');
        
        // получаем данные из формы и экранируем
        $last_name = mysqli_real_escape_string($mysqli, $_POST['last_name']);
        $first_name = mysqli_real_escape_string($mysqli, $_POST['first_name']);
        $middle_name = mysqli_real_escape_string($mysqli, $_POST['middle_name']);
        $gender = mysqli_real_escape_string($mysqli, $_POST['gender']);
        $birth_date = mysqli_real_escape_string($mysqli, $_POST['birth_date']);
        $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
        $address = mysqli_real_escape_string($mysqli, $_POST['address']);
        $email = mysqli_real_escape_string($mysqli, $_POST['email']);
        $comment = mysqli_real_escape_string($mysqli, $_POST['comment']);
        
        $sql = "INSERT INTO contacts (last_name, first_name, middle_name, gender, birth_date, phone, address, email, comment) 
                VALUES ('$last_name', '$first_name', '$middle_name', '$gender', '$birth_date', '$phone', '$address', '$email', '$comment')";
        
        if (mysqli_query($mysqli, $sql)) {
            $message = 'запись добавлена';
            $messageType = 'success';
        } else {
            $message = 'ошибка: запись не добавлена. ' . mysqli_error($mysqli);
            $messageType = 'error';
        }
        mysqli_close($mysqli);
    }
}
?>

<h2>Добавление новой записи</h2>

<?php if ($message): ?>
    <div class="message-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<form method="post" action="/?p=add">
    <input type="hidden" name="action" value="add">
    
    <div class="form-group">
        <label>Фамилия *</label>
        <input type="text" name="last_name" required>
    </div>
    
    <div class="form-group">
        <label>Имя *</label>
        <input type="text" name="first_name" required>
    </div>
    
    <div class="form-group">
        <label>Отчество</label>
        <input type="text" name="middle_name">
    </div>
    
    <div class="form-group">
        <label>Пол</label>
        <select name="gender">
            <option value="м">Мужской</option>
            <option value="ж">Женский</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Дата рождения</label>
        <input type="date" name="birth_date">
    </div>
    
    <div class="form-group">
        <label>Телефон</label>
        <input type="text" name="phone">
    </div>
    
    <div class="form-group">
        <label>Адрес</label>
        <input type="text" name="address">
    </div>
    
    <div class="form-group">
        <label>E-mail</label>
        <input type="email" name="email">
    </div>
    
    <div class="form-group">
        <label>Комментарий</label>
        <textarea name="comment" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <input type="submit" value="Добавить запись">
    </div>
</form>