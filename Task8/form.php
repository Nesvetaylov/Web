<html>
<head>
    <style>
   .error {
        border: 2px solid red;
    }
    </style>
    <link rel="stylesheet" href="style.css">
    <meta charset="utf-8">
    <title>Form</title>
</head>

<body>
    <form id="form" action="index.php" method="post">
        <label>
            <strong> Фамилия имя отчество:</strong>
            <br>
            <input name="FIO" type="text" id="FIO" value="<?php print $values['FIO'];?>" placeholder="ФИО" />
</label>
        <br>
        <label for="PHONE">Phone number:</label>
        <input name="PHONE" id="PHONE" value="<?php print $values['PHONE'];?>">
        <br>
        <label for="EMAIL">e-mail:</label>
        <input name="EMAIL" id="EMAIL" value="<?php print $values['EMAIL'];?>">
        <br>
        <label for="BIRTHDATE">Date of Birth:</label>
        <input value="2023-09-24" name="BIRTHDATE" id="BIRTHDATE" value="<?php print $values['BIRTHDATE'];?>">
        <br>
        <label for="GENDER">Choose gender:</label>
        <input type="radio" id="male" name="GENDER" value="male" <?php if($values['GENDER']=='male'){print 'checked';}?>>
        <label for="male">Мужской</label>
        <input type="radio" id="female" name="GENDER" value="female" <?php if($values['GENDER']=='female'){print 'checked';}?>>
        <label for="male">Женский</label>
        <br>
        <label>
            <select class="lab" name="Lang_Prog[]" multiple="multiple">
                <option value="1" <?php if (in_array('1', $values['Lang_Prog'])) { print 'elected'; }?>> Pascal</option>
                <option value="2" <?php if (in_array('2', $values['Lang_Prog'])) { print 'elected'; }?>> C</option>
                <option value="3" <?php if (in_array('3', $values['Lang_Prog'])) { print 'elected'; }?>> C++</option>
                <option value="4" <?php if (in_array('4', $values['Lang_Prog'])) { print 'elected'; }?>> JavaScript</option>
                <option value="5" <?php if (in_array('5', $values['Lang_Prog'])) { print 'elected'; }?>> PHP</option>
                <option value="6" <?php if (in_array('6', $values['Lang_Prog'])) { print 'elected'; }?>> Python</option>
                <option value="7" <?php if (in_array('7', $values['Lang_Prog'])) { print 'elected'; }?>> Java</option>
                <option value="8" <?php if (in_array('8', $values['Lang_Prog'])) { print 'elected'; }?>> Haskel</option>
                <option value="9" <?php if (in_array('9', $values['Lang_Prog'])) { print 'elected'; }?>> Clojure</option>
                <option value="10" <?php if (in_array('10', $values['Lang_Prog'])) { print 'elected'; }?>> Prolog</option>
                <option value="11" <?php if (in_array('11', $values['Lang_Prog'])) { print 'elected'; }?>> Scala</option>
            </select>
        </label>
        <br>
        <label for="BIOGRAFY">Biography:</label>
        <br>
        <textarea  name="BIOGRAFY" id="BIOGRAFY" placeholder='Ваша история'><?php print $values['BIOGRAFY'];?></textarea>
        <br>
        <label>
            <input type="checkbox" checked="checked">
        </label> С контрактом ознакомлен(а)<br>
        <button class="button" id="submit"> Сохранить </button>
    </form>

    <script>
        const form = document.getElementById('form');
        const submitButton = document.getElementById('submit');

        submitButton.addEventListener('click', (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const jsonData = {};

            for (const [key, value] of formData) {
                jsonData[key] = value;
            }

            fetch('index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
           .then(response => response.json())
           .then(data => console.log(data))
           .catch(error => console.error(error));
        });
    </script>
</body>
</html>