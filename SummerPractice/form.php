<html>
    <head>
    <style>
    .error {
    border: 2px solid red;}
    </style>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <meta charset="utf-8">
    <title>Летняя практика</title>
    </head>

    <body>
      <?php
      if (!empty($messages)) {
        print('<div id="messages">');
        // Выводим все сообщения.
        foreach ($messages as $message) {
          print($message);
        }
        print('</div>');
      }
      ?>
    <form action="index.php" method="POST">
        <h2>Форма для заполнения информации о враче</h2>
        <label for="FIO_DOCTOR">ФИО врача:</label>
        <input name="FIO_DOCTOR" type="text"><br><br>

        <label for="SPECIALITY_DOCTOR">Специальность врача:</label>
        <input name="SPECIALITY_DOCTOR" type="text"><br><br>

        <label for="COST_OF_ADMISSION">Стоимость приема:</label>
        <input name="COST_OF_ADMISSION" type="text"><br><br>

        <label for="PERCENTAGE_OF_SALARY">Процент отчисления на зарплату:</label>
        <input name="PERCENTAGE_OF_SALARY" type="text"><br><br>

        <input type="submit" value="Отправить">
    </form>
    </body>
</html>
