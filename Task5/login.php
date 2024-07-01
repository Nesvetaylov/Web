<?php
header('Content-Type: text/html; charset=UTF-8');

$session_started = false;
if ($_COOKIE[session_name()] && session_start()) {
    $session_started = true;
    if (!empty($_GET['exit'])) {
        session_destroy();
        header('Location: index.php');
        exit();
    }
    if (!empty($_SESSION['hasLogged']) && $_SESSION['hasLogged'] = true) {
        header('Location: ./');
        exit();
    }
}
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
                <input name="password" />
                <br>
                <input type="submit" value="Войти" />
    </form>
    <style>
        .fomaa {
    width: 30%;
    margin: 0 auto;
    padding: 20px;
    background-color: #9effc6; 
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

form {
    width: 30%;
    margin: 0 auto;
    padding: 20px;
    background-color: #9effc6; 
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    color: #000000; 
}

strong {
    display: block;
    margin: 10px 0;
    color: #000000; 
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #000000; 
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
    margin-bottom: 10px;
}

input[type="submit"] {
    background-color: #184c2d; 
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
}

input[type="submit"]:hover {
    background-color: #000000;
}
    </style>
    <?php
}
else {
    include ('../Secret.php');
    $username = username;
    $password = password;
    $db = new PDO(
        "mysql:host=localhost;dbname=$username",
        $username,
        $password,
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $loginFlag = false;
    try {
        $select = "SELECT * FROM Logi";
        $result = $db->query($select);
        if (!$session_started) {
            session_start();
        }
        while ($row = $result->fetch()) {
            if ($_POST['login'] == $row['login'] && md5($_POST['password']) == $row['password']) {
                $loginFlag = true;
                break;
            }
        }
    } catch (PDOException $e) {
        setcookie('DBERROR', 'Error : ' . $e->getMessage());
        exit();
    }
    if ($loginFlag) {
        $_SESSION['hasLogged'] = true;
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
    } else {
        $_SESSION['hasLogged'] = false;
        $_SESSION['login'] = '';
        $_SESSION['password'] = '';
        setcookie('AUTHERROR', 'Неверный логин или пароль');
    }

    header('Location: ./');
}
?>
