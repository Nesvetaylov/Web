<?php
header('Content-Type: text/html; charset=UTF-8');

// Include database configuration and connection
include('../Secret.php');

// HTTP Basic Authentication
$username = username;
$password = password;

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
            echo '<tr><th>ID</th><th>FIO</th><th>Phone</th><th>Email</th><th>Birthdate</th><th>Gender</th><th>Biography</th><th>Actions</th></tr>';

            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . $row['ID'] . '</td>';
                echo '<td>' . htmlspecialchars($row['FIO']) . '</td>';
                echo '<td>' . htmlspecialchars($row['PHONE']) . '</td>';
                echo '<td>' . htmlspecialchars($row['EMAIL']) . '</td>';
                echo '<td>' . htmlspecialchars($row['BIRTHDATE']) . '</td>';
                echo '<td>' . htmlspecialchars($row['GENDER']) . '</td>';
                echo '<td>' . htmlspecialchars($row['BIOGRAFY']) . '</td>';
                echo '<td><a href="?id=' . $row['ID'] . '&lang=1">Edit</a> | <a href="?id=' . $row['ID'] . '&lang=2">Delete</a></td>';
                echo '</tr>';
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

    if (isset($_POST['id']) && isset($_POST['FIO']) && isset($_POST['PHONE']) && isset($_POST['EMAIL']) && isset($_POST['BIRTHDATE']) && isset($_POST['GENDER']) && isset($_POST['BIOGRAFY'])) {
        try {
            $stmt = $conn->prepare('UPDATE REQUEST SET FIO=?, PHONE=?, EMAIL=?, BIRTHDATE=?, GENDER=?, BIOGRAFY=? WHERE ID=?');
            $stmt->execute([$_POST['FIO'], $_POST['PHONE'], $_POST['EMAIL'], $_POST['BIRTHDATE'], $_POST['GENDER'], $_POST['BIOGRAFY'], $_POST['id']]);

            echo 'User data updated successfully.';
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Invalid request.';
    }
}

function deleteUserData()
{
    global $conn;

    if (isset($_GET['id'])) {
        try {
            $stmt = $conn->prepare('DELETE FROM REQUEST WHERE ID=?');
            $stmt->execute([$_GET['id']]);

            echo 'User data deleted successfully.';
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Invalid request.';
    }
}

function showLanguageStats()
{
    global $conn;

    if (isset($_GET['id'])) {
        $langId = $_GET['lang'];

        try {
            $stmt = $conn->prepare('SELECT Lang_NAME, COUNT(*) as count FROM Lang_Prog INNER JOIN ANSWER ON Lang_Prog.Lang_ID = ANSWER.Lang_ID INNER JOIN REQUEST ON ANSWER.ID = REQUEST.ID GROUP BY Lang_NAME');
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                echo '<table border="1">';
                echo '<tr><th>Language</th><th>Count</th></tr>';

                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['Lang_NAME']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['count']) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo 'No data found.';
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo'Invalid request.';
    }
}

$conn = null;
