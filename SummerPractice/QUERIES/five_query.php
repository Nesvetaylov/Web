<?php
$v = rand();
echo "<link rel='stylesheet' type='text/css' href='../style_query_1.css?v=$v' media='screen' />";
include("../Secret.php");
$username = username;
$password = password;

// Запрос 5: Выбирает из таблиц ВРАЧИ, ПАЦИЕНТЫ и ПРИЕМ информацию обо всех приемах в некоторый заданный интервал времени
// Нижняя и верхняя границы интервала задаются при выполнении запроса, например, '2024-04-08' и '2024-08-08'
echo "<h2>Запрос 5: Выбирает из таблиц ВРАЧИ, ПАЦИЕНТЫ и ПРИЕМ информацию обо всех приемах в некоторый заданный интервал времени</h2>";
try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $start_date = '2024-04-08';
    $end_date = '2024-08-08';
    $query = "SELECT d.DOCTOR_ID, d.FIO_DOCTOR, d.SPECIALITY_DOCTOR, d.COST_OF_ADMISSION, d.PERCENTAGE_OF_SALARY
              FROM DOCTORS d
              JOIN ADMISSION_OF_PATIENTS ap ON d.DOCTOR_ID = ap.DOCTOR_ID
              JOIN PATIENTS p ON ap.PATIENT_ID = p.PATIENT_ID
              WHERE ap.DATE BETWEEN '$start_date' AND '$end_date'";
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