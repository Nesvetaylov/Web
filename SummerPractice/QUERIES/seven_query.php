<?php $v = rand(); echo "<link rel='stylesheet' type='text/css' href='../style_query_1.css?v=$v' media='screen' />"; 
include("../Secret.php"); 

$username = username;
$password = password;

// Запрос 7: Выполняет группировку по полю Дата приема и вычисляет среднюю стоимость приема
echo "<h2> Запрос 7: Выполняет группировку по полю Дата приема и вычисляет среднюю стоимость приема</h2>";

try {
    $conn = new PDO( "mysql:host=localhost;dbname=$username", $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] );
    $query = "SELECT a.DATE, AVG(d.COST_OF_ADMISSION) AS Средняя_стоимость_приема FROM ADMISSION_OF_PATIENTS a JOIN DOCTORS d ON a.DOCTOR_ID = d.DOCTOR_ID GROUP BY a.DATE";
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<table class="table">';
    echo '<tr>';
    echo "<td>Дата</td>";
    echo "<td>Средняя стоимость приема</td>";
    echo '</tr>';

    foreach ($results as $row) {
        echo '<tr>';
        echo "<td>" . $row['DATE'] . "</td>";
        echo "<td>" . $row['Средняя_стоимость_приема'] . "</td>";
        echo '</tr>';
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
