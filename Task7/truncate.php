<?php
include('dbconnect.php');
$stmt = $db->prepare("TRUNCATE application; TRUNCATE languages; TRUNCATE users;");
$stmt->execute();
header('Location: index.php');