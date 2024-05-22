<?php

include("../Secret.php");
$username = username;
$password = password;

try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $query = "select * from DOCTORS where SPECIALITY_DOCTOR='Хирург';";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "DOCTOR_ID: " . $row['DOCTOR_ID'] . "<br>";
        echo "FIO_DOCTOR: " . $row['FIO_DOCTOR'] . "<br>";
        echo "SPECIALITY_DOCTOR: " . $row['SPECIALITY_DOCTOR'] . "<br>";
        echo "COST_OF_ADMISSION: " . $row['COST_OF_ADMISSION'] . "<br>";
        echo "PERCENTAGE_OF_SALARY: " . $row['PERCENTAGE_OF_SALARY'] . "<br>";
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
