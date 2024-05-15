<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css" >
        <style>
            body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    padding: 20px;
}

.form-container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 12px;
    border: 3px solid #a2a2a2;
    /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
    max-width: 600px;
    margin: auto;
    margin-top: 50px;
}

.form-container h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.form-section {
    margin-bottom: 20px;
}

.form-section label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.error {
    border: 1px solid red;
}

.button {
    background-color: #0f5d75;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: block;
    margin: 0 auto;
}

.button:hover {
    background-color: #26829e;
}

#messages {
    margin-top: 20px;
    background-color: #f8d7da;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
        </style>
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
