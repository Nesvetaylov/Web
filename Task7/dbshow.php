<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <title>Task 6</title>
    <link rel="stylesheet" href="db.css">
</head>
<body>
<?php
    echo '<div class="msgbox">'; 
        if (!empty($_COOKIE['updated'])) {
            echo '<p class="msg">Обновлены данные пользователя с id = ' . $_COOKIE['updated'] . '</p>';
            setcookie('updated', '', time() + 24 * 60 * 60);
        }
        if (!empty($_COOKIE['clear'])) {
            echo '<p class="msg">Удалены данные пользователя с id = ' . $_COOKIE['clear'] . '</p>';
            setcookie('clear', '', time() + 24 * 60 * 60);
        }
        if (!empty($messages)) {
            echo "<p>Данные пользователя с id = " . $_COOKIE['error_id'] . " не обновлены по следующим причинам:</br></p>";
            echo "<ol>";
            foreach ($messages as $message) {
                print('<li>' . $message . '</li>');
            }
            echo "<ol>";
        }
        echo '</div>';
    include('statistics.php');
    ?>
    <form action="" method="POST">
        <table>
            <caption>Данные формы</caption>
            <tr> 
                <th>id</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>email</th>
                <th>Год</th>
                <th>Пол</th>
                <th>Любимый язык</th>
                <th>Биография</th>
                <th><a href="truncate.php"><img src="https://cdn-icons-png.flaticon.com/512/860/860829.png" width="25" height="25" alt="truncate"></a></th>
            </tr>
            <?php
                foreach ($values as $value) {
                    echo    '<tr>';
                    echo    '<td style="font-weight: 700;">'; print($value['application_id']); echo '</td>';
                    echo    '<td>
                                <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                    else print(" "); echo 'class="input" name="name'.$value['application_id'].'" value="'; print(htmlspecialchars(strip_tags($value['name']))); echo '">
                            </td>';
                    echo    '<td>
                            <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                else print(" "); echo 'class="input" name="phone'.$value['application_id'].'" value="'; print(htmlspecialchars(strip_tags($value['phone']))); echo '">
                            </td>';
                    echo    '<td>
                                <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                    else print(" "); echo 'class="input" name="email'.$value['application_id'].'" value="'; print(htmlspecialchars(strip_tags($value['email']))); echo '">
                            </td>';
                    echo    '<td>';
                    echo        '<select'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                    else print(" "); echo 'name="year'.$value['application_id'].'">';
                                    for ($i = 2023; $i >= 1922; $i--) {
                                        if ($i == $value['year']) {
                                            printf('<option selected value="%d">%d год</option>', $i, $i);
                                        } else {
                                            printf('<option value="%d">%d год</option>', $i, $i);
                                        }
                                    }
                    echo        '</select>';
                    echo    '</td>';
                    echo    '<td> 
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="radio" id="radioMale'.$value['application_id'].'" name="gender'.$value['application_id'].'" value="male" ';
                                            if (htmlspecialchars(strip_tags($value['gender'])) == 'male') echo 'checked'; echo '>
                                    <label for="radioMale'.$value['application_id'].'">Мужчина</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="radio" id="radioFemale'.$value['application_id'].'" name="gender'.$value['application_id'].'" value="female" ';
                                            if (htmlspecialchars(strip_tags($value['gender'])) == 'female') echo 'checked'; echo '>
                                    <label for="radioFemale'.$value['application_id'].'">Женщина</label>
                                </div>
                            </td>';
                    $stmt = $db->prepare("SELECT language_id FROM languages WHERE application_id = ?");
                    $stmt->execute([$value['application_id']]);
                    $languages = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    echo    '<td class="languages">
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Pascal'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="1"' . (in_array(1, $languages) ? ' checked' : '') . '>
                                    <label for="Pascal'.$value['application_id'].'">Pascal</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="C'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="2"' . (in_array(2, $languages) ? ' checked' : '') . '>
                                    <label for="C'.$value['application_id'].'">C</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Cpp'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="3"' . (in_array(3, $languages) ? ' checked' : '') . '>
                                    <label for="Cpp'.$value['application_id'].'">C++</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="JavaScript'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="4"' . (in_array(4, $languages) ? ' checked' : '') . '>
                                    <label for="JavaScript'.$value['application_id'].'">JavaScript</label>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="PHP'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="5"' . (in_array(5, $languages) ? ' checked' : '') . '>
                                    <label for="PHP'.$value['application_id'].'">PHP</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Python'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="6"' . (in_array(6, $languages) ? ' checked' : '') . '>
                                    <label for="Python'.$value['application_id'].'">Python</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Java'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="7"' . (in_array(7, $languages) ? ' checked' : '') . '>
                                    <label for="Java'.$value['application_id'].'">Java</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Haskel'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="8"' . (in_array(8, $languages) ? ' checked' : '') . '>
                                    <label for="Haskel'.$value['application_id'].'">Haskel</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Clojure'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="9"' . (in_array(9, $languages) ? ' checked' : '') . '>
                                    <label for="Clojure'.$value['application_id'].'">Clojure</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Prolog'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="10"' . (in_array(10, $languages) ? ' checked' : '') . '>
                                    <label for="Prolog'.$value['application_id'].'">Prolog</label>
                                </div>
                                <div class="column-item">
                                    <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                        else print(" "); echo 'type="checkbox" id="Scala'.$value['application_id'].'" name="languages'.$value['application_id'].'[]" value="11"' . (in_array(11, $languages) ? ' checked' : '') . '>
                                    <label for="Scala'.$value['application_id'].'">Scala</label>
                                </div>
                            </td>';
                    echo    '<td>
                                <textarea'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) print(" disabled ");
                                    else print(" "); echo 'name="biography'.$value['application_id'].'" id="" cols="30" rows="4" maxlength="128">';
                                        print htmlspecialchars(strip_tags($value['biography'])); echo '</textarea>
                            </td>';
                    echo    '<td>';
                if (empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['application_id'])) {
                    echo        '<div class="column-item">
                                    <input name="edit'.$value['application_id'].'" type="image" src="https://static.thenounproject.com/png/2185844-200.png" width="25" height="25" alt="submit"/>
                                </div>
                                <div class="column-item">
                                    <input name="clear'.$value['application_id'].'" type="image" src="https://cdn-icons-png.flaticon.com/512/860/860829.png" width="25" height="25" alt="submit"/>
                                </div>';
                } else {
                    echo        '<div class="column-item">
                                    <input name="save'.$value['application_id'].'" type="image" src="https://cdn-icons-png.flaticon.com/512/84/84138.png" width="25" height="25" alt="submit"/>
                                </div>';
                }
                    echo    '</td>';
                    echo    '</tr>'; 
                }
            ?>
        </table>
        <?php if (!empty($_SESSION['login'])) {echo '<input type="hidden" name="token" value="' . $_SESSION["token"] . '">'; } ?>
    </form>
</body>
</html>