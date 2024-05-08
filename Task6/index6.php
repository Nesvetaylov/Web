<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $isStarted = session_start();
  $messages=array();
  // Выдаем сообщения о различных ошибках
  if (!empty($_COOKIE['DBERROR'])) {
    $messages[] = $_COOKIE['DBERROR'] . '<br><br>';
    setcookie('DBERROR', '', time() - 3600);
  }
  if (!empty($_COOKIE['AUTHERROR'])) {
    $messages[] = $_COOKIE['AUTHERROR'] . '<br><br>';
    setcookie('AUTHERROR', '', time() - 3600);
  }
    // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
    if (!empty($_COOKIE['SAVE'])) {
      // Если есть параметр save, то выводим сообщение пользователю.
      $mase=unserialize($_COOKIE['MAS']);
      foreach($mase as $m){$messages[]=$m;}
      setcookie('SAVE', '', 100000);
      setcookie('MAS', '', 100000);
    $messages[]='Спасибо, результаты сохранены.';
     // Если в куках есть пароль, то выводим сообщение.
     if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете войти с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.<br>',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password']));
    }
    setcookie('save', '', time() - 3600);
    setcookie('login', '', time() - 3600);
    setcookie('password', '', time() - 3600);
    }
    // Включаем содержимое файла form.php
    $errors=array();
    $errors['FIO']=!empty($_COOKIE['FIO_error']);
    $errors['PHONE']=!empty($_COOKIE['PHONE_error']);
    $errors['EMAIL']=!empty($_COOKIE['EMAIL_error']);
    $errors['BIRTHDATE']=!empty($_COOKIE['BIRTHDATE_error']);
    $errors['GENDER']=!empty($_COOKIE['GENDER_error']);
    $errors['BIOGRAFY']=!empty($_COOKIE['BIOGRAFY_error']);
    $errors['Lang_Prog']=!empty($_COOKIE['Lang_Prog_error']);
    if($errors['FIO']){
      setcookie('FIO_error', '', time() - 24 * 60 * 60);
      setcookie('FIO_error', '', time() - 24 * 60 * 60);
      $messages[] = '<div class="error">Заполните имя.</div>';
    }
    if($errors['PHONE']){
      setcookie('PHONE_error', '', time() - 24 * 60 * 60);
      setcookie('PHONE_error', '', time() - 24 * 60 * 60);
      $messages[] = '<div class="error">Заполните номер телефона.</div>';
    }
    if($errors['EMAIL']){
      setcookie('EMAIL_error', '', time() - 24 * 60 * 60);
      setcookie('EMAIL_error', '', time() - 24 * 60 * 60);
      $messages[] = '<div class="error">Заполните почту.</div>';
    }
    if($errors['BIRTHDATE']){
      setcookie('BIRTHDATE_error', '', time() - 24 * 60 * 60);
      setcookie('BIRTHDATE_error', '', time() - 24 * 60 * 60);
      $messages[] = '<div class="error">Заполните дату рождения.</div>';
    }
    if($errors['GENDER']){
      setcookie('GENDER_error', '', time() - 24 * 60 * 60);
      setcookie('GENDER_error', '', time() - 24 * 60 * 60);
      $messages[] = '<div class="error">Заполните гендер).</div>';
    }
    if($errors['BIOGRAFY']){
      setcookie('BIOGRAFY_error', '', time() - 24 * 60 * 60);
      setcookie('BIOGRAFY_error', '', time() - 24 * 60 * 60);
      $messages[] = '<div class="error">Заполните биографию.</div>';
    }
    if($errors['Lang_Prog']){
      setcookie('Lang_Prog_error', '', time() - 24 * 60 * 60);
      setcookie('Lang_Prog_error', '', time() - 24 * 60 * 60);
      $messages[] = '<div class="error">Выберите язык.</div>';
    }

    //дополнительный enter))
    $values = array();
    $values['FIO'] = empty($_COOKIE['FIO_value']) ? '' : $_COOKIE['FIO_value'];
    $values['PHONE'] = empty($_COOKIE['PHONE_value']) ? '' : $_COOKIE['PHONE_value'];
    $values['EMAIL'] = empty($_COOKIE['EMAIL_value']) ? '' : $_COOKIE['EMAIL_value'];
    $values['BIRTHDATE'] = empty($_COOKIE['BIRTHDATE_value']) ? '' : $_COOKIE['BIRTHDATE_value'];
    $values['GENDER'] = empty($_COOKIE['GENDER_value']) ? '' : $_COOKIE['GENDER_value'];
    $values['BIOGRAFY'] = empty($_COOKIE['BIOGRAFY_value']) ? '' : $_COOKIE['BIOGRAFY_value'];
    $values['Lang_Prog'] = empty($_COOKIE['Lang_Prog_value']) ? array() : unserialize($_COOKIE['Lang_Prog_value']);
    include('form.php');
    // Завершаем работу скрипта.
    exit();
  }
  //include("../Secret.php");


 // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
// ранее в сессию записан факт успешного логина.
if($isStarted && !empty($COOKIE[session_name()]) && !empty($_SESSION['Logged']) && $_SESSION['Logged']) {
  include("../Secret.php");
  $servername='localhost';
  $username = username;
  $password = password;
  $dbname = username;
  $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  try {
    $select="SELECT * FROM USERS WHERE login = ?";
    $result = $db->prepare($select);
    $result->execute([$_SESSION['login']]);
    $row = $result->fetch();
    // получаю значения формы из таблицы бд
    $formID = $row['ID'];
    $values['FIO'] = $row['FIO'];
    $values['PHONE'] = $row['PHONE'];
    $values['EMAIL'] = $row['EMAIL'];
    $values['BIRTHDATE'] = $row['BIRTHDATE'];
    $values['GENDER'] = $row['GENDER'];
    $values['BIOGRAFY'] = $row['BIOGRAFY'];
    // достаю выбранные языки программирования
    $select = "SELECT Lang_ID FROM Lang_Prog WHERE ID = ?";
    $result = $db->prepare($select);
    $result->execute([$formID]);
    $list = array();
    while($row = $result->fetch()){
      $list[] = $row['Lang_ID'];
    }
    $values['selections'] = $list;
  }
  catch(PDOException $e){
    $messages[] = 'Ошибка при загрузке формы из базы данных:<br>' . $e->getMessage();
  }
  $messages[] = "Выполнен вход с логином: <strong>" . $_SESSION['login'] . '</strong><br>';
  $messages[] = '<a href="login.php?exit=1">Выход</a>'; // вывод ссылки для выхода
}
// если не вошел, то вывести ссылку для входа
elseif($isStarted && !empty($_COOKIE[session_name()])) {
  $messages[] = '<a href="login.php">Войти</a> для изменения данных ранее отправленных форм<br>.';
}
include('form.php');





//$username = username;
//$password = password;
//$dbname = username;

$fio = $phone = $email = $birthdate = $gender = '';
$fio = $_POST['FIO'];
$phone = $_POST['PHONE'];
$email = $_POST['EMAIL'];
$birthdate = $_POST['BIRTHDATE'];
$gender = $_POST['GENDER'];
$biografy = $_POST['BIOGRAFY'];
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
    setcookie('FIO_error', '1', time() + 24 * 60 * 60);
}
else{
  setcookie('FIO_value', $_POST['FIO'], time() + 30 * 24 * 60 * 60);
}

if (empty($phone) || !preg_match('/^[0-9+]+$/', $phone)) {
    $errors = TRUE;
    setcookie('PHONE_error', '1', time() + 24 * 60 * 60);
}
else{
  setcookie('PHONE_value', $_POST['PHONE'], time() + 30 * 24 * 60 * 60);
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = TRUE;
    setcookie('EMAIL_error', '1', time() + 24 * 60 * 60);
}
else{
  setcookie('EMAIL_value', $_POST['EMAIL'], time() + 30 * 24 * 60 * 60);
}


$dateObject = DateTime::createFromFormat('Y-m-d', $birthdate);
if ($dateObject === false || $dateObject->format('Y-m-d') !== $birthdate) {
    $errors = TRUE;
    setcookie('BIRTHDATE_error', '1', time() + 24 * 60 * 60);
}
else{
  setcookie('BIRTHDATE_value', $_POST['BIRTHDATE'], time() + 30 * 24 * 60 * 60);
}

if ($gender != 'male' && $gender != 'female') {
    $errors = TRUE;
    setcookie('GENDER_error', '1', time() + 24 * 60 * 60);
}
else{
  setcookie('GENDER_value', $_POST['GENDER'], time() + 30 * 24 * 60 * 60);
}

if (empty($biografy)) {
  $errors = TRUE;
  setcookie('BIOGRAFY_error', '1', time() + 24 * 60 * 60);
}
else{
setcookie('BIOGRAFY_value', $_POST['BIOGRAFY'], time() + 30 * 24 * 60 * 60);
}
if (empty($langs)) {
  $errors = TRUE;
  setcookie('Lang_Prog_error', '1', time() + 24 * 60 * 60);
}
else{
setcookie('Lang_Prog_value', serialize($_POST['Lang_Prog']), time() + 30 * 24 * 60 * 60);
}
$mas=array();
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
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
  setcookie('Lang_Prog_error', '', 100000);
  // TODO: тут необходимо удалить остальные Cookies.
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully ";
    $sql = "INSERT INTO REQUEST (FIO, PHONE, EMAIL, BIRTHDATE, GENDER, BIOGRAFY) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$fio, $phone, $email, $birthdate, $gender, $biografy]);
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

} catch(PDOException $e) {
  $mas[]="Connection failed: " . $e->getMessage();
}
$conn = null;
// Сохраняем куки с признаком успешного сохранения.
setcookie('SAVE', '1');
setcookie('MAS', serialize($mas));
// Делаем перенаправление.
header('Location: index.php');
?>


<!-- ГЕНЕРАЦИЯ РАНДОМНОГО ЛОГИНА И ПАРОЛЯ И ПОДКЛЮЧЕНИЕ К БД -->
<?php
//Подключение к БД

?>