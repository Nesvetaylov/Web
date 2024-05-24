<?php
header('Content-Type: text/html; charset=UTF-8');

// Include database configuration and connection
include('../Secret.php');

// HTTP Basic Authentication
$adminUsername = username;
$adminPassword = password;

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] != $username || $_SERVER['PHP_AUTH_PW'] != $password) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Access denied.';
    exit();
}

$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    // Handle form submissions
    switch ($_POST['action']) {
        case 'edit':
            editUserData();
            break;
        case 'delete':
            deleteUserData();
            break;
    }
} elseif (isset($_GET['id']) && isset($_GET['lang'])) {
    // Handle language statistics
    showLanguageStats();
} else {
    // Display user data
    showUserData();
}

function showUserData()
{
    global $conn;

    try {
        $stmt = $conn->query('SELECT * FROM REQUEST');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>FIO</th><th>Login</th><th>Email</th><th>Prog</th><th>Lang</th></tr>';

            foreach ($results as $row) {
                echo "<tr><td>{$row['ID']}</td><td>{$row['FIO']}</td><td>{$row['Login']}</td><td>{$row['Email']}</td><td>{$row['Prog']}</td><td>{$row['Lang']}</td></tr>";
            }

            echo '</table>';
        } else {
            echo 'No data found.';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function showLanguageStats()
{
    global $conn;

    try {
        $stmt = $conn->prepare('SELECT Lang, COUNT(*) as count FROM User_Prog WHERE Lang = :lang GROUP BY Lang');
        $stmt->bindParam(':lang', $_GET['lang']);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            echo '<table border="1">';
            echo '<tr><th>Language</th><th>Count</th></tr>';

            foreach ($results as $row) {
                echo "<tr><td>{$row['Lang']}</td><td>{$row['count']}</td></tr>";
            }

            echo '</table>';
        } else {
            echo 'No data found.';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function editUserData()
{
    global $conn;

    try {
        $stmt = $conn->prepare('UPDATE User_Prog SET FIO = :fio, Login = :login, Email = :email, Prog = :prog, Lang = :lang WHERE ID = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->bindParam(':fio', $_POST['fio']);
        $stmt->bindParam(':login', $_POST['login']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':prog', $_POST['prog']);
        $stmt->bindParam(':lang', $_POST['lang']);
        $stmt->execute();

        echo 'User data updated successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function deleteUserData()
{
    global $conn;

    try {
        $stmt = $conn->prepare('DELETE FROM User_Prog WHERE ID = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        echo 'User data deleted successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
