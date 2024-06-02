<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $isStarted = session_start(); //начало сессии
    $messages = array(); //массив сообщений для пользователя
  
    //вывод ошибок из куков
    if (!empty($_COOKIE['DBERROR'])) {
      $messages[] = $_COOKIE['DBERROR'] . '<br><br>';
      setcookie('DBERROR', '', time() - 3600);
    }
    if (!empty($_COOKIE['AUTHERROR'])) {
      $messages[] = $_COOKIE['AUTHERROR'] . '<br><br>';
      setcookie('AUTHERROR', '', time() - 3600);
    }
    if (!empty($_COOKIE['save'])) {
      setcookie('save', '', 100000);
      $messages[] = 'Спасибо, результаты сохранены.';
      // Если в куках есть пароль, то выводим сообщение.
      if (!empty($_COOKIE['pass'])) {
        $messages[] = sprintf(
          'Вы можете войти с логином <strong>%s</strong> паролем <strong>%s</strong> для повторного входа.<br>',
          strip_tags($_COOKIE['login']),
          strip_tags($_COOKIE['pass'])
        );
      }
      setcookie('save', '', time() - 3600);
      setcookie('login', '', time() - 3600);
      setcookie('pass', '', time() - 3600);
    }

    //если куки пустые
    $hasErrors = false;
    $errors = array();
    $errors['FIO'] = !empty($_COOKIE['FIO_error']);
    $errors['PHONE'] = !empty($_COOKIE['PHONE_error']);
    $errors['EMAIL'] = !empty($_COOKIE['EMAIL_error']);
    $errors['BIRTHDATE'] = !empty($_COOKIE['BIRTHDATE_error']);
    $errors['GENDER'] = !empty($_COOKIE['GENDER_error']);
    $errors['LANG'] = !empty($_COOKIE['LANG_error']);
    $errors['BIOGRAFY'] = !empty($_COOKIE['BIOGRAFY_error']);
    $errors['CH'] = !empty($_COOKIE['CH_error']);

    if ($errors['FIO']) {
        setcookie('FIO_error', '', 100000);
        setcookie('FIO_value', '', 100000);
        $messages[] = '<div class="error">Заполните имя.</div>';
        $hasErrors = true;
    }
    if ($errors['PHONE']) {
        setcookie('PHONE_error', '', 100000);
        setcookie('PHONE_value', '', 100000);
        $messages[] = '<div class="error">Заполните телефон.</div>';
        $hasErrors = true;
    }
    if ($errors['EMAIL']) {
        setcookie('EMAIL_error', '', 100000);
        setcookie('EMAIL_value', '', 100000);
        $messages[] = '<div class="error">Заполните почту.</div>';
        $hasErrors = true;
    }
    if ($errors['BIRTHDATE']) {
        setcookie('BIRTHDATE_error', '', 100000);
        setcookie('BIRTHDATE_value', '', 100000);
        $messages[] = '<div class="error">Заполните дату рождения.</div>';
        $hasErrors = true;
    }
    if ($errors['GENDER']) {
        setcookie('GENDER_error', '', 100000);
        setcookie('GENDER_value', '', 100000);
        $messages[] = '<div class="error">Выберите пол.</div>';
        $hasErrors = true;
    }
    if ($errors['LANG']) {
        setcookie('LANG_error', '', 100000);
        setcookie('LANG_value', '', 100000);
        $messages[] = '<div class="error">Что-то не так с языком программирования!.</div>';
        $hasErrors = true;
    }
    if ($errors['BIOGRAFY']) {
        setcookie('BIOGRAFY_error', '', 100000);
        setcookie('BIOGRAFY_value', '', 100000);
        $messages[] = '<div class="error">Заполните биографию.</div>';
        $hasErrors = true;
    }
    if ($errors['CH']) {
        setcookie('CH_error', '', 100000);
        setcookie('CH_value', '', 100000);
        $messages[] = '<div class="error">Подтвердите согласие.</div>';
        $hasErrors = true;
    }

    // если куки не пустые то массив заполняется данными из куки, иначе ''
    $values = array(); 
    $values['FIO'] = empty($_COOKIE['FIO_value']) ? '' : $_COOKIE['FIO_value'];
    $values['PHONE'] = empty($_COOKIE['PHONE_value']) ? '' : $_COOKIE['PHONE_value'];
    $values['EMAIL'] = empty($_COOKIE['EMAIL_value']) ? '' : $_COOKIE['EMAIL_value'];
    $values['BIRTHDATE'] = empty($_COOKIE['BIRTHDATE_value']) ? '' : $_COOKIE['BIRTHDATE_value'];
    $values['GENDER'] = empty($_COOKIE['GENDER_value']) ? '' : $_COOKIE['GENDER_value'];
    $values['LANG'] = empty($_COOKIE['LANG_value']) ? array() : unserialize($_COOKIE['LANG_value']);
    $values['BIOGRAFY'] = empty($_COOKIE['BIOGRAFY_value']) ? '' : $_COOKIE['BIOGRAFY_value'];
    $values['CH'] = empty($_COOKIE['CH_value']) ? '' : $_COOKIE['CH_value'];


    if ($isStarted && !empty($_COOKIE[session_name()]) && !empty($_SESSION['hasLogged']) && $_SESSION['hasLogged']) {
        include ('../Secret.php');
        $username = username;
        $password = password;
        $db = new PDO("mysql:host=localhost;dbname=$username",$username,$password,[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
        try {
            $select = "SELECT * FROM PERSON_LOGIN WHERE login = ?"; //текст запроса
            $result = $db->prepare($select); //подготовка запроса 
            $result->execute([$_SESSION['login']]); //подстановка значения в ?
            $row = $result->fetch(); //из результата запроса выбирает 1 строку и сохран в row 
            // выписывает из строки значения в values
            $formID = $row['ID'];
            $values['FIO'] = $row['FIO'];
            $values['PHONE'] = $row['PHONE'];
            $values['EMAIL'] = $row['EMAIL'];
            $values['BIRTHDATE'] = $row['BIRTHDATE'];
            $values['GENDER'] = $row['GENDER'];
            $values['BIOGRAFY'] = $row['BIOGRAFY'];
            $select = "SELECT LANG_ID FROM PERSON_LANG WHERE U_ID = ?";
            $result = $db->prepare($select);
            $result->execute([$formID]);
            $list = array();
            while ($row = $result->fetch()) {
              $list[] = $row['LANG_ID'];
            }
            $values['LANG'] = $list;
          } catch (PDOException $e) {
            $messages[] = 'Ошибка при загрузке формы из базы данных:<br>' . $e->getMessage();
          }
          $messages[] = "Выполнен вход с логином: <strong>" . $_SESSION['login'] . '</strong><br>';
          $messages[] = '<a href="login.php?exit=1">Выход из аккаунта</a>'; // вывод ссылки для выхода
        }

        // если не вошел, то вывести ссылку для входа
    elseif ($isStarted && !empty($_COOKIE[session_name()])) {
        $messages[] = '<a href="login5.php">Войти в аккаунт</a><br>.';
    }

    include ('form5.php');

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include ('../Secret.php');
    $username = username;
    $password = password;
    $db = new PDO("mysql:host=localhost;dbname=$username",$username,$password,[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


    $errors = FALSE;
    if (empty($_POST['FIO']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['FIO'])) {
        setcookie('FIO_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('FIO_value', $_POST['FIO'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['PHONE']) || !preg_match('/^\+[0-9]{11}$/', $_POST['PHONE'])) {
        setcookie('PHONE_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('PHONE_value', $_POST['PHONE'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['EMAIL']) || !preg_match('/^([a-z0-9_-]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i', $_POST['EMAIL'])) {
        setcookie('EMAIL_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('EMAIL_value', $_POST['EMAIL'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['BIRTHDATE'])) {
        setcookie('BIRTHDATE_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('BIRTHDATE_value', $_POST['BIRTHDATE'], time() + 30 * 24 * 60 * 60);
    }

    $GenderCheck = $_POST['GENDER'] == "1" || $_POST['GENDER'] == "2" || $_POST['GENDER'] == "3";
    if (empty($_POST['GENDER']) || !$GenderCheck) {
        setcookie('GENDER_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('GENDER_value', $_POST['GENDER'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['LANG'])) {
        setcookie('LANG_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        $sth = $db->prepare("SELECT ID FROM LANG");
        $sth->execute();
        $langs = $sth->fetchAll();
        $has_incorrect_lang = false;
        foreach ($_POST['LANG'] as $lang) {
          $flag = true;
          foreach ($langs as $index)
            if ($index[0] == $lang) {
              $flag = false;
              break;
            }
          if ($flag == true) {
            $has_incorrect_lang = true;
            $errors = true;
            break;
          }
        }
        if (!$has_incorrect_lang) {
          setcookie('LANG_value', serialize($_POST['LANG']), time() + 30 * 24 * 60 * 60);
        }
    }

    if (empty($_POST['BIOGRAFY'])) {
        setcookie('BIOGRAFY_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('BIOGRAFY_value', $_POST['BIOGRAFY'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['CH'])) {
        setcookie('CH_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('CH_value', $_POST['CH'], time() + 30 * 24 * 60 * 60);
    }


    if ($errors) {
        header('Location: indexindex5.phpu.php'); //если есть ошибки перезагружаем
        exit();
      } else {
        setcookie('FIO_error', '', -10000); //удалемя куки ошибок
        setcookie('PHONE_error', '', -10000);
        setcookie('EMAIL_error', '', -10000);
        setcookie('BIRTHDATE_error', '', -10000);
        setcookie('GENDER_error', '', -10000);
        setcookie('LANG_error', '', -10000);
        setcookie('BIOGRAFY_error', '', -10000);
        setcookie('CH_error', '', -10000);
      }



      $isStarted = session_start();
    if ($isStarted && !empty($_COOKIE[session_name()]) && !empty($_SESSION['hasLogged'])) {
    // перезапись данных в бд
    try {
      // получаем форму для данного логина
      $login = $_SESSION['login'];
      $select = "SELECT lp.ID FROM PERSON_LOGIN lp, LOGIN lg WHERE lg.login = '$login' AND lp.login = lg.login";
      $result = $db->query($select);
      $row = $result->fetch();
      $formID = $row['ID'];
      // изменение данных в форме
      $updateForm = "UPDATE PERSON_LOGIN SET FIO = ?, PHONE = ?, EMAIL = ?, BIRTHDATE = ?, GENDER = ?, BIOGRAFY = ? WHERE ID = '$formID'";
      $formReq = $db->prepare($updateForm);
      $formReq->execute([$_POST['FIO'], $_POST['PHONE'], $_POST['EMAIL'], $_POST['BIRTHDATE'], $_POST['GENDER'], $_POST['BIOGRAFY']]);
      // удаляем прошлые языки
      $deleteLangs = "DELETE FROM PERSON_LANG WHERE ID = '$formID'";
      $delReq = $db->query($deleteLangs);
      // заполняем заново языки
      $lang = "SELECT ID FROM LANG WHERE ID = ?";
      $feed = "INSERT INTO PERSON_LANG (U_ID, LANG_ID) VALUES (?, ?)";
      $langPrep = $db->prepare($lang);
      $feedPrep = $db->prepare($feed);
      foreach ($_POST['LANG'] as $selection) {
        $langPrep->execute([$selection]);
        $langID = $langPrep->fetchColumn();
        $feedPrep->execute([$formID, $langID]);
      }
    } catch (PDOException $e) {
      setcookie('DBERROR', 'Error : ' . $e->getMessage());
      exit();
    }
  } else {
    // генерируем логин и пароль
    $login = substr(uniqID(), 3);
    $pass = rand(1000000, 9999999);
    // сохраняем в куки
    setcookie('login', $login);
    setcookie('pass', $pass);
    $_SESSION['hasLogged'] = false;

    try {
      $newUser = "INSERT INTO LOGIN (login, password) VALUES (?, ?)";
      $request = $db->prepare($newUser);
      $request->execute([$login, md5($password)]); // сохранил логин и хеш пароля
      //добавляем данные формы нового пользователя  в бд
      $newForm = "INSERT INTO PERSON_LOGIN (login, FIO, PHONE, EMAIL, BIRTHDATE, GENDER, BIOGRAFY) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $formReq = $db->prepare($newForm);
      $formReq->execute([$login, $_POST['FIO'], $_POST['PHONE'], $_POST['EMAIL'], $_POST['BIRTHDATE'], $_POST['GENDER'], $_POST['BIOGRAFY']]);
      $userID = $db->lastInsertID();
      //и заполняет языки
      $lang = "SELECT ID FROM LANG WHERE ID = ?";
      $feed = "INSERT INTO PERSON_LANG (U_ID, LANG_ID) VALUES (?, ?)";
      $langPrep = $db->prepare($lang);
      $feedPrep = $db->prepare($feed);
      foreach ($_POST['selections'] as $selection) {
        $langPrep->execute([$selection]);
        $langID = $langPrep->fetchColumn();
        $feedPrep->execute([$userID, $langID]);
      }
    } catch (PDOException $e) {
      setcookie('DBERROR', 'Error : ' . $e->getMessage());
      exit();
    }
  }

  setcookie('save', '1');//сохранили куку о сохранении
  header('Location: index.php'); //перезагрузка
}
?>
