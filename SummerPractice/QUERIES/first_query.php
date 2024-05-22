<?php

$v = rand();
echo "<link rel='stylesheet' type='text/css' href='../style_query_1.css?v=$v' media='screen' />";

include("../Secret.php");
$username = username;
$password = password;

// Запрос 1: Выбирает из таблицы ВРАЧИ информацию о врачах, имеющих конкретную специальность (например, хирург)
echo "<h2>Запрос 1: Выбирает из таблицы ВРАЧИ информацию о врачах, имеющих конкретную специальность (например, хирург)</h2>";
try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $query = "select * from DOCTORS where SPECIALITY_DOCTOR='Хирург';";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<table class="table">';
    echo '<tr>';
    echo "<td>Номер доктора</td>";
    echo "<td>ФИО доктора</td>";
    echo "<td>Специальность</td>";
    echo "<td>Стоимость приёма</td>";
    echo "<td>Процент отчисления</td>";
    echo '</tr>';
    foreach ($results as $row) {
        echo '<tr>';
        echo "<td>" . $row['DOCTOR_ID'] . "</td>";
        echo "<td>" . $row['FIO_DOCTOR'] . "</td>";
        echo "<td>" . $row['SPECIALITY_DOCTOR'] . "</td>";
        echo "<td>" . $row['COST_OF_ADMISSION'] . "</td>";
        echo "<td>" . $row['PERCENTAGE_OF_SALARY'] . "</td>";
        echo '</tr>';
    }
    echo '</table>';

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
