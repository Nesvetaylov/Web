<html> 
  <head>
    <title>task 4</title>
    <meta charset="UTF-8">
    <?php echo '<link rel="stylesheet" type="text/css" href="formStyle1.css" media="screen" />'; ?>
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
    <form action="index.php" method="POST" id="svyaz">
      <h3> Для обратной связи оставьте свои данные: </h3>
      <label>
          <strong> Фамилия имя отчество:</strong>
          <br>
          <input name="fio" type="text"
          <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" placeholder="ФИО" />
      </label>
      <br>
      <label>
          <strong>Номер телефона: </strong>
          <br>
          <input name="phone" type="tel" pattern="\+7\-[0-9]{3}\-[0-9]{3}\-[0-9]{2}\-[0-9]{2}"
          <?php if ($errors['phone']) {print 'class="error"';} ?> value="<?php print $values['phone']; ?>" placeholder="+7(___)___-__-__" />
      </label>
      <br>
      <label>
          <strong> Введите вашу почту:</strong>
          <br>
          <input name="email" type="email"
          <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>" placeholder="email" />
      </label>
      <br>
      <label>
          <strong> Дата рождения:</strong>
          <br>
          <input name="birthdate" type="date"
          <?php if ($errors['birthdate']) {print 'class="error"';} ?> value="<?php print $values['birthdate']; ?>" />
      </label>
      <br>
      <strong> Пол:</strong>
      <label>
          <input type="radio" name="gender" required value="male" <?php if ($values['gender'] === 'male') { print 'checked'; } ?>>
          Мужской
      </label>
      <label>
          <input type="radio" name="gender" required value="female" <?php if ($values['gender'] === 'female') { print 'checked'; } ?>>
          Женский
      </label>
      <br>
      <label>
          <strong>Любимый язык программирования:</strong>
          <br>
          <select name="selections[]" multiple="multiple" <?php if ($errors['selections']) {print 'class="error"';} ?>>
              <option value="1" <?php if (in_array('1', $values['selections'])) { print 'selected'; } ?>> Lua</option>
              <option value="2" <?php if (in_array('2', $values['selections'])) { print 'selected'; } ?>> C</option>
              <option value="3" <?php if (in_array('3', $values['selections'])) { print 'selected'; } ?>> C++</option>
              <option value="4" <?php if (in_array('4', $values['selections'])) { print 'selected'; } ?>> C#</option>
              <option value="5" <?php if (in_array('5', $values['selections'])) { print 'selected'; } ?>> PHP</option>
              <option value="6" <?php if (in_array('6', $values['selections'])) { print 'selected'; } ?>> Python</option>
              <option value="7" <?php if (in_array('7', $values['selections'])) { print 'selected'; } ?>> Java</option>
              <option value="8" <?php if (in_array('8', $values['selections'])) { print 'selected'; } ?>> JavaScript</option>
              <option value="9" <?php if (in_array('9', $values['selections'])) { print 'selected'; } ?>> Ruby</option>
              <option value="10" <?php if (in_array('10', $values['selections'])) { print 'selected'; } ?>> Go</option>
          </select>
      </label>
      <br>
      <label>
          <strong> Биография:</strong>
          <br>
          <textarea name="biography" placeholder="Я был писателем, пока не... " 
          <?php if ($errors['biography']) {print 'class="error"';} ?>><?php print $values['biography']; ?></textarea>
      </label>
      <br>
      <label>
          <input type="checkbox" name="check" 
          <?php if ($errors['check']) {print 'class="error"';} ?> value=""/>
          c контрактом ознакомлен(а)
      </label>
      <br>
      <input type="submit" value="Сохранить" />
  </form>
  <?php 
  ?>
  </body>
</html>

