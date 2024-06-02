<html>
<head>
    <style>
    .error {
    border: 2px solid red;}
    </style>
    <link rel="stylesheet" href="style.css">
    <meta charset="utf-8">
    <title>
        <a 4 Задание>
        </a>
    </title>
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
<form class='form-container' action="index5.php" method="POST">
        <label>
          <strong> Фамилия имя отчество:</strong>
          <br>
          <input name="FIO" type="text"
          <?php if ($errors['FIO']) {print 'class="error"';} ?> value="<?php print $values['FIO']; ?>" placeholder="ФИО" />
      </label>
    <!-- <label for="FIO">Full name:</label> -->
    <!-- <input class="lab" type="text" id="FIO" name="FIO" required> -->
    <!-- <input name="FIO" type="text"<?php// if ($errors['FIO']) {print 'class="error"';} ?> value="<?php// print $values['FIO']; ?>" placeholder="ФИО"/> -->
    <br>
    <label for="PHONE">Phone number:</label>
    <input name="PHONE" <?php if ($errors['PHONE']) {print 'class="error"';} ?> value="<?php print $values['PHONE']; ?>">
    <br>
    <label for="EMAIL">e-mail:</label>
    <input name="EMAIL" <?php if ($errors['EMAIL']) {print 'class="error"';} ?> value="<?php print $values['EMAIL']; ?>">
    <br>
    <label for="BIRTHDATE">Date of Birth:</label>
    <input value="2023-09-24" name="BIRTHDATE" <?php if ($errors['BIRTHDATE']) {print 'class="error"';} ?> value="<?php print $values['BIRTHDATE']; ?>">
    <br>
    <label for="GENDER">Choose gender:</label>
    <input type="radio" id="male" name="GENDER" value="male" <?php if($values['GENDER']=='male'){print 'checked';} ?>>
    <label for="male">Мужской</label>
    <input type="radio" id="female" name="GENDER" value="female" <?php if($values['GENDER']=='female'){print 'checked';}?>>
    <label for="male">Женский</label>
    <br>
    <label>
        <select class="lab" name="LANG[]" multiple="multiple">
            <option value="1" <?php if (in_array('1', $values['LANG'])) { print 'selected'; } ?>> Pascal</option>
            <option value="2" <?php if (in_array('2', $values['LANG'])) { print 'selected'; } ?>> C</option>
            <option value="3" <?php if (in_array('3', $values['LANG'])) { print 'selected'; } ?>> C++</option>
            <option value="4" <?php if (in_array('4', $values['LANG'])) { print 'selected'; } ?>> JavaScript</option>
            <option value="5" <?php if (in_array('5', $values['LANG'])) { print 'selected'; } ?>> PHP</option>
            <option value="6" <?php if (in_array('6', $values['LANG'])) { print 'selected'; } ?>> Python</option>
            <option value="7" <?php if (in_array('7', $values['LANG'])) { print 'selected'; } ?>> Java</option>
            <option value="8" <?php if (in_array('8', $values['LANG'])) { print 'selected'; } ?>> Haskel</option>
            <option value="9" <?php if (in_array('9', $values['LANG'])) { print 'selected'; } ?>> Clojure</option>
            <option value="10" <?php if (in_array('10', $values['LANG'])) { print 'selected'; } ?>> Prolog</option>
            <option value="11" <?php if (in_array('11', $values['LANG'])) { print 'selected'; } ?>> Scala</option>
        </select>
    </label>
    <br>
    <label for="BIOGRAFY">Biography:</label>
    <br>
    <textarea  name="BIOGRAFY" <?php if ($errors['BIOGRAFY']) {print 'class="error"';} ?> placeholder='Ваша история'><?php print $values['BIOGRAFY']; ?></textarea>
    <br>
    <label>
        <input type="checkbox" name="CH"
          <?php if ($errors['CH']) {print 'class="error"';} ?>
          <?php if (!empty($values['CH'])) {print "checked";}; ?>
          />
        c контрактом ознакомлен(а)
      </label>
    <button class="button"> Сохранить </button>
    <input class="button" type="submit" value="Отправить">
</form>
</body>
</html>





