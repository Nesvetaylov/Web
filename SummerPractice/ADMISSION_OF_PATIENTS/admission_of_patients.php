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
    $values['DATE'] = empty($_COOKIE['BIRTHDATE']) ? '' : $_COOKIE['BIRTHDATE_value'];
    $values['ADDRESS'] = empty($_COOKIE['ADDRESS_value']) ? '' : $_COOKIE['ADDRESS_value'];

    include('form_admission_of_patients.php');
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

    $lastname=$firstname=$middlename=$birthdate=$address='';
    $lastname=$_POST['LAST_NAME'];
    $firstname=$_POST['FIRST_NAME'];
    $middlename=$_POST['MIDDLE_NAME'];
    $birthdate=$_POST['DATE'];
    $address=$_POST['ADDRESS'];

    $errors = FALSE;
    //(1) LAST_NAME CHECK
    if (empty(trim($_POST['LAST_NAME'])) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['LAST_NAME'])) {
        $errors = TRUE;
        setcookie('LAST_NAME_error', '1', time() + 24 * 60 * 60);
        print('Необходимо заполнить фамилию'."\n");
    }
    else{
        setcookie('LAST_NAME_value', $_POST['LAST_NAME'], time() + 30 * 24 * 60 * 60);
    }
    //(2) FIRST_NAME CHECK
    if(empty($_POST['FIRST_NAME']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['FIRST_NAME'])){
        $errors = TRUE;
        setcookie('FIRST_NAME_error', '1', time() + 24 * 60 * 60);
        print('Необходимо заполнить имя'."\n");
    }
    else{
        setcookie('FIRST_NAME_value', $_POST['FIRST_NAME'], time() + 30 * 24 * 60 * 60);
    }
    //(3) MIDDLE_NAME CHECK
    if(empty($_POST['MIDDLE_NAME']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['MIDDLE_NAME'])){
        $errors = TRUE;
        setcookie('MIDDLE_NAME_error', '1', time() + 24 * 60 * 60);
        print('Необходимо заполнить отчество'."\n");
    }
    else{
        setcookie('MIDDLE_NAME_value', $_POST['MIDDLE_NAME'], time() + 30 * 24 * 60 * 60);
    }
    //(4) BIRTHDATE CHECK
    $dateObject = DateTime::createFromFormat('Y-m-d', $_POST['BIRTHDATE']);
    if ($dateObject === false || $dateObject->format('Y-m-d') !== $_POST['BIRTHDATE']) {
        $errors = TRUE;
        setcookie('BIRTHDATE_error', '1', time() + 24 * 60 * 60);
        print ('Укажите дату рождения');
    }
    else{
        setcookie('BIRTHDATE_value', $_POST['BIRTHDATE'], time() + 30 * 24 * 60 * 60);
    }
    //(5) ADDRESS CHECK
    if(empty($_POST['ADDRESS'])){
        $errors = TRUE;
        setcookie('ADDRESS_error', '1', time() + 24 * 60 * 60);
        print('Укажите корректный адрес'."\n");
    }
    else{
        setcookie('ADDRESS_value', $_POST['ADDRESS'], time() + 30 * 24 * 60 * 60);
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
        $sql = "INSERT INTO PATIENTS (LAST_NAME,FIRST_NAME, MIDDLE_NAME, BIRTHDATE, ADDRESS) VALUES (:lastname, :firstname, :middlename, :birthdate, :address)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':lastname', $_POST['lastname']);
        $stmt->bindParam(':firstname', $_POST['firstname']);
        $stmt->bindParam(':middlename', $_POST['middlename']);
        $stmt->bindParam(':birthdate', $_POST['birthdate']);
        $stmt->bindParam(':address', $_POST['address']);

        $stmt->execute();
        echo "Пациент успешно добавлен.";
        $lastId = $pdo->lastInsertId();
        echo "ID нового пациента: $lastId <br>";


    } catch (PDOException $e) {
        $errors['database'] = "Ошибка при добавлении врача: " . $e->getMessage();
        echo "Ошибка при добавлении врача: " . $e->getMessage();
    }
    setcookie('save', '1');




 // запись на прием только что добавленного пациента



// Проверка на отправку формы
try {
    // Получение данных из формы
    $lastname=$_POST['LAST_NAME'];
    $firstname=$_POST['FIRST_NAME'];
    $middlename=$_POST['MIDDLE_NAME'];
    $birthdate=$_POST['BIRTHDATE'];
    $address=$_POST['ADDRESS'];
    $doctor_id = $_POST["DOCTOR_ID"];
    $date = $_POST["DATE"];


    // Поиск ID пациента
    $sql = "SELECT PATIENT_ID FROM PATIENTS WHERE LAST_NAME = ? AND FIRST_NAME = ? AND MIDDLE_NAME = ?";
    $stmt = $pdo->prepare($sql);
    //$stmt->bind_param("sss", $lastName, $firstName, $middleName);

    $stmt->execute([$lastname, $firstname, $middlename]);
   // $result = $stmt->get_result();
    $patient_id = $stmt->fetch()["PATIENT_ID"];

    // Добавление записи в таблицу Appointments
    $sql = "INSERT INTO ADMISSION_OF_PATIENTS (PATIENT_ID, DOCTOR_ID, DATE) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    //$stmt->bind_param("iis", $patient_id, $doctor_id, $date);

    $stmt->execute([$patient_id, $doctor_id, $date]);
        echo "Запись на прием успешно добавлена.";
    }
    catch (PDOException $e) {
        $errors['database'] = "Ошибка при добавлении: " . $e->getMessage();
        echo "Ошибка при добавлении: " . $e->getMessage();
    }
  //setcookie('save', '1');
}
exit;
?>

