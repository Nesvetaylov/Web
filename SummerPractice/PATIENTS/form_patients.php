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
        <title>Добавить пациента</title>
        <style>
            .error {
                border: 2px solid red;
            }
        </style>
    </head>

    <body>
        <div class='form-container'>
            <h2>Форма для заполнения информации о пациенте</h2> 
            <form action="patients.php" method="POST">

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

                <button type="submit" class="button">Добавить пациента</button>
            </form>
        </div>
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
    </body>
</html>
