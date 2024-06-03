<?php
header('Content-Type: text/html; charset=UTF-8');// Отправляем браузеру правильную кодировку, файл login.php должен быть в кодировке UTF-8 без BOM.

// Сохраним суперглобальный массив $_SESSION в переменные сессии логин после успешной авторизации.
$session_started = false;
if ($_COOKIE[session_name()] && session_start()) {
    $session_started = true;
    if (!empty($_GET['EXIT'])) {
        // выход (окончание сессии session_destroy() при нажатии на кнопку Выход).
        session_destroy();
        header('Location: index.php');
        exit();
    }
    if (!empty($_SESSION['hasLogged']) && $_SESSION['hasLogged'] = true) {
        // Если есть логин в сессии, то пользователь уже авторизован, перенаправляем на форму.
        header('Location: ./');
        exit();
    }
}
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    ?>

    <form action="" method="post">
        <h3 id="forma">
            ВХОД В АККАУНТ
            <h3>
                <strong>
                    Логин:
                </strong>
                <input name="login" />
                <br>
                <strong>
                    Пароль:
                </strong>
                <input name="pass" />
                <br>
                <input type="submit" value="Войти" />
    </form>
    <?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
    include ('../Secret.php');
    $username = username;
    $password = password;
    $db = new PDO("mysql:host=localhost;dbname=$username", $username, $password,[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    // проверка наличия логина в базе данных
    $loginFlag = false;
    try {
        $select = "SELECT * FROM LOGIN";
        $result = $db->query($select);
        if (!$session_started) {
            session_start();
        }
        while ($row = $result->fetch()) {
            if ($_POST['login'] == $row['login'] && md5($_POST['pass']) == $row['password']) {
                $loginFlag = true;
                break;
            }
        }
    } catch (PDOException $e) {
        setcookie('DBERROR', 'Error : ' . $e->getMessage());
        exit();
    }

    // Если все ок, то авторизуем пользователя.
    if ($loginFlag) {
        $_SESSION['hasLogged'] = true;
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['pass'] = $_POST['pass'];
    } else {
        $_SESSION['hasLogged'] = false;
        $_SESSION['login'] = '';
        $_SESSION['pass'] = '';
        setcookie('AUTHERROR', 'Неверный логин или пароль');
    }

    header('Location: ./');//перенаправлем
}
?>
