<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form5</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  
    <?php
      if (!empty($messages)) {
        print('<div id="messages">');
        // Выводим сообщения.
        foreach ($messages as $message) {
          print($message);
        }
        print('</div>');
      }
    ?>

  <div class="fomaa">
    <form action="index.php" method="POST">

      <h3 id="forma">
        ФОРМА ДЛЯ ПОДПИСКИ НА РАССЫЛКУ С ЦИТАТИМИ ПРЕПОДАВАТЕЛЕЙ ФКТИПМ
      </h3>

      <label>
        <strong>
          Фамилия имя отчество:
        </strong>
        <br>
        <input name="fio" type="text" 
          <?php if ($errors['fio']) {print 'class="error"';} ?>
          value="<?php print $values['fio']; ?>" 
          placeholder="ФИО" />
      </label>
      <br>

      <label>
        <strong>
          Номер телефона:
        </strong>
        <br>
        <input name="phone" type="tel" 
          <?php if ($errors['phone']) {print 'class="error"';} ?>
          value="<?php print $values['phone']; ?>"
        />
      </label>
      <br>

      <label>
        <strong>
          Введите вашу почту:
        </strong>
        <input name="mail" type="email" 
          <?php if ($errors['mail']) {print 'class="error"';} ?>
          value="<?php print $values['mail']; ?>"        
          placeholder="email" />
      </label>
      <br>

      <label>
        <strong> 
          Дата рождения:
        </strong>
        <br>
        <input name="birthdate" type="date"
        <?php if ($errors['birthdate']) {print 'class="error"';} ?> value="<?php print $values['birthdate']; ?>" />
      </label>

      <br>
      <strong>
        Пол:
      </strong>
      <label>
        <input type="radio" name="pol" required value="1" <?php if ($values['pol']==='1') {print 'checked';}?> >
        Мужской
      </label>
      <label>
        <input type="radio" name="pol" required value="2"  <?php if ($values['pol']==='2') {print 'checked';}?>  >
        Женский
      </label>
      <label>
        <input type="radio" name="pol" required value="3" <?php if ($values['pol']==='3') {print 'checked';}?> >
        Ламинат
      </label>
      <br>

      <strong>
        Любимый язык программирования:
      </strong>
      <div class="language">
        <label>
          <select name="langg[]" multiple="multiple"  <?php if ($errors['langg']) {print 'class="error"';}?>  >
            <option value="1" <?php if (in_array('1', $values['langg'])) { print 'selected'; } ?> > С </option>
            <option value="2" <?php if (in_array('2', $values['langg'])) { print 'selected'; } ?> > Pascal </option>
            <option value="3" <?php if (in_array('3', $values['langg'])) { print 'selected'; } ?> > Scala </option>
            <option value="4" <?php if (in_array('4', $values['langg'])) { print 'selected'; } ?> > C++ </option>
            <option value="5" <?php if (in_array('5', $values['langg'])) { print 'selected'; } ?> > Java </option>
            <option value="6" <?php if (in_array('6', $values['langg'])) { print 'selected'; } ?> > Python </option>
            <option value="7" <?php if (in_array('7', $values['langg'])) { print 'selected'; } ?> > JavaScript </option>
            <option value="8" <?php if (in_array('8', $values['langg'])) { print 'selected'; } ?> > PHP </option>
            <option value="9" <?php if (in_array('9', $values['langg'])) { print 'selected'; } ?> > Hascel </option>
            <option value="10" <?php if (in_array('10', $values['langg'])) { print 'selected'; } ?> > Clojure </option>
            <option value="11" <?php if (in_array('11', $values['langg'])) { print 'selected'; } ?> > Prolog </option>
          </select>
        </label>
      </div>

      <label>
        <strong>
          Биография:
        </strong>
        <textarea name="biog" <?php if ($errors['biog']) {print 'class="error"';} ?> placeholder="Я ждал 12 лет в Азкабане.."><?php print $values['biog']; ?>
        </textarea>
      </label>

      <label>
        <input type="checkbox" name="V"
          <?php if ($errors['V']) {print 'class="error"';} ?>
          <?php if (!empty($values['V'])) {print "checked";}; ?>
          />
        c контрактом ознакомлен(а)
      </label>

      <input type="submit" value="Сохранить" />
      
    </form>
  </div>
</body>

</html>