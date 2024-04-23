<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
    if (!empty($_GET['save'])) {
      // Если есть параметр save, то выводим сообщение пользователю.
      print('Спасибо, результаты сохранены.');
    }
    // Включаем содержимое файла form.php
    include('form4.php');
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

if ($errors === TRUE) {
    echo 'mistake';
    exit();
}
if ($_SERVER['REQUEST_METHOD']=='GET'){
    $messages=array();
    if (!empty($_COOKIE['SAVE'])) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('SAVE', '', 100000);
        // Если есть параметр save, то выводим сообщение пользователю.
        $messages[] = 'Спасибо, результаты сохранены.';
    }
    // Складываем признак ошибок в массив.
  $errors = array();
  $errors['FIO'] = !empty($_COOKIE['FIO_error']);
  $errors['PHONE'] = !empty($_COOKIE['PHONE_error']);
  $errors['EMAIL'] = !empty($_COOKIE['EMAIL_error']);
  $errors['BIRTHDATE'] = !empty($_COOKIE['BIRTHDATE_error']);
  $errors['GENDER'] = !empty($_COOKIE['GENDER_error']);
  $errors['BIOGRAFY'] = !empty($_COOKIE['BIOGRAFY_error']);
  // TODO: аналогично все поля.

  // Выдаем сообщения об ошибках.
  if ($errors['FIO']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('FIO_error', '', 100000);
    setcookie('FIO_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
    setcookie('PHONE_error', '', 100000);
    setcookie('PHONE_value', '', 100000);
    $messages[] = '<div class="error">Заполните номер телефона.</div>';
    setcookie('EMAIL_error', '', 100000);
    setcookie('EMAIL_value', '', 100000);
    $messages[] = '<div class="error">Заполните почту.</div>';
    setcookie('BIRTHDATE_error', '', 100000);
    setcookie('BIRTHDATE_value', '', 100000);
    $messages[] = '<div class="error">Заполните дату рождения.</div>';
    setcookie('GENDER_error', '', 100000);
    setcookie('GENDER_value', '', 100000);
    $messages[] = '<div class="error">Заполните информацию о поле.</div>';
    setcookie('BIOGRAFY_error', '', 100000);
    setcookie('BIOGRAFY_value', '', 100000);
    $messages[] = '<div class="error">Заполните информацию о биографии.</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['FIO'] = empty($_COOKIE['FIO_value']) ? '' : $_COOKIE['FIO_value'];
  $values = array();
  $values['PHONE'] = empty($_COOKIE['PHONE_value']) ? '' : $_COOKIE['PHONE_value'];
  $values = array();
  $values['EMAIL'] = empty($_COOKIE['EMAIL_value']) ? '' : $_COOKIE['EMAIL_value'];
  $values = array();
  $values['BIRTHDATE'] = empty($_COOKIE['BIRTHDATE_value']) ? '' : $_COOKIE['BIRTHDATE_value'];
  $values = array();
  $values['GENDER'] = empty($_COOKIE['GENDER_value']) ? '' : $_COOKIE['GENDER_value'];
  $values = array();
  $values['BIOGRAFY'] = empty($_COOKIE['BIOGRAFY_value']) ? '' : $_COOKIE['BIOGRAFY_value'];
  // TODO: аналогично все поля.

   // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form4.php');

}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
    // Проверяем ошибки.
  $errors = FALSE;
  //1
  if (empty($_POST['FIO'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('FIO_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  // Сохраняем ранее введенное в форму значение на месяц.
  setcookie('FIO_value', $_POST['FIO'], time() + 30 * 24 * 60 * 60);
  //2
  if (empty($_POST['PHONE'])) {
    setcookie('PHONE_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('PHONE_value', $_POST['PHONE'], time() + 30 * 24 * 60 * 60);
  //3
  if (empty($_POST['EMAIL'])) {
    setcookie('EMAIL_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('EMAIL_value', $_POST['EMAIL'], time() + 30 * 24 * 60 * 60);
  //4
  if (empty($_POST['BIRTHDATE'])) {
    setcookie('BIRTHDATE_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('BIRTHDATE_value', $_POST['BIRTHDATE'], time() + 30 * 24 * 60 * 60);
  //5
  if (empty($_POST['GENDER'])) {
    setcookie('GENDER_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('GENDER_value', $_POST['GENDER'], time() + 30 * 24 * 60 * 60);
  //6
  if (empty($_POST['BIOGRAFY'])) {
    setcookie('BIOGRAFY_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('BIOGRAFY_value', $_POST['BIOGRAFY'], time() + 30 * 24 * 60 * 60);

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('FIO_error', '', 100000);
    setcookie('PHONE_error', '', 100000);
    setcookie('EMAIL_error', '', 100000);
    setcookie('BIRTHDATE_error', '', 100000);
    setcookie('GENDER_error', '', 100000);
    setcookie('BIOGRAFY_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Сохраняем куки с признаком успешного сохранения.
  setcookie('SAVE', '1');
  // Делаем перенаправление.
  header('Location: index.php');
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
