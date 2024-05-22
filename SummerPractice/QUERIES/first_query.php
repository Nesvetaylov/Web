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
        echo "DOCTOR_ID: " . $row['DOCTOR_ID'] . "\n";
        echo "FIO_DOCTOR: " . $row['FIO_DOCTOR'] . "\n";
        echo "SPECIALITY_DOCTOR: " . $row['SPECIALITY_DOCTOR'] . "\n";
        echo "COST_OF_ADMISSION: " . $row['COST_OF_ADMISSION'] . "\n";
        echo "PERCENTAGE_OF_SALARY: " . $row['PERCENTAGE_OF_SALARY'] . "\n\n";
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
