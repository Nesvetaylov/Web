<?php
header('Content-Type: application/json; charset=UTF-8');

// Configuration
$username = 'username';
$password = 'password';
$dbname = 'username';

// Connect to database
$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get JSON data from request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate data
    $errors = validateData($data);

    if ($errors) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data', 'errors' => $errors]);
        exit;
    }

    // Save data to database
    $stmt = $conn->prepare("INSERT INTO REQUEST (FIO, PHONE, EMAIL, BIRTHDATE, GENDER, BIOGRAFY) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$data['FIO'], $data['PHONE'], $data['EMAIL'], $data['BIRTHDATE'], $data['GENDER'], $data['BIOGRAFY']]);

    $lastId = $conn->lastInsertId();

    // Save languages to database
    foreach ($data['Lang_Prog'] as $lang) {
        $stmt = $conn->prepare("INSERT INTO ANSWER (ID, Lang_ID) VALUES (?,?)");
        $stmt->execute([$lastId, $lang]);
    }

    // Return response
    http_response_code(201);
    echo json_encode(['message' => 'Data saved successfully', 'id' => $lastId]);
    exit;
}

// Handle GET requests (for authorized users)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get user ID from session or token
    $userId = $_SESSION['user_id'];

    // Get user data from database
    $stmt = $conn->prepare("SELECT * FROM REQUEST WHERE ID =?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch();

    // Return response
    http_response_code(200);
    echo json_encode(['data' => $userData]);
    exit;
}

// Validate data function
function validateData($data) {
    $errors = [];

    // Validate FIO
    if (empty($data['FIO']) ||!preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $data['FIO'])) {
        $errors['FIO'] = 'Invalid FIO';
    }

    // Validate PHONE
    if (empty($data['PHONE']) ||!preg_match('/^[0-9+]+$/', $data['PHONE'])) {
        $errors['PHONE'] = 'Invalid PHONE';
    }

    // Validate EMAIL
    if (empty($data['EMAIL']) ||!filter_var($data['EMAIL'], FILTER_VALIDATE_EMAIL)) {
        $errors['EMAIL'] = 'Invalid EMAIL';
    }

    // Validate BIRTHDATE
    if (empty($data['BIRTHDATE']) ||!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['BIRTHDATE'])) {
        $errors['BIRTHDATE'] = 'Invalid BIRTHDATE';
    }

    // Validate GENDER
    if (empty($data['GENDER']) ||!in_array($data['GENDER'], ['male', 'female'])) {
        $errors['GENDER'] = 'Invalid GENDER';
    }

    // Validate BIOGRAFY
    if (empty($data['BIOGRAFY'])) {
        $errors['BIOGRAFY'] = 'Invalid BIOGRAFY';
    }

    // Validate Lang_Prog
    if (empty($data['Lang_Prog'])) {
        $errors['Lang_Prog'] = 'Invalid Lang_Prog';
    }

    return $errors;
}

// Close database connection
$conn = null;