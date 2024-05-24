<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['DBERROR'])) {
        $messages[] = $_COOKIE['DBERROR'] . '<br><br>';
        setcookie('DBERROR', '', time() - 3600);
    }
    if (!empty($_COOKIE['logMASS'])) {
        $messages[] = $_COOKIE['logMASS'];
        setcookie('logMASS', '', time() - 3600);
    }
    if (!empty($_COOKIE['flag'])) {
        $messages[] = 'a? '.$_COOKIE['flag'];
        setcookie('flag', '', time() - 3600);
    }
    if (!empty($_COOKIE['kkk'])) {
        $messages[] = 'how? '.unserialize($_COOKIE['kkk']);
        setcookie('kkk', '', time() - 3600);
    }

    if (!empty($_COOKIE['save'])) {
        $messages[] = 'Спасибо, результаты сохранены.';
        if (!empty($_COOKIE['password'])) {
            $messages[] = sprintf('Вы можете <a href="login.php?log=%s&pas=%s"> войти </a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['password']),
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['password']));
        }
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('password', '', 100000);
    }


    $errors = array();
    $errors['FIO'] = !empty($_COOKIE['FIO_error']);
    $errors['PHONE'] = !empty($_COOKIE['PHONE_error']);
    $errors['EMAIL'] = !empty($_COOKIE['EMAIL_error']);
    $errors['BIRTHDATE'] = !empty($_COOKIE['BIRTHDATE_error']);
    $errors['GENDER'] = !empty($_COOKIE['GENDER_error']);
    $errors['Lang_Prog'] = !empty($_COOKIE['Lang_Prog_error']);
    $errors['BIOGRAFY'] = !empty($_COOKIE['BIOGRAFY_error']);
    $errors['CONTRACT'] = !empty($_COOKIE['CONTRACT_error']);

    if ($errors['FIO']) {
        setcookie('FIO_error', '', 100000);
        setcookie('FIO_value', '', 100000);
        $messages[] = '<div class="error">Заполните имя.</div>';
    }
    if ($errors['PHONE']) {
        setcookie('PHONE_error', '', 100000);
        setcookie('PHONE_value', '', 100000);
        $messages[] = '<div class="error">Заполните телефон.</div>';
    }
    if ($errors['EMAIL']) {
        setcookie('EMAIL_error', '', 100000);
        setcookie('EMAIL_value', '', 100000);
        $messages[] = '<div class="error">Заполните почту.</div>';
    }
    if ($errors['BIRTHDATE']) {
        setcookie('BIRTHDATE_error', '', 100000);
        setcookie('BIRTHDATE_value', '', 100000);
        $messages[] = '<div class="error">Заполните дату.</div>';
    }
    if ($errors['GENDER']) {
        setcookie('GENDER_error', '', 100000);
        setcookie('GENDER_value', '', 100000);
        $messages[] = '<div class="error">Заполните пол.</div>';
    }
    if ($errors['Lang_Prog']) {
        setcookie('Lang_Prog_error', '', 100000);
        setcookie('Lang_Prog_value', '', 100000);
        $messages[] = '<div class="error">выберите язык.</div>';
    }
    if ($errors['BIOGRAFY']) {
        setcookie('BIOGRAFY_error', '', 100000);
        setcookie('BIOGRAFY_value', '', 100000);
        $messages[] = '<div class="error">Заполните дополнительную информаци о себе.</div>';
    }
    if ($errors['CONTRACT']) {
        setcookie('CONTRACT_error', '', 100000);
        setcookie('CONTRACT_value', '', 100000);
        $messages[] = '<div class="error">Согласитель на обработку персональных данных.</div>';
    }


    $values = array();
    $values['FIO'] = empty($_COOKIE['FIO_value']) ? '' : $_COOKIE['FIO_value'];
    $values['PHONE'] = empty($_COOKIE['PHONE_value']) ? '' : $_COOKIE['PHONE_value'];
    $values['EMAIL'] = empty($_COOKIE['EMAIL_value']) ? '' : $_COOKIE['EMAIL_value'];
    $values['BIRTHDATE'] = empty($_COOKIE['BIRTHDATE_value']) ? '' : $_COOKIE['BIRTHDATE_value'];
    $values['GENDER'] = empty($_COOKIE['GENDER_value']) ? '' : $_COOKIE['GENDER_value'];
    $values['Lang_Prog'] = empty($_COOKIE['Lang_Prog_value']) ? array() : unserialize($_COOKIE['Lang_Prog_value']) ;
    $values['BIOGRAFY'] = empty($_COOKIE['BIOGRAFY_value']) ? '' : $_COOKIE['BIOGRAFY_value'];
    $values['CONTRACT'] = empty($_COOKIE['CONTRACT_value']) ? '' : $_COOKIE['CONTRACT_value'];


    $started_session = session_start();
    $messages[] = '1:'.!empty($_COOKIE[session_name()]) .'<br> :: '. $started_session .'<br> :: '. !empty($_SESSION['entered']).'<br> ';

    if (!empty($_COOKIE[session_name()]) &&
        $started_session && !empty($_SESSION['entered'])) {
        $messages[]='Вход с логином: '. $_SESSION['login'];
        // TODO: загрузить данные пользователя из БД
        // и заполнить переменную $values,
        // предварительно санитизовав.
        printf('Вход с логином %s, uID %d', $_SESSION['login'], $_SESSION['password']);
    }
    else{
        $messages[]='не выполнен вход';
    }

    $messages[] = '<a href ="login.php?enter=1"> Enter (выход) </a>';



    include('form.php');
    exit();
}
else {
//обработка POST запроса
    $FIO = $PHONE = $EMAIL = $BIRTHDATE = $GENDER = '';
    $langs = [];
    $FIO = $_POST['FIO'];
    $PHONE = $_POST['PHONE'];
    $EMAIL = $_POST['EMAIL'];
    $BIRTHDATE = $_POST['BIRTHDATE'];
    $GENDER = $_POST['GENDER'];
    $BIOGRAFY = $_POST['BIOGRAFY'];
    $langs = isset($_POST['Lang_Prog']) ? (array)$_POST['Lang_Prog'] : [];
    $langs_check = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskel', 'Clojure', 'Prolog', 'Scala'];


//валидация данных

    $errors = FALSE;

    if (empty($_POST['FIO']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['FIO'])) {
        $errors = TRUE;
        setcookie('FIO_error', '1', time() + 24 * 60 * 60);
        print (" mistake in фио ");
    }
    else setcookie('FIO_value', $_POST['FIO'], time() + 30 * 24 * 60 * 60);

    if (empty($_POST['PHONE']) || !preg_match('/^[0-9+]+$/', $_POST['PHONE'])) {
        $errors = TRUE;
        setcookie('PHONE_error', '1', time() + 24 * 60 * 60);
        print (" mistake in тел ");
    }
    else setcookie('PHONE_value', $_POST['PHONE'], time() + 30 * 24 * 60 * 60);

    if (empty($_POST['EMAIL']) || !filter_var($_POST['EMAIL'], FILTER_VALIDATE_EMAIL)) {
        $errors = TRUE;
        setcookie('EMAIL_error', '1', time() + 24 * 60 * 60);
        print (" mistake in мыло ");
    }
    else setcookie('EMAIL_value', $_POST['EMAIL'], time() + 30 * 24 * 60 * 60);


    $dateObject = DateTime::createFromFormat('Y-m-d', $_POST['BIRTHDATE']);
    if ($dateObject === false || $dateObject->format('Y-m-d') !== $_POST['BIRTHDATE']) {
        $errors = TRUE;
        setcookie('BIRTHDATE_error', '1', time() + 24 * 60 * 60);
        print (" mistake in дата ");
    }
    else setcookie('BIRTHDATE_value', $_POST['BIRTHDATE'], time() + 30 * 24 * 60 * 60);

    if ($_POST['GENDER'] != 'male' && $_POST['GENDER'] != 'female') {
        $errors = TRUE;
        setcookie('GENDER_error', '1', time() + 24 * 60 * 60);
        print (" mistake in гендр ");
    }
    else setcookie('GENDER_value', $_POST['GENDER'], time() + 30 * 24 * 60 * 60);

    /*
    if (!checkLangs($langs, $langs_check)) {
        $errors = TRUE;
        print (" mistake in check ");
    }*/

    if(!isset($_POST['CONTRACT'])){
        $errors = TRUE;
        setcookie('CONTRACT_error', '1', time() + 24 * 60 * 60);
        print (" mistake in галочка ");
    }
    else setcookie('CONTRACT_value', $_POST['CONTRACT'], time() + 30 * 24 * 60 * 60);

    if ($errors === TRUE) {
        echo 'mistake';
        exit();
    }
    else {
        setcookie('FIO_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('PHONE_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('EMAIL_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('BIRTHDATE_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('GENDER_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('CONTRACT_value', '', time() - 30 * 24 * 60 * 60);
    }

    function generateUsername() {
        return 'user_' . uniqID(); // генерация уникального ID для простоты
    }


    include('../Secret.php');
    $servername = "localhost";
    $username = username;
    $password = password;
    $dbname = username;


    if (!empty($_COOKIE[session_name()]) &&
        session_start() && !empty($_SESSION['login']) && false) {
        // TODO: перезаписать данные в БД новыми данными,
        // кроме логина и пароля.
    }
    else {
        $login = generateUsername();
        $password = rand();
        $hashed_password = md5($password);

        // Сохраняем в Cookies.
        setcookie('login', $login);
        setcookie('password', $password);

        $_SESSION['entered'] = false;


        try {
            $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully ";

            // Вставка данных в базу
            $sql = "INSERT INTO USERS (username, password) VALUES (:username, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['username' => $login, 'password' => $hashed_password]);



            $sql = "INSERT INTO REQUEST (login, FIO, PHONE, EMAIL, BIRTHDATE, GENDER, BIOGRAFY) VALUES ('$login','$FIO', '$PHONE', '$EMAIL', '$BIRTHDATE', '$GENDER', '$BIOGRAFY')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $lastID = $conn->lastInsertID();


            for ($i = 0; $i < count($langs); $i++) {
                $sql = "SELECT Lang_ID FROM Lang_Prog WHERE Lang_NAME = :langName";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':langName', $langs[$i]);
                $stmt->execute();
                $result = $stmt->fetch();
                $lang_ID = $result['Lang_ID'];
                $sql = "INSERT INTO ANSWER (ID, Lang_ID) VALUES ($lastID, $lang_ID)";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
            echo nl2br("\nNew record created successfully");
        } catch(PDOException $e) {
            setcookie('DBERROR', 'Error1 : ' . $e->getMessage());
        }
    }

    setcookie('save', '1');

    // Делаем перенаправление.
    header('Location: ./');

    $conn = null;
}
?>
