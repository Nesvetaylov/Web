<?php
$v = rand();
echo "<link rel='stylesheet' type='text/css' href='../style_query_1.css?v=$v' media='screen' />";
include("../Secret.php");
$username = username;
$password = password;


// Запрос 6: Вычисляет размер заработной платы врача за каждый прием
echo "<h2>Запрос 6: Вычисляет размер заработной платы врача за каждый прием</h2>";
try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $query = "SELECT d.DOCTOR_ID, d.FIO_DOCTOR, d.SPECIALITY_DOCTOR, aop.DATE, d.COST_OF_ADMISSION, d.PERCENTAGE_OF_SALARY
    FROM DOCTORS d
    JOIN ADMISSION_OF_PATIENTS aop ON d.DOCTOR_ID = aop.DOCTOR_ID;";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<table class="table">';
    echo '<tr>';
    echo "<td>Номер доктора</td>";
    echo "<td>ФИО доктора</td>";
    echo "<td>Специальность</td>";
    echo "<td>Дата приёма</td>";
    echo "<td>Стоимость приёма</td>";
    echo "<td>Процент отчисления</td>";
    echo "<td>Зарплата за один приём</td>";
    echo '</tr>';
    foreach ($results as $row) {
        echo '<tr>';
        echo "<td>" . $row['DOCTOR_ID'] . "</td>";
        echo "<td>" . $row['FIO_DOCTOR'] . "</td>";
        echo "<td>" . $row['SPECIALITY_DOCTOR'] . "</td>";
        echo "<td>" . $row['DATE'] . "</td>";
        echo "<td>" . $row['COST_OF_ADMISSION'] . "</td>";
        echo "<td>" . $row['PERCENTAGE_OF_SALARY'] . "</td>";
        echo "<td>" . $row['COST_OF_ADMISSION'] * $row['PERCENTAGE_OF_SALARY'] / 100 . "</td>";
        echo '</tr>';
    }
    echo '</table>';
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
