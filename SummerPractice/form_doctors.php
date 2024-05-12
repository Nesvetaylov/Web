<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- <style>
    .error {
    border: 2px solid red;}
    </style> -->
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Добавить врача</title>
    </head>

    <body>
      <div class='form-container'>
      <form action="doctors.php" method="POST">
        <h2>Форма для заполнения информации о враче</h2>

        <div class='form-section'>
          <label for="FIO_DOCTOR">ФИО врача:</label>
          <input type="text" class="form-control"  name="FIO_DOCTOR" <?php if (isset($errors['FIO_DOCTOR'])) {print 'class="error"';} ?>
        value="<?php if (isset($values['FIO_DOCTOR'])) {print $values['FIO_DOCTOR'];} ?>" />
        </div>

        <div class='form-section'>
          <label for="SPECIALITY_DOCTOR">Специальность врача:</label>
          <select class="form-control" id="SPECIALITY_DOCTOR" name="SPECIALITY_DOCTOR" <?php if (isset($errors['SPECIALITY_DOCTOR'])) {echo 'class="error"';} ?>>
            <option value="Кардиолог" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Кардиолог') ? 'selected' : ''; ?>>Кардиолог</option>
            <option value="Терапевт" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Терапевт') ? 'selected' : ''; ?>>Терапевт</option>
            <option value="Хирург" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Хирург') ? 'selected' : ''; ?>>Хирург</option>
            <option value="Педиатр" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Педиатр') ? 'selected' : ''; ?>>Педиатр</option>
            <option value="Гинеколог" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Гинеколог') ? 'selected' : ''; ?>>Гинеколог</option>
            <option value="Психиатр" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Психиатр') ? 'selected' : ''; ?>>Психиатр</option>
            <option value="Невролог" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Невролог') ? 'selected' : ''; ?>>Невролог</option>
            <option value="Ортопед" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Ортопед') ? 'selected' : ''; ?>>Ортопед</option>
            <option value="Дерматолог" <?php echo (isset($values['SPECIALITY_DOCTOR']) && $values['SPECIALITY_DOCTOR'] == 'Дерматолог') ? 'selected' : ''; ?>>Дерматолог</option>
          </select>
        </div>

        <div class="form-section">
          <label for="COST_OF_ADMISSION">Стоимость приема:</label>
          <input type="number"  class="form-control"  id="COST_OF_ADMISSION" name="COST_OF_ADMISSION" <?php if (isset($errors['COST_OF_ADMISSION'])) {print 'class="error"';} ?>
          value="<?php if (isset($values['COST_OF_ADMISSION'])) {print $values['COST_OF_ADMISSION'];} ?>" />
        </div>

        <div class="form-section">
          <label for="PERCENTAGE_OF_SALARY">Процент отчисления:</label>
          <input type="number"  class="form-control" id="PERCENTAGE_OF_SALARY" name="PERCENTAGE_OF_SALARY" <?php if (isset($errors['PERCENTAGE_OF_SALARY'])) {print 'class="error"';} ?>
          value="<?php if (isset($values['PERCENTAGE_OF_SALARY'])) {print $values['PERCENTAGE_OF_SALARY'];} ?>" />
        </div>

        <button type="submit" class="button">Добавить врача</button>
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
