<?php
header('Content-Type: text/html; charset=UTF-8');

// Include database configuration and connection
include('../Secret.php');

// HTTP Basic Authentication
$adminUsername = 'u67281';
$adminPassword = '9872763';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] != $adminUsername || $_SERVER['PHP_AUTH_PW'] != $adminPassword) {
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
        $stmt = $conn->prepare('SELECT * FROM User_Prog');
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Language</th><th>Username</th><th>Program</th><th>Action</th></tr>';

            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Lang_NAME']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Username']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Program']) . '</td>';
                echo '<td><form method="post" action="">
                        <input type="hidden" name="id" value="' . htmlspecialchars($row['ID']) . '">
                        <input type="hidden" name="action" value="edit">
                        <input type="submit" value="Edit">
                      </form>
                      <form method="post" action="">
                        <input type="hidden" name="id" value="' . htmlspecialchars($row['ID']) . '">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Delete">
                      </form></td>';
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

function editUserData()
{
    global $conn;

    $id = $_POST['id'];
    $username = $_POST['username'];
    $program = $_POST['program'];

    try {
        $stmt = $conn->prepare('UPDATE User_Prog SET Username = :username, Program = :program WHERE ID = :id');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':program', $program);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo 'User data updated successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function deleteUserData()
{
    global $conn;

    $id = $_POST['id'];

    try {
        $stmt = $conn->prepare('DELETE FROM User_Prog WHERE ID = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo 'User data deleted successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'edit') {
        editUserData();
    } else if ($_POST['action'] == 'delete') {
        deleteUserData();
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'stats') {
        showLanguageStats();
    }
}

showUserData();

?>
