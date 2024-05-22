<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <?php 
            $v = rand();
            echo "<link rel='stylesheet' type='text/css' href='../style.css?v=$v' media='screen' />";
        ?>
        <link rel="stylesheet" href="style.css" >
        <title>Заявление на приём</title>
        <style>
            .error {
                border: 2px solid red;
            }
            .buttons{
                display: flex;
                flex-direction: row;
                margin:5px;
                justify-content: space-evenly;
                align-items: center;
                }
        </style>
    </head>

    <body>
        <div class='form-container'>
            <h2>Форма для заполнения информации о пациенте</h2>
            <form action="admission_of_patients.php" method="POST">
                <div class="form-section">
                    <label for="LAST_NAME">Фамилия:</label>
                    <input type="text" class="form-control" id="LAST_NAME" name="LAST_NAME" <?php if (($errors['LAST_NAME'])) {print 'class="error"';} ?>
                    value="<?php if (isset($values['LAST_NAME'])) {print $values['LAST_NAME'];} ?>" />
                </div>

                <div class="form-section">
                    <label for="FIRST_NAME">Имя:</label>
                    <input type="text" class="form-control" id="FIRST_NAME" name="FIRST_NAME" <?php if (($errors['FIRST_NAME'])) {print 'class="error"';} ?>
                    value="<?php if (isset($values['FIRST_NAME'])) {print $values['FIRST_NAME'];} ?>" />
                </div>

                <div class="form-section">
                    <label for="MIDDLE_NAME">Отчество:</label>
                    <input type="text" class="form-control" id="MIDDLE_NAME" name="MIDDLE_NAME" <?php if (($errors['MIDDLE_NAME'])) {print 'class="error"';} ?>
                    value="<?php if (isset($values['MIDDLE_NAME'])) {print $values['MIDDLE_NAME'];} ?>" />
                </div>

                <div class="form-section">
                    <label for="BIRTHDATE">Дата рождения:</label>
                    <input type="date" class="form-control" id="BIRTHDATE" name="BIRTHDATE" <?php if (($errors['BIRTHDATE'])) {print 'class="error"';} ?>
                    value="<?php if (isset($values['BIRTHDATE'])) {print $values['BIRTHDATE'];} ?>" />
                </div>

                <div class="form-section">
                    <label for="ADDRESS">Адрес:</label>
                    <input type="text" class="form-control" id="ADDRESS" name="ADDRESS" <?php if (($errors['ADDRESS'])) {print 'class="error"';} ?>
                    value="<?php if (isset($values['ADDRESS'])) {print $values['ADDRESS'];} ?>" />
                </div>

                <div class="form-section">
                    <label for="specialty">Выбрать врача:</label>
                    <?php
                        include("../Secret.php");
                        $servername='localhost';
                        $username = username;
                        $password = password;
                        $dbname = username;

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT DOCTOR_ID,SPECIALITY_DOCTOR, FIO_DOCTOR FROM DOCTORS";
                        $result = $conn->query($sql);
                        echo "<select name='DOCTOR_ID' class='form-control'>";

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $id = $row["DOCTOR_ID"];
                                $speciality = $row["SPECIALITY_DOCTOR"];
                                $fio = $row["FIO_DOCTOR"];
                                echo "<option value='$id'>$fio ($speciality)</option>";
                            }
                        }
                        else {
                            echo "<option>No data available</option>";
                        }
                        echo "</select>";
                        $conn->close();
                    ?>

                    <label for="date">Дата приема:</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" >
                </div>
                <div class="buttons">
                    <button type="submit"  class="button">Записать на прием</button>
                    <br>
                    <!-- <a href="quintation.php">
                        <button type="button" class="button">Сформировать квитанцию об оплате</button>
                    </a> -->
                </div>
            </form>
        </div>
    </body>
</html>
