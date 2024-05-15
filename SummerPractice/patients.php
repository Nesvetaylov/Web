<?php
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-cache, must-revalidate');

session_start(); // Начало сессии для хранения данных формы и сообщений об ошибках

if ($_SERVER['REQUEST_METHOD']=='GET') {
    $messages=array();
    if (!empty($_COOKIE['SAVE'])) {
        setcookie('SAVE', '', 100000);
        $messages[]='Спасибо, результаты сохранены.';
    }
    
    $errors = array();
    $errors['LAST_NAME'] = !empty($_COOKIE['LAST_NAME_errors']);
    $errors['FIRST_NAME'] = !empty($_COOKIE['FIRST_NAME_errors']);
    $errors['MIDDLE_NAME'] = !empty($_COOKIE['MIDDLE_NAME_errors']);
    $errors['BIRTHDATE'] = !empty($_COOKIE['BIRTHDATE_errors']);
    $errors['ADDRESS'] = !empty($_COOKIE['ADDRESS_errors']);

    if($errors['LAST_NAME']) {
        setcookie('LAST_NAME_error', '', time()-24*60*60);
        setcookie('LAST_NAME_error', '', time()-24*60*60);
        $messages[]='<div class="error">Заполните фамилию пациента</div>';
    }
    if($errors['FIRST_NAME']) {
        setcookie('FIRST_NAME_error', '', time()-24*60*60);
        setcookie('FIRST_NAME_error', '', time()-24*60*60);
        $messages[]='<div class="error">Заполните имя пациента</div>';
    }
    if($errors['MIDDLE_NAME']) {
        setcookie('MIDDLE_NAME_error', '', time()-24*60*60);
        setcookie('MIDDLE_NAME_error', '', time()-24*60*60);
        $messages[]='<div class="error">Заполните отчество пациента</div>';
    }
    if($errors['BIRTHDATE']) {
        setcookie('BIRTHDATE_error', '', time()-24*60*60);
        setcookie('BIRTHDATE_error', '', time()-24*60*60);
        $messages[]='<div class="error">Заполните дату рождения</div>';
    }
    if($errors['ADDRESS']) {
        setcookie('ADDRESS_error', '', time()-24*60*60);
        setcookie('ADDRESS_error', '', time()-24*60*60);
        $messages[]='<div class="error">Заполните адрес пациента</div>';
    }

    $values = array();
    $values['LAST_NAME'] = empty($_COOKIE['LAST_NAME_value']) ? '' : $_COOKIE['LAST_NAME_value'];
    $values['FIRST_NAME'] = empty($_COOKIE['FIRST_NAME_value']) ? '' : $_COOKIE['FIRST_NAME_value'];
    $values['MIDDLE_NAME'] = empty($_COOKIE['MIDDLE_NAME_value']) ? '' : $_COOKIE['MIDDLE_NAME_value'];
    $values['BIRTHDATE'] = empty($_COOKIE['BIRTHDATE']) ? '' : $_COOKIE['BIRTHDATE_value'];
    $values['ADDRESS'] = empty($_COOKIE['ADDRESS_value']) ? '' : $_COOKIE['ADDRESS_value'];

    include('form_patients.php');
    exit();
}

elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

    $lastname=$firstname=$middlename=$birthdate=$address='';
    $lastname=$_POST['LAST_NAME'];
    $firstname=$_POST['FIRST_NAME'];
    $middlename=$_POST['MIDDLE_NAME'];
    $birthdate=$_POST['BIRTHDATE'];
    $address=$_POST['ADDRESS'];

    $errors = FALSE;
    //(1) LAST_NAME CHECK
    if (empty(trim($_POST['LAST_NAME'])) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['LAST_NAME'])) {
        $errors = TRUE;
        setcookie('LAST_NAME_error', '1', time() + 24 * 60 * 60);
    }
    else{
        setcookie('LAST_NAME_value', $_POST['LAST_NAME'], time() + 30 * 24 * 60 * 60);
    }
    //(2) FIRST_NAME CHECK
    if(empty($_POST['FIRST_NAME']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['FIRST_NAME'])){
        $errors = TRUE;
        setcookie('FIRST_NAME_error', '1', time() + 24 * 60 * 60);
    }
    else{
        setcookie('FIRST_NAME_value', $_POST['FIRST_NAME'], time() + 30 * 24 * 60 * 60);
    }
    //(3) MIDDLE_NAME CHECK
    if(empty($_POST['MIDDLE_NAME']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['MIDDLE_NAME'])){
        $errors = TRUE;
        setcookie('MIDDLE_NAME_error', '1', time() + 24 * 60 * 60);
    }
    else{
        setcookie('MIDDLE_NAME_value', $_POST['MIDDLE_NAME'], time() + 30 * 24 * 60 * 60);
    }
    //(4) BIRTHDATE CHECK
    $dateObject = DateTime::createFromFormat('Y-m-d', $_POST['BIRTHDATE']);
    if ($dateObject === false || $dateObject->format('Y-m-d') !== $_POST['BIRTHDATE']) {
        $errors = TRUE;
        setcookie('BIRTHDATE_error', '1', time() + 24 * 60 * 60);
    }
    else{
        setcookie('BIRTHDATE_value', $_POST['BIRTHDATE'], time() + 30 * 24 * 60 * 60);
    }
    //(5) ADDRESS CHECK
    if(empty($_POST['ADDRESS'])){
        $errors = TRUE;
        setcookie('ADDRESS_error', '1', time() + 24 * 60 * 60);
    }
    else{
        setcookie('ADDRESS_value', $_POST['ADDRESS'], time() + 30 * 24 * 60 * 60);
    }

    $mas=array();
    if ($errors) {
        print('Ошибка');
        header('Location: patients.php');
        exit();
    }
    else {
        setcookie('LAST_NAME_error','',100000);
        setcookie('FIRST_NAME_error','',100000);
        setcookie('MIDDLE_NAME_error','',100000);
        setcookie('BIRTHDATE_error','',100000);
        setcookie('ADDRESS_error','',100000);
    }

    include("../Secret.php");
    $servername='localhost';
    $username = username;
    $password = password;
    $dbname = username;

    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully ";
        $sql = "INSERT INTO PATIENTS (LAST_NAME, FIRST_NAME, MIDDLE_NAME, BIRTHDATE, ADDRESS ) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        $stmt->execute([$fio, $speciality, $cost, $percent]);
        echo "Пациент успешно добавлен.";
        $lastId = $conn->lastInsertId();
        echo "ID нового пациента: $lastId";
        
    }
    catch(PDOException $e) {
        $mas[]="Connection failed: " . $e->getMessage();
    }
    $conn = null;
    setcookie('SAVE', '1');
    
    header("Location: patients.php"); 
    exit;
}
?>