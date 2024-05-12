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

    $errors=array();
    $errors['FIO_DOCTOR']=!empty($_COOKIE['FIO_DOCTOR_error']);
    $errors['SPECIALITY_DOCTOR']=!empty($_COOKIE['SPECIALITY_DOCTOR_error']);
    $errors['COST_OF_ADMISSION']=!empty($_COOKIE['COST_OF_ADMISSION_error']);
    $errors['PERCENTAGE_OF_SALARY']=!empty($_COOKIE['PERCENTAGE_OF_SALARY_error']);
    

    if($errors['FIO_DOCTOR']) {
        setcookie('FIO_DOCTOR_error', '', time()-24*60*60);
        setcookie('FIO_DOCTOR_error', '', time()-24*60*60);
        $messages[]='<div class="error">Заполните ФИО врача</div>';
    }
    if($errors['SPECIALITY_DOCTOR']) {
        setcookie('SPECIALITY_DOCTOR_error', '', time()-24*60*60);
        setcookie('SPECIALITY_DOCTOR_error', '', time()-24*60*60);
        $messages[]='<div class="error">Выберите специальность врача</div>';
    }
    if($errors['COST_OF_ADMISSION']) {
        setcookie('COST_OF_ADMISSION_error', '', time()-24*60*60);
        setcookie('COST_OF_ADMISSION_error', '', time()-24*60*60);
        $messages[]='<div class="error">Введите стоимость приёма</div>';
    }
    if($errors['PERCENTAGE_OF_SALARY']) {
        setcookie('PERCENTAGE_OF_SALARY_error', '', time()-24*60*60);
        setcookie('PERCENTAGE_OF_SALARY_error', '', time()-24*60*60);
        $messages[]='<div class="error">Введите процент отчисления на зарплату</div>';
    }

    $values = array();
    $values['FIO_DOCTOR'] = empty($_COOKIE['FIO_DOCTOR_value']) ? '' : $_COOKIE['FIO_DOCTOR_value'];
    $values['SPECIALITY_DOCTOR'] = empty($_COOKIE['SPECIALITY_DOCTOR_value']) ? '' : $_COOKIE['SPECIALITY_DOCTOR_value'];
    $values['COST_OF_ADMISSION'] = empty($_COOKIE['COST_OF_ADMISSION_value']) ? '' : $_COOKIE['COST_OF_ADMISSION_value'];
    $values['PERCENTAGE_OF_SALARY'] = empty($_COOKIE['PERCENTAGE_OF_SALARY_value']) ? '' : $_COOKIE['PERCENTAGE_OF_SALARY_value'];
    include('form_doctors.php');
    exit();
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fio=$speciality=$cost=$percent='';
    $fio=$_POST['FIO_DOCTOR'];
    $speciality=$_POST['SPECIALITY_DOCTOR'];
    $cost=$_POST['COST_OF_ADMISSION'];
    $percent=$_POST['PERCENTAGE_OF_SALARY'];

    $errors = FALSE;
//проверяет, заполнено ли поле "..." в форме и содержит ли оно допустимые символы.
//(1) FIO check
if (empty($_POST['FIO_DOCTOR']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,50}$/u', $_POST['FIO_DOCTOR'])) {
    $errors = TRUE;
    setcookie('FIO_DOCTOR_error', '1', time() + 24 * 60 * 60);
}
else{
  setcookie('FIO_DOCTOR_value', $_POST['FIO_DOCTOR'], time() + 30 * 24 * 60 * 60);
}
//(2) SPECIALITY check
if (empty($_POST['SPECIALITY_DOCTOR']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,50}$/u', $_POST['SPECIALITY_DOCTOR'])) {
    $errors = TRUE;
    setcookie('SPECIALITY_DOCTOR_error', '1', time() + 24 * 60 * 60);
}
else{
  setcookie('SPECIALITY_DOCTOR_value', $_POST['SPECIALITY_DOCTOR'], time() + 30 * 24 * 60 * 60);
}
//(3) COST check
if (empty($_POST['COST_OF_ADMISSION']) || !preg_match('/^\d{1,6}(\.\d{1,2})?$/', $_POST['COST_OF_ADMISSION'])) {
    $errors = TRUE;
    setcookie('COST_OF_ADMISSION_error', '1', time() + 24 * 60 * 60);
} 
else {
    setcookie('COST_OF_ADMISSION_value', $_POST['COST_OF_ADMISSION'], time() + 30 * 24 * 60 * 60);
}
//(4) PERCENTAGE check
if (empty($_POST['PERCENTAGE_OF_SALARY']) || !preg_match('/^\d{1,3}$/', $_POST['PERCENTAGE_OF_SALARY'])) {
    $errors = TRUE;
    setcookie('PERCENTAGE_OF_SALARY_error', '1', time() + 24 * 60 * 60);
} 
else {
    setcookie('PERCENTAGE_OF_SALARY_value', $_POST['PERCENTAGE_OF_SALARY'], time() + 30 * 24 * 60 * 60);
}
$mas=array();
if ($errors) {
    print('Ошибка');
    header('Location: doctors.php');
    exit();
}
else {
    setcookie('FIO_error','',100000);
    setcookie('SPECIALITY_DOCTOR_error','',100000);
    setcookie('COST_OF_ADMISSION_error','',100000);
    setcookie('PERCENTAGE_OF_SALARY_error','',100000);
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
    $sql = "INSERT INTO DOCTORS (FIO_DOCTOR, SPECIALITY_DOCTOR, COST_OF_ADMISSION, PERCENTAGE_OF_SALARY) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->execute([$fio, $speciality, $cost, $percent]);
    echo "Врач успешно добавлен.";
    $lastId = $conn->lastInsertId();
    echo "ID нового врача: $lastId";
    
}
catch(PDOException $e) {
    $mas[]="Connection failed: " . $e->getMessage();
}
$conn = null;
setcookie('SAVE', '1');

header("Location: doctors.php"); 
exit;

}
?>