<?php
header('Content-Type: text/html; charset=UTF-8');

// Generate unique login and password
function generateLoginPassword() {
    $login = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
    $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 12);
    return array($login, $password);
}

// Check if user is logged in
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
} else {
    $username = '';
    $password = '';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate form data
    $errors = array();
    if (empty($_POST['FIO']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['FIO'])) {
        $errors[] = 'Ошибка ФИО';
    }
    if (empty($_POST['PHONE']) || !preg_match('/^[0-9+]+$/', $_POST['PHONE'])) {
        $errors[] = 'Ошибка Телефона';
    }
    if (empty($_POST['EMAIL']) || !filter_var($_POST['EMAIL'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Ошибка ЕМАИЛ';
    }
    $dateObject = DateTime::createFromFormat('Y-m-d', $_POST['BIRTHDATE']);
    if ($dateObject === false || $dateObject->format('Y-m-d') !== $_POST['BIRTHDATE']) {
        $errors[] = 'Ошибка Дата рождения';
    }
    if ($_POST['GENDER'] != 'male' && $_POST['GENDER'] != 'female') {
        $errors[] = 'Ошибка пола';
    }
    if (empty($_POST['BIOGRAFY'])) {
        $errors[] = 'Ошибка биографии';
    }
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div>";
        }
    } else {
        // Generate unique login and password if not logged in
        if (empty($username) && empty($password)) {
            list($username, $password) = generateLoginPassword();
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
        }
        // Save data to database
        try {
            $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO REQUEST (FIO, PHONE, EMAIL, BIRTHDATE, GENDER, BIOGRAFY) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_POST['FIO'], $_POST['PHONE'], $_POST['EMAIL'], $_POST['BIRTHDATE'], $_POST['GENDER'], $_POST['BIOGRAFY']]);
            $lastId = $conn->lastInsertId();
            $Lang_selection = "SELECT Lang_ID FROM Lang_Prog WHERE Lang_NAME = ?";
            $Lang_prepare = $conn->prepare($Lang_selection);
            $Answer_insert = "INSERT INTO ANSWER (ID, Lang_ID) VALUES (?, ?)";
            $Answer_prepare = $conn->prepare($Answer_insert);
            foreach ($_POST['Lang_Prog'] as $lang) {
                $Lang_prepare->execute([$lang]);
                $lang_ID = $Lang_prepare->fetchColumn();
                $Answer_prepare->execute([$lastId, $lang_ID]);
            }
            echo nl2br("\nNew record created successfully");
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        $conn = null;
    }
}

// Display form
include('form4.php');

// Display saved data if logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM REQUEST WHERE FIO = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['username']]);
        $result = $stmt->fetch();
        echo "<h2>Ваши данные:</h2>";
        echo "<p>ФИО: " . $result['FIO'] . "</p>";
        echo "<p>Телефон: " .$result['PHONE'] . "</p>";
        echo "<p>Email: " . $result['EMAIL'] . "</p>";
        echo "<p>Дата рождения: " . $result['BIRTHDATE'] . "</p>";
        echo "<p>Пол: " . $result['GENDER'] . "</p>";
        echo "<p>Биография: " . $result['BIOGRAFY'] . "</p>";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    $conn = null;
}
?>
