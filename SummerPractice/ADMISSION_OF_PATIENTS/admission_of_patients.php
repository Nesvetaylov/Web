<?php
// Конфигурация
const DB_HOST = 'localhost';
const DB_USERNAME = 'username';
const DB_PASSWORD = 'password';
const DB_NAME = 'username';

// Обработка ошибок
function errorHandler($error) {
    // Логирование ошибки или отображение сообщения об ошибке
    echo "Ошибка: $error";
    exit;
}

// Соединение с базой данных
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    errorHandler($e->getMessage());
}

// Валидация формы
function validateForm($data) {
    $errors = [];
    foreach ($data as $field => $value) {
        if (empty($value)) {
            $errors[$field] = "Поле является обязательным";
        } elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $value)) {
            $errors[$field] = "Неверный формат";
        }
    }
    return $errors;
}

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST;
    $errors = validateForm($data);
    if (empty($errors)) {
        // Вставка данных в базу данных
        $stmt = $conn->prepare("INSERT INTO PATIENTS (LAST_NAME, FIRST_NAME, MIDDLE_NAME, BIRTHDATE, ADDRESS) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['LAST_NAME'], $data['FIRST_NAME'], $data['MIDDLE_NAME'], $data['BIRTHDATE'], $data['ADDRESS']]);
        $patientId = $conn->lastInsertId();
        
        // Вставка данных о приеме в базу данных
        $stmt = $conn->prepare("INSERT INTO ADMISSION_OF_PATIENTS (PATIENT_ID, DOCTOR_ID, DATE) VALUES (?, ?, ?)");
        $stmt->execute([$patientId, $data['DOCTOR_ID'], $data['DATE']]);
        echo "Прием добавлен успешно";
    } else {
        // Отображение сообщений об ошибках
        foreach ($errors as $field => $error) {
            echo "<div class='error'>$error</div>";
        }
    }
}

// Отображение формы
include('form_admission_of_patients.php');
