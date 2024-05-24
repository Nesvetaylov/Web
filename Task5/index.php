<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
include("../Secret.php");

$username = username;
$password = password;
$dbname = username;

if (isset($_SESSION['login']) && isset($_SESSION['password'])) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fio = $_POST['FIO'];
            $phone = $_POST['PHONE'];
            $email = $_POST['EMAIL'];
            $birthdate = $_POST['BIRTHDATE'];
            $gender = $_POST['GENDER'];
            $biografy = $_POST['BIOGRAFY'];
            $langs = isset($_POST['Lang_Prog']) ? (array)$_POST['Lang_Prog'] : [];

            if (empty($fio) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $fio)) {
                $errors['FIO'] = true;
            }

            if (empty($phone) || !preg_match('/^[0-9+]+$/', $phone)) {
                $errors['PHONE'] = true;
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['EMAIL'] = true;
            }

            if (!empty($birthdate) && !DateTime::createFromFormat('Y-m-d', $birthdate)) {
                $errors['BIRTHDATE'] = true;
            }

            if (!in_array($gender, ['male', 'female'])) {
                $errors['GENDER'] = true;
            }

            if (empty($biografy)) {
                $errors['BIOGRAFY'] = true;
            }

            if (empty($langs)) {
                $errors['Lang_Prog'] = true;
            }

            if (!$errors) {
                $stmt = $conn->prepare("UPDATE REQUEST SET FIO = ?, PHONE = ?, EMAIL = ?, BIRTHDATE = ?, GENDER = ?, BIOGRAFY = ? WHERE login = ? AND password = ?");
                $stmt->execute([$fio, $phone, $email, $birthdate, $gender, $biografy, $_SESSION['login'], $_SESSION['password']]);

                $Lang_selection = "SELECT Lang_ID FROM Lang_Prog WHERE Lang_NAME = ?";
                $Lang_prepare = $conn->prepare($Lang_selection);
                $Answer_insert = "INSERT INTO ANSWER (ID, Lang_ID) VALUES (?, ?)";
                $Answer_prepare = $conn->prepare($Answer_insert);

                $stmt = $conn->prepare("DELETE FROM ANSWER WHERE ID = ?");
                $stmt->execute([$_SESSION['user_id']]);

                foreach ($langs as $lang) {
                    $Lang_prepare->execute([$lang]);
                    $lang_ID = $Lang_prepare->fetchColumn();
                    $Answer_prepare->execute([$_SESSION['user_id'], $lang_ID]);
                }

                $messages[] = 'Your information has been successfully updated.';
            } else {
                $messages[] = 'Please correct the errors before submitting the form.';
            }
        }

        $stmt = $conn->prepare("SELECT * FROM REQUEST WHERE login = ? AND password = ?");
        $stmt->execute([$_SESSION['login'], $_SESSION['password']]);
        $user = $stmt->fetch();

        $stmt = $conn->prepare("SELECT Lang_NAME FROM ANSWER INNER JOIN Lang_Prog ON ANSWER.Lang_ID = Lang_Prog.Lang_ID WHERE ID = ?");
        $stmt->execute([$user['ID']]);
        $values['Lang_Prog'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $values['FIO'] = $user['FIO'];
        $values['PHONE'] = $user['PHONE'];
        $values['EMAIL'] = $user['EMAIL'];
        $values['BIRTHDATE'] = $user['BIRTHDATE'];
        $values['GENDER'] = $user['GENDER'];
        $values['BIOGRAFY'] = $user['BIOGRAFY'];

    } catch (PDOException $e) {
        $mas[] = "Connection failed: " . $e->getMessage();
    }
    $conn = null;
} else {
    header('Location: login.php');
    exit();
}

// Function to generate a random string
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$errors = $messages = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_COOKIE['SAVE'])) {
        $messages[] = 'Спасибо, результаты сохранены.';
        setcookie('SAVE', '', 100000);
    }

    $values = [
        'FIO' => empty($_COOKIE['FIO_value']) ? '' : $_COOKIE['FIO_value'],
        'PHONE' => empty($_COOKIE['PHONE_value']) ? '' : $_COOKIE['PHONE_value'],
        'EMAIL' => empty($_COOKIE['EMAIL_value']) ? '' : $_COOKIE['EMAIL_value'],
        'BIRTHDATE' => empty($_COOKIE['BIRTHDATE_value']) ? '' : $_COOKIE['BIRTHDATE_value'],
        'GENDER' => empty($_COOKIE['GENDER_value']) ? '' : $_COOKIE['GENDER_value'],
        'BIOGRAFY' => empty($_COOKIE['BIOGRAFY_value']) ? '' : $_COOKIE['BIOGRAFY_value'],
        'Lang_Prog' => empty($_COOKIE['Lang_Prog_value']) ? [] : unserialize($_COOKIE['Lang_Prog_value']),
    ];

    include('form.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($errors) {
        header('Location: index.php');
        exit();
    } else {
        // Delete cookies with error flags
        setcookie('FIO_error', '', 100000);
        setcookie('PHONE_error', '', 100000);
        setcookie('EMAIL_error', '', 100000);
        setcookie('BIRTHDATE_error', '', 100000);
        setcookie('GENDER_error', '', 100000);
        setcookie('BIOGRAFY_error', '', 100000);
        setcookie('Lang_Prog_error', '', 100000);
    }
}

include('form.php');
?>
