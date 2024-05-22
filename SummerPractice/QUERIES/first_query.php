<?php

include("../Secret.php");
$username = username;
$password = password;
$opt = $_POST['options'];

if ($opt=="1") {
    // текст SQL запроса, который будет передан базе
    $query = "select * from DOCTORS where SPECIALITY_DOCTOR='Хирург';"
    
     // выполняем запрос к базе данных
     $results = mysqli_query($connection, $query);

 
    // выводим полученные данные
    while($row = $results->fetch_assoc()){
      echo  $row['FIO_DOCTOR'] . ' - ' . $row['SPECIALITY_DOCTOR'] . ' - ' . $row['COST_OF_ADMISSION'] . ' - ' . $row['PERCENTAGE_OF_SALARY'] . "<br>";
    }
  }
catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    die();
}
