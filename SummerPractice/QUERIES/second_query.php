<?php

include("../Secret.php");
$username = username;
$password = password;


// Запрос 2: Выбирает из таблицы ПАЦИЕНТЫ информацию о пациентах, родившихся до 01.01.2000
echo "<h2>Запрос 2: Выбирает из таблицы ПАЦИЕНТЫ информацию о пациентах, родившихся до 01.01.2000</h2>";
try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $query = "SELECT * FROM PATIENTS WHERE BIRTHDATE < 01.01.2000";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "Номер пациента: " . $row['PATIENT_ID'] . "<br>";
        echo "Фамилия: " . $row['LAST_NAME'] . "<br>";
        echo "Имя: " . $row['FIRST_NAME'] . "<br>";
        echo "Отчество: " . $row['MIDDLE_NAME'] . "<br>";
        echo "Дата рождения: " . $row['BIRTHDATE'] . "<br>";
        echo "Адрес: " . $row['ADDRESS'] . "<br><br><br>";
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
