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
<form class='form-container' action="index.php" method="POST">
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
        <select class="lab" name="Lang_Prog[]" multiple="multiple">
            <option value="1" <?php if (in_array('1', $values['Lang_Prog'])) { print 'selected'; } ?>> Pascal</option>
            <option value="2" <?php if (in_array('2', $values['Lang_Prog'])) { print 'selected'; } ?>> C</option>
            <option value="3" <?php if (in_array('3', $values['Lang_Prog'])) { print 'selected'; } ?>> C++</option>
            <option value="4" <?php if (in_array('4', $values['Lang_Prog'])) { print 'selected'; } ?>> JavaScript</option>
            <option value="5" <?php if (in_array('5', $values['Lang_Prog'])) { print 'selected'; } ?>> PHP</option>
            <option value="6" <?php if (in_array('6', $values['Lang_Prog'])) { print 'selected'; } ?>> Python</option>
            <option value="7" <?php if (in_array('7', $values['Lang_Prog'])) { print 'selected'; } ?>> Java</option>
            <option value="8" <?php if (in_array('8', $values['Lang_Prog'])) { print 'selected'; } ?>> Haskel</option>
            <option value="9" <?php if (in_array('9', $values['Lang_Prog'])) { print 'selected'; } ?>> Clojure</option>
            <option value="10" <?php if (in_array('10', $values['Lang_Prog'])) { print 'selected'; } ?>> Prolog</option>
            <option value="11" <?php if (in_array('11', $values['Lang_Prog'])) { print 'selected'; } ?>> Scala</option>
        </select>
    </label>
    <br>
    <label for="BIOGRAFY">Biography:</label>
    <br>
    <textarea  name="BIOGRAFY" <?php if ($errors['BIOGRAFY']) {print 'class="error"';} ?> placeholder='Ваша история'><?php print $values['BIOGRAFY']; ?></textarea>
    <br>
    <label>
        <input type="checkbox" checked="checked">
    </label> С контрактом ознакомлен(а)<br>
    <button class="button"> Сохранить </button>
    <input class="button" type="submit" value="Отправить">
</form>
</body>
</html>





