<?php
header('Content-Type: text/html; charset=UTF-8');

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

    include('form_admission_of_patients.php');
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

    $lastname = $_POST['LAST_NAME'];
    $firstname = $_POST['FIRST_NAME'];
    $middlename = $_POST['MIDDLE_NAME'];
    $birthdate = $_POST['BIRTHDATE'];
    $address = $_POST['ADDRESS'];

    $errors = FALSE;
    //(1) LAST_NAME CHECK
    if (empty(trim($lastname)) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $lastname)) {
        $errors = TRUE;
        setcookie('LAST_NAME_error', '1', time() + 24 * 60 * 60);
        print('Необходимо заполнить фамилию'."\n");
    }
    else{
        setcookie('LAST_NAME_value', $lastname, time() + 30 * 24 * 60 * 60);
    }
    //(2) FIRST_NAME CHECK
    if(empty($firstname) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $firstname)){
        $errors = TRUE;
        setcookie('FIRST_NAME_error', '1', time() + 24 * 60 * 60);
        print('Необходимо заполнить имя'."\n");
    }
    else{
        setcookie('FIRST_NAME_value', $firstname, time() +30 * 24 * 60 * 60);
    }
    //(3) MIDDLE_NAME CHECK
    if(empty($middlename) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $middlename)){
        $errors = TRUE;
        setcookie('MIDDLE_NAME_error', '1', time() + 24 * 60 * 60);
        print('Необходимо заполнить отчество'."\n");
    }
    else{
        setcookie('MIDDLE_NAME_value', $middlename, time() + 30 * 24 * 60 * 60);
    }
    //(4) BIRTHDATE CHECK
    $dateObject = DateTime::createFromFormat('Y-m-d', $birthdate);
    if ($dateObject === false || $dateObject->format('Y-m-d') !== $birthdate) {
        $errors = TRUE;
        setcookie('BIRTHDATE_error', '1', time() + 24 * 60 * 60);
        print ('Укажите дату рождения');
    }
    else{
        setcookie('BIRTHDATE_value', $birthdate, time() + 30 * 24 * 60 * 60);
    }
    //(5) ADDRESS CHECK
    if(empty($address)){
        $errors = TRUE;
        setcookie('ADDRESS_error', '1', time() + 24 * 60 * 60);
        print('Укажите корректный адрес'."\n");
    }
    else{
        setcookie('ADDRESS_value', $address, time() + 30 * 24 * 60 * 60);
    }

    $mas=array();
    if ($errors) {
        print('Ошибка');
        header('Location: admission_of_patients.php');
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
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "selecting:,";
        $sql = "INSERT INTO PATIENTS (LAST_NAME,FIRST_NAME, MIDDLE_NAME, BIRTHDATE, ADDRESS) VALUES (?, ?, ?, ?, ?,)";
        $stmt = $pdo->prepare($sql);;
        $stmt->execute([$lastname, $firstname, $middlename, $birthdate, $address]);
        echo "Пациент успешно добавлен.";
        $lastId = $pdo->lastInsertId();
        echo "ID нового пациента: $lastId <br>";
    } catch (PDOException $e) {
        $errors['database'] = "Ошибка при добавлении врача: " . $e->getMessage();
        echo "Ошибка при добавлении врача: " . $e->getMessage();
    }
    setcookie('save', '1');

}
exit;
?>
