<?php
$v = rand();
echo "<link rel='stylesheet' type='text/css' href='../style_query_1.css?v=$v' media='screen' />";
include("../Secret.php");
$username = username;
$password = password;


// Запрос 2: Выбирает из таблицы ПАЦИЕНТЫ информацию о пациентах, родившихся до 01.01.2000
echo "<h2>Запрос 2: Выбирает из таблицы ПАЦИЕНТЫ информацию о пациентах, родившихся до 01.01.2000</h2>";
try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $query = "SELECT * FROM PATIENTS WHERE BIRTHDATE < '2000-01-01'";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<table class="table">';
    echo '<tr>';
    echo "<td>Номер пациента</td>";
    echo "<td>Фамилия</td>";
    echo "<td>Имя</td>";
    echo "<td>Отчество</td>";
    echo "<td>Дата рождения</td>";
    echo "<td>Адрес</td>";
    echo '</tr>';
    foreach ($results as $row) {
        echo '<tr>';
        echo "<td>" . $row['PATIENT_ID'] . "</td>";
        echo "<td>" . $row['LAST_NAME'] . "</td>";
        echo "<td>" . $row['FIRST_NAME'] . "</td>";
        echo "<td>" . $row['MIDDLE_NAME'] . "</td>";
        echo "<td>" . $row['BIRTHDATE'] . "</td>";
        echo "<td>" . $row['ADDRESS'] . "</td>";
        echo '</tr>';
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
