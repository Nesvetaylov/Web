<head>
    <meta charset="utf-8">
    <title>
        <a 3 Задание>
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
<form action="index.php" method="POST">
    <label for="FIO">Full name:</label>
    <input class="lab" type="text" id="FIO" name="FIO" required>
    <br>
    <label for="PHONE">Phone number:</label>
    <input class="lab" type="tel" id="PHONE" name="PHONE" required>
    <br>
    <label for="EMAIL">e-mail:</label>
    <input class="lab" type="email" id="EMAIL" name="EMAIL" required>
    <br>
    <label for="BIRTHDATE">Date of Birth:</label>
    <input class="lab" value="2023-09-24" type="date" id="BIRTHDATE" name="BIRTHDATE" required>
    <br>
    <label for="GENDER">Choose gender:</label>
    <input type="radio" id="male" name="GENDER" value="male" required>
    <label for="male">Мужской</label>
    <input type="radio" id="female" name="GENDER" value="female" required>
    <label for="male">Женский</label>
    <br>
    <label>
        <select class="lab" name="Lang_Prog[]" multiple="multiple">
            <option value=""> Your favorite language</option>
            <option value="1"> Pascal</option>
            <option value="2"> C</option>
            <option value="3"> C++</option>
            <option value="4"> JavaScript</option>
            <option value="5"> PHP</option>
            <option value="6"> Python</option>
            <option value="7"> Java</option>
            <option value="8"> Haskel</option>
            <option value="9"> Clojure</option>
            <option value="10"> Prolog</option>
            <option value="11"> Scala</option>
        </select>
    </label>
    <br>
    <label for="BIOGRAFY">Biography:</label>
    <br>
    <textarea class="lab" name="BIOGRAFY" required> Ваша история </textarea>
    <br>
    <label>
        <input type="checkbox" checked="checked">
    </label> С контрактом ознакомлен(а)<br>
    <button class="button"> Сохранить </button>
    <input class="button" type="submit" value="Отправить">
</form>
</body>
