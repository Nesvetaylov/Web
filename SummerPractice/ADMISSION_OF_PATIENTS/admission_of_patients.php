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

    // Database connection function
function connectToDatabase($servername, $dbname, $username, $password) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        throw new Exception("Database connection error: " . $e->getMessage());
    }
}

// Form processing function
function processForm($conn) {
    // Validate user input
    $lastname = filter_input(INPUT_POST, 'LAST_NAME', FILTER_SANITIZE_STRING);
    $firstname = filter_input(INPUT_POST, 'FIRST_NAME', FILTER_SANITIZE_STRING);
    $middlename = filter_input(INPUT_POST, 'MIDDLE_NAME', FILTER_SANITIZE_STRING);
    $birthdate = filter_input(INPUT_POST, 'BIRTHDATE', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'ADDRESS', FILTER_SANITIZE_STRING);
    $patient_id = filter_input(INPUT_POST, 'PATIENT_ID', FILTER_VALIDATE_INT);
    $doctor_id = filter_input(INPUT_POST, 'DOCTOR_ID', FILTER_VALIDATE_INT);
    $date = filter_input(INPUT_POST, 'DATE', FILTER_SANITIZE_STRING);

    if (!$lastname || !$firstname || !$middlename || !$birthdate || !$address || !$patient_id || !$doctor_id || !$date) {
        throw new Exception("Invalid input data");
    }

    // Insert patient data
    $sql = "INSERT INTO PATIENTS (LAST_NAME, FIRST_NAME, MIDDLE_NAME, BIRTHDATE, ADDRESS) VALUES (:lastname, :firstname, :middlename, :birthdate, :address)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':lastname' => $lastname,
        ':firstname' => $firstname,
        ':middlename' => $middlename,
        ':birthdate' => $birthdate,
        ':address' => $address,
    ]);

    $lastId = $conn->lastInsertId();
    echo "Пациент успешно добавлен. ID нового пациента: $lastId";

    // Find patient ID
    $sql = "SELECT PATIENT_ID FROM PATIENTS WHERE LAST_NAME = :lastname AND FIRST_NAME = :firstname AND MIDDLE_NAME = :middlename";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':lastname' => $lastname,
        ':firstname' => $firstname,
        ':middlename' => $middlename,
    ]);
    $patient_id = $stmt->fetch()['PATIENT_ID'];

    // Insert admission data
    $sql = "INSERT INTO ADMISSION_OF_PATIENTS (PATIENT_ID, DOCTOR_ID, DATE) VALUES (:patient_id, :doctor_id, :date)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':patient_id' => $patient_id,
        ':doctor_id' => $doctor_id,
        ':date' => $date,
    ]);

    echo "Запись на прием успешно добавлена.";
}

// Main script
$conn = connectToDatabase($servername, $dbname, $username, $password);
try {
    processForm($conn);
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
}
exit;
?>



