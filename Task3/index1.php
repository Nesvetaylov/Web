<?php
$servername = "localhost";
$username = "u67281";
$password = "9872763";
$dbname = "u67281";


$fio = $phone = $email = $birthdate = $gender = '';
$fio = $_POST['FIO'];
$phone = $_POST['PHONE'];
$email = $_POST['EMAIL'];
$birthdate = $_POST['BIRTHDATE'];
$gender = $_POST['GENDER'];
$bio = $_POST['BIOGRAFY'];
$langs = $_POST['Lang_Prog'];
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

if (empty($fio) || !preg_match('/^[A-Za-z]+$/', $fio)) {
    $errors = TRUE;
}

if (empty($phone) || !preg_match('/^[0-9+]+$/', $phone)) {
    $errors = TRUE;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = TRUE;
}


$dateObject = DateTime::createFromFormat('Y-m-d', $birthdate);
if ($dateObject === false || $dateObject->format('Y-m-d') !== $birthdate) {
    $errors = TRUE;
}

if ($gender != 'male' && $gender != 'female') {
    $errors = TRUE;
}

if (!checkLangs($langs, $langs_check)) {
    $errors = TRUE;
}

if ($errors === TRUE) {
    echo 'mistake';
    exit();
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully ";
    $sql = "INSERT INTO request (FIO, PHONE, EMAIL, BIRTHDATE, GENDER, BIOGRAFY)
VALUES ('$fio', '$phone', '$email', '$birthdate', '$gender', '$bio')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $lastId = $conn->lastInsertId();

    for ($i = 0; $i < count($langs); $i++) {
        $sql = "SELECT Lang_ID FROM Lang_Prog WHERE Lang_NAME = :langName";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':langName', $langs[$i]);
        $stmt->execute();
        $result = $stmt->fetch();
        $Lang_ID = $result['Lang_ID'];
        $sql = "INSERT INTO request_to_lang (ID, Lang_ID) VALUES ($lastId, $Lang_ID)";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    echo nl2br("\nNew record created successfully");
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>
