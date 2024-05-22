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
        echo "Номер доктора: " . $row['DOCTOR_ID'] . "<br>";
        echo "ФИО доктора: " . $row['FIO_DOCTOR'] . "<br>";
        echo "Специальность: " . $row['SPECIALITY_DOCTOR'] . "<br>";
        echo "Стоимость приёма: " . $row['COST_OF_ADMISSION'] . "<br>";
        echo "Процент отчисления: " . $row['PERCENTAGE_OF_SALARY'] . "<br><br><br>";
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
