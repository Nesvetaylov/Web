<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
    if (!empty($_GET['save'])) {
      // Если есть параметр save, то выводим сообщение пользователю.
      print('Спасибо, результаты сохранены.');
    }
    // Включаем содержимое файлаindex.html
    include('form.php');
    // Завершаем работу скрипта.
    exit();
  }
  include("../Secret.php");
$username = username;
$password = password;
$dbname = username;


$fio = $phone = $email = $birthdate = $gender = '';
$fio = $_POST['FIO'];
$phone = $_POST['PHONE'];
$email = $_POST['EMAIL'];
$birthdate = $_POST['BIRTHDATE'];
$gender = $_POST['GENDER'];
$bio = $_POST['BIOGRAFY'];
$langs = isset($_POST['Lang_Prog']) ? (array)$_POST['Lang_Prog'] : [];
$langs_check = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskel', 'Clojure', 'Prolog', 'Scala'];
function checkLangs($langs, $langs_check) {
    for ($i = 0; $i < count($langs); $i++) {
        $isTrue = FALSE;
        for ($j = 0; $j < count($langs_check); $j++) {
            if ($langs[$i] === $langs_check[$j]) {
                $isTrue = TRUE;
                break;
            }
        }
        if ($isTrue === FALSE) return FALSE;
    }
    return TRUE;
}


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo 'This script only works with POST queries';
    exit();
}

$errors = FALSE;

if (empty($_POST['FIO']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['FIO'])) {
    $errors = TRUE;
    echo 'Ошибка ФИО';
}

if (empty($phone) || !preg_match('/^[0-9+]+$/', $phone)) {
    $errors = TRUE;
    echo 'Ошибка Телефона';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = TRUE;
    echo 'Ошибка ЕМАИЛ';
}


$dateObject = DateTime::createFromFormat('Y-m-d', $birthdate);
if ($dateObject === false || $dateObject->format('Y-m-d') !== $birthdate) {
    $errors = TRUE;
    echo 'Ошибка Дата рождение';
}

if ($gender != 'male' && $gender != 'female') {
    $errors = TRUE;
    echo 'Ошибка женщины или мужчины';
}

/*
if (!checkLangs($langs, $langs_check)) {
    $errors = TRUE;
    echo 'Ошибка Языка';
}
*/

if ($errors === TRUE) {
    echo 'mistake';
    exit();
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully ";
    $sql = "INSERT INTO REQUEST (FIO, PHONE, EMAIL, BIRTHDATE, GENDER, BIOGRAFY) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$fio, $phone, $email, $birthdate, $gender, $bio]);
    $lastId = $conn->lastInsertId();
    $Lang_selection = "SELECT Lang_ID FROM Lang_Prog WHERE Lang_NAME = ?";
    $Lang_prepare = $conn->prepare($Lang_selection);
    $Answer_insert = "INSERT INTO ANSWER (ID, Lang_ID) VALUES (?, ?)";
    $Answer_prepare = $conn->prepare($Answer_insert);
    foreach($_POST['Lang_Prog'] as $lang){
        $Lang_prepare->execute([$lang]);
        $lang_ID=$Lang_prepare->fetchColumn();
        $Answer_prepare->execute([$lastId,$lang_ID]);
    }
    echo nl2br("\nNew record created successfully");
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>