<?php

include("../Secret.php");
$username = username;
$password = password;

try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $query = "select * from DOCTORS where SPECIALITY_DOCTOR='Хирург';";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = "";
    foreach ($results as $row) {
        $output .= print_r($row, true) . "\n";
    }

    echo $output;
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
