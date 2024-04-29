<?php
header('Content-Type: text/html; charset=UTF-8');
//Этот блок кода позволяет сохранять результаты предыдущего запроса,
//используя cookies, и отображать их в текущем запросе. 
if ($_SERVER['REQUEST_METHOD']=='GET') {
    $messages=array();
    if (!empty($_COOKIE['SAVE'])) {
        setcookie('SAVE', '', 100000);
        $messages[]='Спасибо, результаты сохранены.';
    }
    //проверяет существование определенных cookies,
    //которые указывают на ошибки ввода, и устанавливает переменные
    //в массиве $errors в true, если эти cookies найдены.
    $errors=array();
    $errors['FIO_DOCTOR']=!empty($_COOKIE['FIO_DOCTOR_error']);
    $errors['SPECIALITY_DOCTOR']=!empty($_COOKIE['SPECIALITY_DOCTOR_error']);
    $errors['COST_OF_ADMISSION']=!empty($_COOKIE['COST_OF_ADMISSION_error']);
    $errors['PERCENTAGE_OF_SALARY']=!empty($_COOKIE['PERCENTAGE_OF_SALARY_error']);
    //проверяет, есть ли ошибка с вводом FIO_DOCTOR,
    //и, если это так, отображает сообщение об ошибке и удаляет соответствующую cookie. 
    if($errors['FIO_DOCTOR']) {
        setcookie('FIO_DOCTOR_error', '', time()-24*60*60);
        setcookie('FIO_DOCTOR_error', '', time()-24*60*60);
        $messages[]='<div class="error">Заполните ФИО доктора</div>';
    }
    if($errors['SPECIALITY_DOCTOR']) {
        setcookie('SPECIALITY_DOCTOR_error', '', time()-24*60*60);
        setcookie('SPECIALITY_DOCTOR_error', '', time()-24*60*60);
        $messages[]='<div class="error">Выберите специальность доктора</div>';
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
    include('form.php');
    exit();
}
include("../Secret.php");
$username = username;
$password = password;
$dbname = username;

//Эта строка кода объявляет несколько переменных
//и присваивает им пустые строки в качестве значений по умолчанию.
$fio=$speciality=$cost=$percent='';
$fio=$_POST['FIO_DOCTOR'];
$speciality=$_POST['SPECIALITY_DOCTOR'];
$cost=$_POST['COST_OF_ADMISSION'];
$percent=$_POST['PERCENTAGE_OF_SALARY'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo 'This script only works with POST queries';
    exit();
}
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
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
}
// Удаляем Cookies с признаками ошибок.
else {
    setcookie('FIO_error','',100000);
    setcookie('SPECIALITY_DOCTOR_error','',100000);
    setcookie('COST_OF_ADMISSION_error','',100000);
    setcookie('PERCENTAGE_OF_SALARY_error','',100000);
}
try {
    $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully ";
    $sql = "INSERT INTO DOCTORS (FIO_DOCTOR, SPECIALITY_DOCTOR, COST_OF_ADMISSION, PERCENTAGE_OF_SALARY) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$fio, $speciality, $cost, $percent]);
    $lastId = $conn->lastInsertId();

}
catch(PDOException $e) {
  $mas[]="Connection failed: " . $e->getMessage();
}
$conn = null;
// Сохраняем куки с признаком успешного сохранения.
setcookie('SAVE', '1');
setcookie('MAS', serialize($mas));
// Делаем перенаправление.
header('Location: index.php');














?>