<?php
header('Content-Type: application/json; charset=UTF-8');

include("../Secret.php");
$username = username;
$password = password;

try {
    $conn = new PDO(
        "mysql:host=localhost;dbname=$username",
        $username,
        $password,
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $query = "select * from DOCTORS where SPECIALITY_DOCTOR='Хирург';";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $json_string = json_encode($results, JSON_UNESCAPED_UNICODE);
    $data = json_decode($json_string, true);

    echo "<pre>";
    print_r($data);
    echo "</pre>";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
?>
