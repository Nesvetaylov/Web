<?php
// Configuration
const COOKIE_EXPIRATION_TIME = 30 * 24 * 60 * 60; // 30 days
const ERROR_MESSAGE_CLASS = 'error';

// Functions
function setCookie($name, $value, $expirationTime) {
    setcookie($name, $value, time() + $expirationTime);
}

function getErrorMessage($field, $message) {
    return "<div class=\"$ERROR_MESSAGE_CLASS\">$field: $message</div>";
}

function validateInput($input, $pattern) {
    return preg_match($pattern, $input);
}

// Main Logic
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['SAVE'])) {
        setcookie('SAVE', '', 100000);
        $messages[] = 'Спасибо, результаты сохранены.';
    }

    $errors = array();
    $errors['LAST_NAME'] = !empty($_COOKIE['LAST_NAME_errors']);
    $errors['FIRST_NAME'] = !empty($_COOKIE['FIRST_NAME_errors']);
    $errors['MIDDLE_NAME'] = !empty($_COOKIE['MIDDLE_NAME_errors']);
    $errors['BIRTHDATE'] = !empty($_COOKIE['BIRTHDATE_errors']);
    $errors['ADDRESS'] = !empty($_COOKIE['ADDRESS_errors']);

    if ($errors['LAST_NAME']) {
        setcookie('LAST_NAME_error', '', time() - 24 * 60 * 60);
        $messages[] = '<div class="error">Заполните фамилию пациента</div>';
    }
    if ($errors['FIRST_NAME']) {
        setcookie('FIRST_NAME_error', '', time() - 24 * 60 * 60);
        $messages[] = '<div class="error">Заполните имя пациента</div>';
    }
    if ($errors['MIDDLE_NAME']) {
        setcookie('MIDDLE_NAME_error', '', time() - 24 * 60 * 60);
        $messages[] = '<div class="error">Заполните отчество пациента</div>';
    }
    if ($errors['BIRTHDATE']) {
        setcookie('BIRTHDATE_error', '', time() - 24 * 60 * 60);
        $messages[] = '<div class="error">Заполните дату рождения</div>';
    }
    if ($errors['ADDRESS']) {
        setcookie('ADDRESS_error', '', time() - 24 * 60 * 60);
        $messages[] = '<div class="error">Заполните адрес пациента</div>';
    }

    $values = array();
    $values['LAST_NAME'] = empty($_COOKIE['LAST_NAME_value']) ? '' : $_COOKIE['LAST_NAME_value'];
    $values['FIRST_NAME'] = empty($_COOKIE['FIRST_NAME_value']) ? '' : $_COOKIE['FIRST_NAME_value'];
    $values['MIDDLE_NAME'] = empty($_COOKIE['MIDDLE_NAME_value']) ? '' : $_COOKIE['MIDDLE_NAME_value'];
    $values['BIRTHDATE'] = empty($_COOKIE['BIRTHDATE_value']) ? '' : $_COOKIE['BIRTHDATE_value'];
    $values['ADDRESS'] = empty($_COOKIE['ADDRESS_value']) ? '' : $_COOKIE['ADDRESS_value'];

    include('form_admission_of_patients.php');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $values = [];

    // Validate input
    foreach ($_POST as $field => $value) {
        switch ($field) {
            case 'LAST_NAME':
            case 'FIRST_NAME':
            case 'MIDDLE_NAME':
                if (empty($value) || !validateInput($value, '/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u')) {
                    $errors[$field] = getErrorMessage($field, 'Необходимо заполнить');
                } else {
                    setCookie($field . '_value', $value, COOKIE_EXPIRATION_TIME);
                }
                break;
            case 'BIRTHDATE':
                $dateObject = DateTime::createFromFormat('Y-m-d', $value);
                if ($dateObject === false || $dateObject->format('Y-m-d') !== $value) {
                    $errors[$field] = getErrorMessage($field, 'Укажите дату рождения');
                } else {
                    setCookie($field . '_value', $value, COOKIE_EXPIRATION_TIME);
                }
                break;
            case 'ADDRESS':
                if (empty($value)) {
                    $errors[$field] = getErrorMessage($field, 'Укажите корректный адрес');
                } else {
                    setCookie($field . '_value', $value, COOKIE_EXPIRATION_TIME);
                }
                break;
        }
    }

    if ($errors) {
        // Handle errors
        header('Location: admission_of_patients.php');
        exit();
    } else {
        // Insert data into database
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO PATIENTS (LAST_NAME, FIRST_NAME, MIDDLE_NAME, BIRTHDATE, ADDRESS) VALUES (:lastname, :firstname, :middlename, :birthdate, :address)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':lastname', $_POST['LAST_NAME']);
            $stmt->bindParam(':firstname', $_POST['FIRST_NAME']);
            $stmt->bindParam(':middlename', $_POST['MIDDLE_NAME']);
            $stmt->bindParam(':birthdate', $_POST['BIRTHDATE']);
            $stmt->bindParam(':address', $_POST['ADDRESS']);
            $stmt->execute();

            $lastId = $pdo->lastInsertId();
            echo "Пациент успешно добавлен. ID нового пациента: $lastId <br>";

            // Insert data into Appointments table
            $sql = "INSERT INTO ADMISSION_OF_PATIENTS (PATIENT_ID, DOCTOR_ID, DATE) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$lastId, $_POST["DOCTOR_ID"], $_POST["DATE"]]);
            echo "Запись на прием успешно добавлена.";
        } catch (PDOException $e) {
            echo "Ошибка при добавлении: ". $e->getMessage();
        }
    }
}

